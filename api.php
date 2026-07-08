<?php
// api.php
header('Content-Type: application/json');

// 提供免费的 Google STUN 服务器。
// 如果后续需要支持严格的对称 NAT 网络穿透，可以在此添加你自己部署的 TURN 服务器配置。
echo json_encode([
    'iceServers' => [
        ['urls' => 'stun:stun.l.google.com:19302'],
        ['urls' => 'stun:stun1.l.google.com:19302']
    ]
]);