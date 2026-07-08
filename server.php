<?php
// server.php
// 运行方式: php server.php

error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

$host = '0.0.0.0';
$port = 8282;

$server = stream_socket_server("tcp://$host:$port", $errno, $errstr);
if (!$server) {
    die("无法绑定套接字: $errstr ($errno)\n");
}
echo "信令服务器已启动: ws://$host:$port\n";

$clients = []; // 存储所有客户端资源 (resource ID => socket)
$rooms = [];   // 记录房间信息 [room_name => [resource_id => socket, ...]]
$clientInfo = []; // 记录客户端所在的房间 [resource_id => room_name]

$read = [];
$write = null;
$except = null;

while (true) {
    $read = $clients;
    $read[] = $server;
    
    // 多路复用非阻塞监听
    if (stream_select($read, $write, $except, null) === false) break;

    // 处理新连接
    if (in_array($server, $read)) {
        $client = stream_socket_accept($server);
        if ($client) {
            $clientId = (int)$client;
            $clients[$clientId] = $client;
            
            // 执行 WebSocket 握手
            $headers = fread($client, 1024);
            if (preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $headers, $match)) {
                $key = base64_encode(sha1($match[1] . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));
                $upgrade = "HTTP/1.1 101 Switching Protocols\r\n" .
                           "Upgrade: websocket\r\n" .
                           "Connection: Upgrade\r\n" .
                           "Sec-WebSocket-Accept: $key\r\n\r\n";
                fwrite($client, $upgrade);
                echo "Client [$clientId] 已连接.\n";
            } else {
                fclose($client);
                unset($clients[$clientId]);
            }
        }
        unset($read[array_search($server, $read)]);
    }

    // 处理现有客户端的消息
    foreach ($read as $client) {
        $clientId = (int)$client;
        $data = fread($client, 8192); // WebRTC 描述文件(SDP)可能较大，设置 8KB 缓冲

        if ($data === false || strlen($data) === 0) {
            handleDisconnect($clientId);
            continue;
        }

        $decodedData = decodeWebSocketFrame($data);
        if ($decodedData === false) {
            handleDisconnect($clientId);
            continue;
        }

        $msg = json_decode($decodedData, true);
        if (!$msg || !isset($msg['type'])) continue;

        handleMessage($clientId, $client, $msg);
    }
}

// ---- 业务处理逻辑 ----

function handleMessage($clientId, $client, $msg) {
    global $rooms, $clientInfo;

    $type = $msg['type'];

    if ($type === 'join') {
        $room = $msg['room'] ?? '';
        if (!$room) return;

        if (!isset($rooms[$room])) $rooms[$room] = [];

        // 限制每个房间最多2人
        if (count($rooms[$room]) >= 2) {
            sendToClient($client, ['type' => 'full']);
            return;
        }

        $rooms[$room][$clientId] = $client;
        $clientInfo[$clientId] = $room;

        $peers = array_diff(array_keys($rooms[$room]), [$clientId]);
        $isPolite = count($peers) > 0;

        // 通知自己加入成功
        sendToClient($client, [
            'type' => 'joined',
            'polite' => $isPolite,
            'peers' => array_values($peers)
        ]);

        // 通知房间内的另一个端
        broadcastToRoom($room, $clientId, ['type' => 'peer-joined']);
        echo "Client [$clientId] 加入房间: $room\n";
    } 
    elseif (in_array($type, ['offer', 'answer', 'candidate'])) {
        // SDP 和 ICE 直接透传给房间内的另一方
        if (isset($clientInfo[$clientId])) {
            $room = $clientInfo[$clientId];
            broadcastToRoom($room, $clientId, $msg);
        }
    } 
    elseif ($type === 'bye') {
        if (isset($clientInfo[$clientId])) {
            $room = $clientInfo[$clientId];
            broadcastToRoom($room, $clientId, ['type' => 'peer-left']);
            unset($rooms[$room][$clientId]);
            unset($clientInfo[$clientId]);
        }
    }
}

function handleDisconnect($clientId) {
    global $clients, $rooms, $clientInfo;
    
    if (isset($clientInfo[$clientId])) {
        $room = $clientInfo[$clientId];
        broadcastToRoom($room, $clientId, ['type' => 'peer-left']);
        unset($rooms[$room][$clientId]);
        if (empty($rooms[$room])) unset($rooms[$room]);
    }
    
    if (isset($clients[$clientId])) {
        fclose($clients[$clientId]);
        unset($clients[$clientId]);
    }
    unset($clientInfo[$clientId]);
    echo "Client [$clientId] 断开连接.\n";
}

function broadcastToRoom($room, $excludeClientId, $msgArray) {
    global $rooms;
    if (!isset($rooms[$room])) return;
    foreach ($rooms[$room] as $id => $client) {
        if ($id !== $excludeClientId) {
            sendToClient($client, $msgArray);
        }
    }
}

function sendToClient($client, $msgArray) {
    $encoded = encodeWebSocketFrame(json_encode($msgArray));
    @fwrite($client, $encoded);
}

// ---- WebSocket 协议帧处理 ----

function decodeWebSocketFrame($data) {
    if (strlen($data) < 2) return false;
    
    $firstByte = ord($data[0]);
    $opcode = $firstByte & 0x0F;
    if ($opcode === 0x08) return false; // Connection Close Frame
    
    $secondByte = ord($data[1]);
    $isMasked = ($secondByte & 0x80) ? true : false;
    $payloadLength = $secondByte & 0x7F;

    $offset = 2;
    if ($payloadLength === 126) {
        $payloadLength = unpack('n', substr($data, 2, 2))[1];
        $offset = 4;
    } elseif ($payloadLength === 127) {
        $payloadLength = unpack('J', substr($data, 2, 8))[1];
        $offset = 10;
    }

    if (!$isMasked) return substr($data, $offset);

    $masks = substr($data, $offset, 4);
    $offset += 4;
    $payload = substr($data, $offset, $payloadLength);

    $decoded = '';
    for ($i = 0; $i < $payloadLength; $i++) {
        $decoded .= $payload[$i] ^ $masks[$i % 4];
    }
    return $decoded;
}

function encodeWebSocketFrame($payload) {
    $length = strlen($payload);
    $frame = chr(0x81); // 文本帧，FIN 开启

    if ($length <= 125) {
        $frame .= chr($length);
    } elseif ($length <= 65535) {
        $frame .= chr(126) . pack('n', $length);
    } else {
        $frame .= chr(127) . pack('J', $length);
    }
    $frame .= $payload;
    return $frame;
}