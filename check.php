<?php
// check.php
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SheepDrop 环境与连通性检测</title>
    <style>
/* AeroDrop 风格诊断页面 */ .diagnostic-page{ width:min(760px,92%); margin:40px auto; } .diagnostic-page .glass{ background:rgba(255,255,255,.55); backdrop-filter:blur(18px); -webkit-backdrop-filter:blur(18px); border-radius:24px; border:1px solid rgba(255,255,255,.6); box-shadow: 0 10px
30pxrgba(0,0,0,.08); } .diagnostic-page .section{ margin-top:16px; background:rgba(255,255,255,.35); border-radius:18px; padding:18px; } .diagnostic-page h2{ font-size:16px; margin:0 0 12px; font-weight:600; } .diagnostic-page pre{ margin:0; padding:16px;
background:rgba(245,245,247,.75);border-radius:14px; border:1px solid rgba(0,0,0,.05); font-size:13px; line-height:1.7; color:#333; }

    </style>
</head>
<body>
    <div class="diagnostic-page">
        <!-- PHP 环境检测部分 -->
        <div class="section">
            <h2> 第 1 步：PHP 后端环境检测</h2>
            <pre class="log-info">
<?php
echo "=== SheepDrop 运行环境检测 ===\n\n";
echo "PHP Version: " . PHP_VERSION . "\n\n";

$required_extensions = ['sockets', 'json', 'openssl', 'pcntl'];

foreach ($required_extensions as $ext) {
    $loaded = extension_loaded($ext);
    echo str_pad($ext, 15) . ": ";
    if ($loaded) {
        echo "<span class='log-success'>✅ 已安装</span>\n";
    } else {
        echo "<span class='log-error'>❌ 未安装 (请在 PHP 环境中开启)</span>\n";
    }
}

echo "\n注：核心 WebSocket 功能主要依赖 sockets 和 json 扩展。";
?>
            </pre>
        </div>

        <!-- JS 连通性检测部分 -->
        <div class="section">
            <h2> 第 2 步：前端连通性检测 (Nginx / WebSocket)</h2>
            <pre id="js-output"></pre>
        </div>
    </div>

    <script>
        // 日志打印函数，将 console 输出同步到网页上
        const output = document.getElementById('js-output');
        function logToPage(message, type = 'default') {
            const div = document.createElement('div');
            div.textContent = message;
            
            if (type === 'error') div.className = 'log-error';
            else if (type === 'warn') div.className = 'log-warn';
            else if (message.includes('✅')) div.className = 'log-success';
            else div.className = 'log-info';
            
            output.appendChild(div);
            // 保持真实控制台的输出，方便高级用户
            if(type === 'error') console.error(message);
            else if(type === 'warn') console.warn(message);
            else console.log(message);
        }

        (async function runDiagnostics() {
            logToPage("=== 🔍 开始 SheepDrop 连通性检测 ===");

            // 1. 检测 ICE 接口
            logToPage("⏳ [1/2] 正在检测 ICE 接口 (/api/ice)...");
            try {
                const iceRes = await fetch('/api/ice');
                const text = await iceRes.text();
                try {
                    const json = JSON.parse(text);
                    logToPage("✅ ICE 接口正常！成功获取 JSON 数据:\n" + JSON.stringify(json, null, 2));
                } catch (e) {
                    logToPage("❌ ICE 接口异常！Nginx 配置未生效，请求被拦截或返回了网页（404等）。", "error");
                    logToPage("🔻 服务器实际返回的错误内容如下：\n" + (text.substring(0, 300) + (text.length > 300 ? '...' : '')), "warn");
                }
            } catch (err) {
                logToPage("❌ ICE 请求被浏览器彻底拦截 (可能是跨域或 HTTPS 问题):\n" + err, "error");
            }

            // 2. 检测 WebSocket 接口
            logToPage("\n⏳ [2/2] 正在检测 WebSocket 接口 (/ws)...");
            const protocol = location.protocol === 'https:' ? 'wss' : 'ws';
            const wsUrl = `${protocol}://${location.host}/ws`;
            logToPage(`📡 尝试连接到: ${wsUrl}`);
            
            const ws = new WebSocket(wsUrl);
            ws.onopen = () => {
                logToPage("✅ WebSocket 连接成功！Nginx 反向代理配置完全正确，且 PHP 后台运行正常。");
                ws.close();
            };
            ws.onerror = (err) => {
                logToPage("❌ WebSocket 连接失败！原因可能是：1. Nginx 的 /ws 代理没配置好；2. PHP 后台进程退出了。", "error");
            };
            ws.onclose = (e) => {
                if (e.code !== 1000 && e.code !== 1005) {
                    logToPage(`⚠️ WebSocket 异常断开，状态码: ${e.code}`, "warn");
                } else {
                    logToPage("ℹ️ WebSocket 测试连接已顺利关闭。");
                }
            };
        })();
    </script>
</body>
</html>