# 🚀 AeroDrop

> 简单、私密、高速的点对点文件传输工具

AeroDrop 是一款基于 **WebRTC P2P 技术** 的轻量级文件传输工具。

无需安装客户端、无需注册账号，通过房间链接或二维码即可在浏览器之间直接传输：

- 📄 文件
- 🖼 图片
- 💬 文本消息

## ✨ 特性

- 🔗 WebRTC 点对点传输
- 🔒 数据直连，不保存用户文件
- 📦 支持大文件分片传输
- 🌐 支持 Windows / macOS / Android / iOS
- 📱 支持房间号、链接、二维码快速连接
- 🌍 中文 / English 双语言支持

## 🚀 在线体验

https://i.ximi.me/

## 🛠 技术

- HTML5 / CSS3 / JavaScript
- WebRTC DataChannel
- WebSocket 信令服务

## 📦 部署要求

需要：
- WebSocket 信令服务
- 支持 HTTPS 环境（推荐）
- php扩展:sockets,json,openssl,pcntl 
- 上传所有文件至战斗目录,访问check.php 可检查是否满足部署要求
### Nginx 配置

- 配置仅供参考,其中`http://172.18.0.2:8282`请替换为你自己服务器本地ip与端口
```nginx
# 1. 处理 ICE 路由 (解决 JSON 解析报错)
    location /api/ice {
        try_files $uri /api.php?$query_string;
    }

    # 2. 处理 WebSocket 代理 (解决连不上后端的问题)
    location /ws {
        proxy_pass http://172.18.0.2:8282;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
        proxy_read_timeout 86400;
        proxy_send_timeout 86400;
    }
```

## 📌 适用场景

- 设备间文件互传
- 临时文件分享
- 隐私文件传输
- 跨平台快速传输


 ### 关于

- 1.作者:希米
- 2.原文：https://www.ximi.me/post-6045.html
- 3.最后更新:2026-07-08
- 前台框架样式参考于:[洋小傻](https://filetransfer.caiyy.com)
