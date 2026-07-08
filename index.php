<!-- 
		发布地址：https://www.ximi.me/post-6045.html
		版本：v1.01 
        作者：希米
        说明：AeroDrop · 简单、私密的点对点传输
		更新时间：2026-07-08

-->
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,viewport-fit=cover">
  <title>文件传输助手 / AeroDrop</title>
  <meta name="description" content="文件传输助手 AeroDrop，私密的点对点文件、图片和文本传输工具。">
  <meta name="robots" content="index,follow">
  <meta name="theme-color" content="#ffffff">
  <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg: #f4f4f2;
      --card-glass: rgba(255, 255, 255, 0.45);
      --card-border: rgba(255, 255, 255, 0.5);
      --text: #2d3748;
      --muted: #4a5568;
      --line: rgba(255, 255, 255, 0.6);
      --dark: #2d3748;
      --ok: #2f855a;
      --warn: #b7791f;
      --bad: #c53030;
      --blue: #2b6cb0;
    }
    * { box-sizing: border-box; }
    html, body { height: 100%; margin: 0; }
    
    body {
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Noto Sans SC", sans-serif;
      /* 背景图：使用清爽的自然植物/户外风景来契合你的参考图风格 
       */
	   background: url('https://img.ximi.me/user/xqx/max.php?=max_1779038742_6a09fa16ad98d.jpg') center/cover fixed;
    	 	  /*
		 
	 */

      color: var(--text);
    }
	body.room-mode{
   background: url('https://images.unsplash.com/photo-1508615039623-a25605d2b022?ixlib=rb-4.0.3&auto=format&fit=crop&w=2560&q=80') center/cover fixed;

}

    /* === 首页风格 (Hero Screen) === */
    .hero-screen {
      position: fixed;
      inset: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background: rgba(0, 0, 0, 0.25); /* 压暗背景突出文字 */
      backdrop-filter: blur(3px);
      z-index: 100;
      color: white;
      transition: opacity 0.6s ease;
    }
    .hero-header {
      position: absolute;
      top: 0; left: 0; right: 0;
      padding: 24px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .hero-logo { font-family: 'Great Vibes', cursive; font-size: 2rem; }
    .hero-nav { font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase; display: flex; gap: 24px;}
    .hero-sub {
      font-size: 0.9rem;
      letter-spacing: 4px;
      text-transform: uppercase;
      margin-bottom: 10px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .hero-title {
      font-family: 'Great Vibes', cursive;
      font-size: 6rem;
      font-weight: normal;
      margin: 0 0 20px 0;
      text-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .hero-desc {
      font-size: 1.1rem;
      letter-spacing: 1px;
      margin-bottom: 40px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }
    .hero-btn {
      background: transparent;
      border: 1px solid white;
      color: white;
      padding: 12px 36px;
      border-radius: 999px;
      font-size: 0.9rem;
      letter-spacing: 2px;
      cursor: pointer;
      backdrop-filter: blur(5px);
      transition: all 0.3s ease;
    }
    .hero-btn:hover { background: white; color: #333; }

    /* === 聊天室主体 (毛玻璃清爽化) === */
    button, input, textarea { font: inherit; }
    button {
      border: 1px solid var(--line);
      background: rgba(255, 255, 255, 0.7);
      color: var(--text);
      border-radius: 12px;
      min-height: 40px;
      padding: 9px 12px;
      cursor: pointer;
      backdrop-filter: blur(10px);
      transition: background 0.2s;
    }
    button:hover { background: rgba(255, 255, 255, 0.9); }
    button.primary { background: rgba(45, 55, 72, 0.85); border-color: transparent; color: #fff; }
    button.primary:hover { background: rgba(45, 55, 72, 1); }
    button:disabled { opacity: 0.45; cursor: not-allowed; }
    input, textarea {
      border: 1px solid var(--line);
      background: rgba(255, 255, 255, 0.6);
      color: var(--text);
      border-radius: 12px;
      outline: none;
      padding: 10px 11px;
      min-height: 40px;
      backdrop-filter: blur(10px);
    }
    input:focus, textarea:focus { border-color: rgba(255,255,255,0.9); box-shadow: 0 0 0 3px rgba(255,255,255,0.2); background: rgba(255,255,255,0.8); }
    textarea { resize: none; }
    
    .app {
      min-height: 100%;
      display: grid;
      place-items: center;
      padding: 18px;
    }
    .chatbox {
      width: min(680px, 100%);
      height: min(820px, calc(100vh - 36px));
      background: var(--card-glass);
      backdrop-filter: blur(24px);
      -webkit-backdrop-filter: blur(24px);
      border: 1px solid var(--card-border);
      border-radius: 22px;
      box-shadow: 0 24px 80px rgba(0,0,0,0.15);
      display: grid;
      grid-template-rows: auto 1fr auto;
      overflow: hidden;
    }
    
    /* 顶部Header */
    .top {
      display: flex; align-items: center; justify-content: space-between; gap: 12px;
      padding: 14px 16px;
      border-bottom: 1px solid var(--line);
      background: rgba(255, 255, 255, 0.3);
    }
    .title { font-weight: 800;    padding-bottom: 4px; }
    .subtitle { font-size: 12px; color: var(--muted); margin-top: 2px; }
    .status {
      display: inline-flex; align-items: center; gap: 7px;
      border: 1px solid var(--line); border-radius: 999px;
      padding: 7px 10px; color: var(--text); font-size: 13px;
      background: rgba(255,255,255,0.5);
    }
    .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--bad); }
    .dot.ok { background: var(--ok); }
    .dot.warn { background: #eab308; }

    /* 消息区 */
    .messages { overflow: auto; padding: 14px; background: transparent; }
    .msg {
      max-width: 82%; margin: 8px 0; padding: 10px 12px;
      border-radius: 16px; white-space: pre-wrap; overflow-wrap: anywhere;
      word-break: break-word; line-height: 1.45;
      box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .msg.long { max-width: 100%; }
    .msg-text { white-space: pre-wrap; }
    
    .copy-msg { display: none; margin-top: 7px; min-height: 28px; padding: 4px 9px; border-radius: 999px; font-size: 12px; }
    .msg.show-copy .copy-msg { display: inline-flex; align-items: center; }
    
    /* 气泡样式重构 */
    .msg.me {
      margin-left: auto;
      background: rgba(198, 246, 213, 0.85); /* 清爽淡绿 */
      backdrop-filter: blur(10px);
      color: #1c4532;
      border: 1px solid rgba(255,255,255,0.6);
      border-bottom-right-radius: 5px;
    }
    .msg.peer {
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.6);
      border-bottom-left-radius: 5px;
    }
    .msg.sys { max-width: 100%; text-align: center; color: var(--muted); font-size: 12px; background: transparent; box-shadow: none; padding: 4px; }
    .msg.card { max-width: 100%; background: rgb(185 204 210 / 47%); backdrop-filter: blur(12px); border: 1px solid var(--line); }
    .msg img { max-width: 100%; display: block; border-radius: 12px; margin-top: 7px; }

    /* 连接卡片 */
    .connect-card { display: grid; gap: 9px; }
    .row { display: flex; gap: 8px; align-items: center; }
    .grow { flex: 1; min-width: 0; }
    .mono { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; }
    .hint { color: var(--muted); font-size: 13px; line-height: 1.5; }
    .qrrow { display: grid; grid-template-columns: 106px 1fr; gap: 12px; align-items: center; }
    .qrbox { width: 100px; height: 100px; background: rgba(255,255,255,0.8); border: 1px solid var(--line); border-radius: 12px; display: grid; place-items: center; padding: 7px; }
    .qrbox img, .qrbox canvas { max-width: 86px; max-height: 86px; }

    /* 底部输入区 */
    .composer {
      border-top: 1px solid var(--line);
      background: rgba(255, 255, 255, 0.4);
      padding: 11px;
      display: grid; grid-template-columns: auto 1fr auto; gap: 8px; align-items: end;
    }
    .composer textarea { width: 100%; height: 42px; max-height: 120px; background: rgba(255,255,255,0.7); }
    .iconbtn { width: 42px; padding: 0; font-size: 20px; background: transparent; border: none; }
    
    .drawer { display: none; border-top: 1px solid var(--line); background: rgba(255, 255, 255, 0.3); padding: 10px 11px; }
    .drawer.open { display: block; }
    .drawer-grid { display: grid; grid-template-columns: 1fr; gap: 8px; }
    .drawer label.action {
      display: grid; place-items: center; text-align: center; min-height: 58px;
      border: 1px dashed var(--muted); border-radius: 12px;
      background: rgba(255,255,255,0.5); font-size: 13px; cursor: pointer;
    }
    .drawer label.action:hover { background: rgba(255,255,255,0.8); }
    .drawer input[type=file] { display: none; }
    
    .filecard { border: 1px solid var(--line); border-radius: 12px; padding: 9px; margin-top: 4px; background: rgba(255,255,255,0.65); }
    .filetop { display: flex; justify-content: space-between; gap: 8px; align-items: center; }
    .small { font-size: 12px; color: var(--muted); }
    .me .small { color: #2f5f33; }
    .barwrap { height: 7px; border-radius: 99px; overflow: hidden; background: rgba(255,255,255,0.5); margin-top: 7px; }
    .bar { height: 100%; width: 0; background: rgba(45,55,72,0.8); transition: width 0.2s; }
    .me .bar { background: #2f855a; }
    .download { color: var(--blue); text-decoration: none; display: inline-block; margin-top: 6px; font-size: 14px; }
    
    .footbar { border-top: 1px solid var(--line); padding: 8px 12px; text-align: center; color: var(--muted); font-size: 12px; background: rgba(255,255,255,0.3); }
    .footbar a { color: var(--text); text-decoration: none; margin: 0 5px; font-weight: 500; }
@media(max-width: 720px) {
      .app { padding: 0; }
      .chatbox { width: 100%; height: 100%; border: 0; border-radius: 0; }
      .top { padding-top: calc(12px + env(safe-area-inset-top)); }
      .hero-title { font-size: 4rem; }
      .hero-header { padding: 20px; }
      .hero-nav { display: none; }
      .drawer-grid { grid-template-columns: repeat(2, 1fr); }
      .qrrow { grid-template-columns: 92px 1fr; }
      .qrbox { width: 88px; height: 88px; }
      .qrbox img, .qrbox canvas { max-width: 74px; max-height: 74px; }
      .composer { padding-bottom: calc(10px + env(safe-area-inset-bottom)); }
    } /* <-- 注意：移动端媒体查询的括号在这里闭合！ */

    /* ================= 以下是移出媒体查询的全局样式 ================= */

    /* 二维码弹窗遮罩 (全局生效) */
    .qr-overlay {
      position: fixed; top: 0; left: 0; width: 100%; height: 100%;
      background: rgba(0,0,0,0.5); backdrop-filter: blur(6px);
      display: none; align-items: center; justify-content: center; z-index: 9999;
    }
    
    /* 二维码卡片 */
    .qr-modal {
      background: #fff; padding: 24px; border-radius: 20px; text-align: center;
      box-shadow: 0 20px 40px rgba(0,0,0,0.2); animation: fadeIn 0.3s;max-width: 275px;
    }
    .qr-modal img { width: 220px; height: 220px; display: block; margin-bottom: 15px; border-radius: 8px; }
    @keyframes fadeIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

    /* 增强底部三个操作按钮的对比度 */
    button.action-btn {
    background: rgba(255, 255, 255, 0.7);
    color: var(--text);
    border-radius: 12px;
      font-weight: 500;
      box-shadow: 0 2px 6px rgba(0,0,0,0.06);
    }
    button.action-btn:hover {
    background: rgba(255, 255, 255, 0.9);
    }

/*中英文切换*/

.lang-switch {
  margin-left:12px;
  display:inline-flex;
  gap:8px;
}

.lang-switch button {
  border:0;
  background:none;
  cursor:pointer;
  font-size:13px;
  opacity:.75;
}

.lang-switch button:hover {
  opacity:1;
}
.title-link{
    color:inherit;
    text-decoration:none;
}

.title-link:hover,
.title-link:visited,
.title-link:active{
    color:inherit;
    text-decoration:none;
}
  </style>
</head>
<body>

<div id="homeScreen" class="hero-screen">
	<header class="hero-header">
		<div class="hero-logo">S & D</div>
		<div class="hero-nav">
			<span data-i18n="private">Private</span>
			<span data-i18n="secure">Secure</span>
			<span data-i18n="p2p">P2P</span>
		</div>
	</header>
	<div class="hero-sub" data-i18n="heroSub"> Secure & Fast P2P Transfer </div>
	<h1 class="hero-title"> AeroDrop </h1>
	<div class="hero-desc" data-i18n="heroDesc"> A private peer-to-peer file, image, and text transfer tool. </div>
	<button id="quickStartBtn" class="hero-btn" data-i18n="createRoom"> CREATE ROOM </button>
</div>
<div id="roomScreen" class="app" style="display:none;">
	<main class="chatbox glass">
		<header class="top">
			<div>
				 <a href="./" class="title-link"><div class="title" data-i18n="title">文件传输助手</div></a>
				<div class="subtitle" data-i18n="subtitle"> AeroDrop · 简单、私密的点对点传输 </div>
			</div>
			<div class="status">
				<span id="statusDot" class="dot"></span>
				<span id="statusText" data-i18n="offline"> 未连接 </span>
			</div>
		</header>
		<section id="messages" class="messages" aria-live="polite"></section>
		<section>
			<div id="drawer" class="drawer">
				<div class="drawer-grid">
					<label class="action">
						<input id="fileInput" type="file" multiple disabled>
						<span>
							<span data-i18n="selectFile"> 选择文件 </span>
							<br>
							<small class="small" data-i18n="autoSend"> 选择后自动发送 </small>
						</span>
					</label>
				</div>
				<div class="hint" style="margin-top:8px;text-align:center;" data-i18n="transferHint"> 默认使用均衡传输。大文件传输时请保持页面打开。 </div>
			</div>
			<div class="composer">
				<button id="moreBtn" class="iconbtn" title="更多" data-i18n-title="more"> ＋ </button>
				<textarea id="textInput" placeholder=t("inputPlaceholder") data-i18n-placeholder="inputPlaceholder" disabled></textarea>
				<button id="sendTextBtn" class="primary" disabled data-i18n="send"> 发送 </button>
			</div>
<footer class="footbar"> © 2026 <a href="https://github.com/xm-nas/AeroDrop" class="title-link">「 ximi 」</a> All Rights Reserved <span class="lang-switch">
					<button id="zhBtn"> 中文 </button>
					<button id="enBtn"> English </button>
				</span>
			</footer>

		</section>
	</main>
</div>
<div id="qrOverlay" class="qr-overlay" onclick="this.style.display='none'">
	<div class="qr-modal" onclick="event.stopPropagation()">
		<img id="qrImg" src="" alt="二维码">
		<div style="color:#666;font-size:14px;" data-i18n="scanQr"> 扫码即可在另一台设备访问此房间 </div>
	</div>
</div>

<script>
	(() => {
	  const $ = id => document.getElementById(id);
	  const els = ['statusDot','statusText','messages','drawer','moreBtn','textInput','sendTextBtn','fileInput'].reduce((a,id)=>(a[id]=$(id),a),{});
	 
	let currentLang=localStorage.getItem("aerodrop_language")||"zh";
	
	const I18N={
zh:{
transferHint:"默认使用均衡传输。大文件传输时请保持页面打开。",
selectFile:"选择文件",
autoSend:"选择后自动发送",
title:"文件传输助手",
subtitle:"AeroDrop · 简单、私密的点对点传输",
connectDevice:"连接设备",
connectHint:"输入同一个接头暗号，或分享二维码。",
random:"随机",
join:"进入房间",
disconnect:"断开",
roomAddress:"房间地址",
copy:"复制地址",
share:"分享",
sendChat:"发送到对话",

ready:"文件传输助手 AeroDrop 已就绪",
waitingRoom:"已进入房间：{room}，等待另一台设备",

copySuccess:"已复制文本",
copyFail:"复制失败，请长按选择文本",
addressCopied:"房间地址已复制",
address:"房间地址：{url}",

send:"发送",
download:"下载",

disconnected:"已断开",
waiting:"等待对方",
signaling:"连接信令中",
loading:"加载中",

p2pConnected:"P2P 已连接",
connectSuccess:"连接成功",
connected:"已连接",
connect:"连接",

requestingConnection:"正在请求连接",
dataChannelClosed:"数据通道已关闭",

inputPlaceholder:"连接后输入消息",

qrError:"无法显示二维码：未找到弹窗结构",

roomFull:"房间已满",
roomFullDetail:"房间已满：当前只支持两台设备",

connecting:"正在建立连接",
peerFound:"发现另一台设备，正在连接",

peerJoined:"对方已加入",
peerLeft:"对方离开",
peerLeftWait:"对方已离开，可等待对方重新进入或自己重新进入房间",

receivingStart:"开始接收",
missingFileInfo:"收到分片但缺少文件信息",
receiveFailed:"接收失败",

disconnected2:"已断开连接",

scanQr:"扫码即可在另一台设备访问此房间"
},

en:{
transferHint:"Balanced transfer is used by default. Keep the page open during large file transfers.",
selectFile:"Select File",
autoSend:"Automatically send after selection",
title:"File Transfer Assistant",
subtitle:"AeroDrop · Simple and private peer-to-peer transfer",
connectDevice:"Connect Device",
connectHint:"Enter the same room code or share the QR code.",
random:"Random",
join:"Join Room",
disconnect:"Disconnect",
roomAddress:"Room Address",
copy:"Copy Address",
share:"Share",
sendChat:"Send to Chat",

ready:"File Transfer Assistant AeroDrop is ready",
waitingRoom:"Joined room: {room}, waiting for another device",

copySuccess:"Text copied",
copyFail:"Copy failed",
addressCopied:"Room address copied",
address:"Room address: {url}",

send:"Send",
download:"Download",

disconnected:"Disconnected",
waiting:"Waiting for peer",
signaling:"Connecting to signaling server",
loading:"Loading",

p2pConnected:"P2P Connected",
connectSuccess:"Connection successful",
connected:"Connected",
connect:"Connect",

requestingConnection:"Requesting connection",
dataChannelClosed:"Data channel closed",

inputPlaceholder:"Type message after connection",

qrError:"Unable to display QR code: popup structure not found",

roomFull:"Room is full",
roomFullDetail:"Room is full: only two devices are supported",

connecting:"Establishing connection",
peerFound:"Another device found, connecting",

peerJoined:"Peer joined",
peerLeft:"Peer left",
peerLeftWait:"Peer left. Wait for them to rejoin or enter the room again.",

receivingStart:"Start receiving",
missingFileInfo:"Received chunk but missing file information",
receiveFailed:"Receive failed",

disconnected2:"Connection closed",

scanQr:"Scan the QR code to join this room from another device."

}
};
	
	function t(k,obj={}){
	let s=I18N[currentLang][k]||k;
	Object.keys(obj).forEach(i=>s=s.replace("{"+i+"}",obj[i]));
	return s;
	}
	
	window.setLanguage=function(lang){
	currentLang=lang;
	localStorage.setItem("aerodrop_language",lang);
	
	document.querySelectorAll("[data-i18n]").forEach(e=>{
	let k=e.dataset.i18n;
	if(I18N[lang][k])e.textContent=I18N[lang][k];
	});
	
	document.querySelectorAll("[data-i18n-placeholder]").forEach(e=>{
	let k=e.dataset.i18nPlaceholder;
	if(I18N[lang][k])e.placeholder=I18N[lang][k];
	});
	};
	
	const mode={label:'均衡',chunk:64*1024,buffer:4*1024*1024};
	let ws,pc,dc,room,polite=false,makingOffer=false,ignoreOffer=false,iceServers=[],recv=new Map(),activeTransfers=0,iceTimers=[],currentJoinBtn=null,currentDisconnectBtn=null;
	
	   const sleep=ms=>new Promise(r=>setTimeout(r,ms));
	  const safeRoom=s=>String(s||'').replace(/[^a-zA-Z0-9_-]/g,'').slice(0,64);
	  const randRoom=()=>Math.random().toString(36).slice(2,8);
	  function roomFromUrl(){const m=location.pathname.match(/\/r\/([a-zA-Z0-9_-]+)/);return m?m[1]:(new URLSearchParams(location.search).get('room')||'')}
	
	  const linkOf=r=>r?`${location.origin}/?room=${encodeURIComponent(r)}`:'';
	  function fmt(n){if(n<1024)return n+' B';if(n<1048576)return(n/1024).toFixed(1)+' KB';if(n<1073741824)return(n/1048576).toFixed(1)+' MB';return(n/1073741824).toFixed(2)+' GB'}
	  function setStatus(t,k=''){els.statusText.textContent=t;els.statusDot.className='dot '+k}
	  function esc(s){return String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}function escAttr(s){return esc(s).replace(/`/g,'&#96;')}
	  function node(cls,html){const d=document.createElement('div');d.className=cls;d.innerHTML=html;els.messages.appendChild(d);els.messages.scrollTop=els.messages.scrollHeight;return d}
	  
	function sys(v){
	if(typeof v==="object"&&v.i18n)return node('msg sys',esc(t(v.i18n,v)));
	return node('msg sys',esc(v));
	}
	
	
	  function copyText(value){if(navigator.clipboard&&navigator.clipboard.writeText)return navigator.clipboard.writeText(value);const ta=document.createElement('textarea');ta.value=value;ta.style.position='fixed';ta.style.opacity='0';document.body.appendChild(ta);ta.focus();ta.select();document.execCommand('copy');ta.remove();return Promise.resolve()}
	  
	// 替换 text()
	
	function text(t1,me){
	const value=String(t1);
	const long=value.length>200||value.includes('\n');
	
	const d=node(
	'msg text-msg '+(me?'me':'peer')+(long?' long':''),
	`<div class="msg-text">${esc(value)}</div><button class="copy-msg" type="button">${t("copy")}</button>`
	);
	
	const btn=d.querySelector('.copy-msg');
	
	d.onclick=e=>{
	if(e.target===btn)return;
	document.querySelectorAll('.msg.show-copy').forEach(x=>{
	if(x!==d)x.classList.remove('show-copy')
	});
	d.classList.toggle('show-copy')
	};
	
	btn.onclick=async e=>{
	e.stopPropagation();
	try{
	await copyText(value);
	sys({i18n:"copySuccess"});
	}catch{
	sys({i18n:"copyFail"});
	}
	};
	
	return d;
	}
	  
	
	function connectCard(){
	    const r=safeRoom(room||roomFromUrl())||randRoom(); room=r;
	    
	const d=node('msg card',` <div class="connect-card"><b data-i18n="connectDevice"> 连接设备 </b><div class="hint" data-i18n="connectHint"> 输入同一个接头暗号，或分享二维码。 </div><div class="row"><input id="roomInput" class="grow mono" value="${escAttr(r)}" autocomplete="off" autocapitalize="none" autocorrect="off" spellcheck="false" inputmode="latin"><button id="randomBtn" data-i18n="random"> 随机 </button></div><div class="row"><button id="joinBtn" class="primary grow" data-i18n="join"> 进入房间 </button><button id="disconnectBtn" disabled data-i18n="disconnect"> 断开 </button></div><div style="margin-top:4px"><b data-i18n="roomAddress"> 房间地址 </b><div id="inviteLink" class="hint mono" style="margin-bottom:8px"></div><div class="row"><button id="copyInviteBtn" class="action-btn grow" data-i18n="copy"> 复制地址 </button><button id="shareBtn" class="action-btn grow" data-i18n="share"> 分享 </button><button id="showInviteBtn" class="action-btn grow" data-i18n="sendChat"> 发送到对话 </button></div></div></div> `);
	
	
	
	
	
	    const roomInput=d.querySelector('#roomInput'), 
	          joinBtn=d.querySelector('#joinBtn'), 
	          randomBtn=d.querySelector('#randomBtn'), 
	          disconnectBtn=d.querySelector('#disconnectBtn'), 
	          inviteLink=d.querySelector('#inviteLink'), 
	          copyInviteBtn=d.querySelector('#copyInviteBtn'), 
	          showInviteBtn=d.querySelector('#showInviteBtn'), 
	          shareBtn=d.querySelector('#shareBtn');
	    
 function update(){

  room=safeRoom(roomInput.value)||randRoom();

  roomInput.value=room;

  const url=linkOf(room);

  inviteLink.textContent=url;


  const qrImg=document.getElementById('qrImg');

  if(qrImg){

    qrImg.src=
    `https://www.ximi.me/themes/book/qcr_logo.php?page_url=${encodeURIComponent(url)}`;

  }

}
	    
	    roomInput.oninput=update; 
 randomBtn.onclick=()=>{roomInput.value=randRoom();update();history.replaceState(null,'',`/?room=${room}`)}; 
	    joinBtn.onclick=()=>join(roomInput.value,joinBtn,disconnectBtn); 
	    disconnectBtn.onclick=disconnect; 
	
	// connectCard 内按钮提示替换
	
	copyInviteBtn.onclick=async()=>{
	update();
	await navigator.clipboard.writeText(linkOf(room));
	sys({i18n:"addressCopied"});
	};
	
	showInviteBtn.onclick=()=>{
	update();
	sys({i18n:"address",url:linkOf(room)});
	};
	

shareBtn.onclick=()=>{
  update();

  const qrOverlay=document.getElementById('qrOverlay');

  if(qrOverlay){
    qrOverlay.style.display='flex';
  }else{
    sys(t("qrError"));
  }
 };
	    

			return {
	showInvite:()=>{
	update();
	sys({i18n:"address",url:linkOf(room)});
	}
	};
	   // update();
	   // return {showInvite:()=>{update();sys('房间地址：'+linkOf(room))}};
	  }
	  


	  function renderQr(el, link) {
	  	el.innerHTML = '';
	  	if (window.QRCode) new QRCode(el, {
	  		text: link,
	  		width: 86,
	  		height: 86,
	  		colorDark: '#2d3748',
	  		colorLight: 'transparent',
	  		correctLevel: QRCode.CorrectLevel.M
	  	});
	  	else {
	  		el.innerHTML = '<span class="small">t("loading")</span>';
	  		setTimeout(() => renderQr(el, link), 500)
	  	}
	  }
	  let cardApi = connectCard();

	  function enableSend(on) {
	  	els.textInput.disabled = !on;
	  	els.sendTextBtn.disabled = !on;
	  	els.fileInput.disabled = !on
	  }

	  function sendSignal(m) {
	  	if (ws && ws.readyState === 1) ws.send(JSON.stringify(m))
	  }

	  function sendData(m) {
	  	if (dc && dc.readyState === 'open') dc.send(JSON.stringify(m))
	  }
	  async function loadIce() {
	  	const r = await fetch('/api/ice', {
	  		cache: 'no-store'
	  	}).catch(() => ({
	  		json: () => ({
	  			iceServers: []
	  		})
	  	}));
	  	const j = await r.json();
	  	iceServers = j.iceServers || []
	  }

	  function candidateKind(c) {
	  	return c && (c.candidateType || c.type || '')
	  }

	  function labelFromStats(stats) {
	  	let pair;
	  	stats.forEach(r => {
	  		if (r.type === 'transport' && r.selectedCandidatePairId) pair = stats.get(r.selectedCandidatePairId);
	  		if (r.type === 'candidate-pair' && (r.selected || r.nominated)) pair = r
	  	});
	  	if (!pair) return null;
	  	const lt = candidateKind(stats.get(pair.localCandidateId));
	  	const rt = candidateKind(stats.get(pair.remoteCandidateId));
	  	if (lt === 'relay' || rt === 'relay') return ['TURN 中继', 'ok', lt, rt];
	  	if (lt || rt) return [t("p2pConnected"), 'ok', lt, rt];
	  	return null
	  }
	  async function detectType(silent = false) {
	  	if (!pc || pc.connectionState !== 'connected') return;
	  	try {
	  		const res = labelFromStats(await pc.getStats());
	  		if (!res) {
	  			setStatus(t("connected"), 'ok');
	  			return
	  		}
	  		setStatus(res[0], res[1]);
	  		//if (!silent) sys(`t("connectSuccess")：${res[0]}（local=${res[2]||'unknown'} / remote=${res[3]||'unknown'}）`)
			if (!silent) sys(`${t("connectSuccess")}：${res[0]}（local=${res[2]||'unknown'} / remote=${res[3]||'unknown'}）`);
	  	} catch {
	  		setStatus(t("connected"), 'ok')
	  	}
	  }

	  function scheduleTypeChecks() {
	  	iceTimers.forEach(clearTimeout);
	  	iceTimers = [];
	  	[500, 2000, 5000].forEach((d, i) => iceTimers.push(setTimeout(() => detectType(i > 0), d)))
	  }
	  async function setupPc() {
	  	pc = new RTCPeerConnection({
	  		iceServers
	  	});
	  	pc.onicecandidate = e => {
	  		if (e.candidate) sendSignal({
	  			type: 'candidate',
	  			candidate: e.candidate
	  		})
	  	};
	  	pc.onconnectionstatechange = () => {
	  		const s = pc.connectionState;
	  		if (s === 'connected') {
	  			enableSend(true);
	  			scheduleTypeChecks()
	  		} else if (['failed', 'disconnected', 'closed'].includes(s)) {
	  			setStatus(t("connect") + s);
	  			enableSend(false)
	  		} else setStatus('连接 ' + s, 'warn')
	  	};
	  	pc.ondatachannel = e => {
	  		dc = e.channel;
	  		bindDc()
	  	}
	  }
	  async function makeOffer() {
	  	if (!pc || pc.signalingState !== 'stable') return;
	  	try {
	  		makingOffer = true;
	  		await pc.setLocalDescription(await pc.createOffer());
	  		sendSignal({
	  			type: 'offer',
	  			description: pc.localDescription
	  		});
	  		sys(t("requestingConnection"))
	  	} finally {
	  		makingOffer = false
	  	}
	  }

	  function createDc() {
	  	dc = pc.createDataChannel('transfer', {
	  		ordered: true
	  	});
	  	bindDc()
	  }

	  function bindDc() {
	  	dc.binaryType = 'arraybuffer';
	  	dc.onopen = () => {
	  		enableSend(true);
	  		scheduleTypeChecks();
	  		sendData({
	  			kind: 'hello'
	  		})
	  	};
	  	dc.onclose = () => {
	  		enableSend(false);
	  		sys(t("dataChannelClosed"))
	  	};
	  	dc.onmessage = e => handleData(e.data)
	  }

	  // join() 替换

	  async function join(inputRoom, joinBtn, disconnectBtn) {
	  	resetPeer(true, false);
	  	currentJoinBtn = joinBtn;
	  	currentDisconnectBtn = disconnectBtn;

	  	room = safeRoom(inputRoom) || randRoom();

	  	if (window.history.replaceState)
	  		history.replaceState(null, '', `/?room=${room}`);

	  	setStatus(t("signaling"), 'warn');

	  	await loadIce();

	  	ws = new WebSocket(
	  		(location.protocol === 'https:' ? 'wss' : 'ws') +
	  		'://' + location.host + '/ws'
	  	);

	  	ws.onopen = () => {
	  		sendSignal({
	  			type: 'join',
	  			room
	  		});
	  		setStatus(t("waiting"), 'warn');

	  		if (joinBtn) joinBtn.disabled = true;
	  		if (disconnectBtn) disconnectBtn.disabled = false;

	  		sys({
	  			i18n: "waitingRoom",
	  			room: room
	  		});
	  	};

	  	ws.onmessage = e => handleSignal(JSON.parse(e.data));

	  	ws.onclose = () => {
	  		setStatus(t("disconnected"));
	  		resetPeer(false, false);

	  		if (joinBtn) joinBtn.disabled = false;
	  		if (disconnectBtn) disconnectBtn.disabled = true;
	  	};

	  	ws.onerror = () => setStatus('Signaling error');
	  }


	  async function handleSignal(msg) {
	  	if (msg.type === 'full') {
	  		setStatus(t("roomFull"));
	  		sys(t("roomFullDetail"));
	  		return
	  	}
	  	if (msg.type === 'joined') {
	  		polite = !!msg.polite;
	  		if (!pc) await setupPc();
	  		if ((msg.peers || []).length) {
	  			setStatus(t("connecting"), 'warn');
	  			sys(t("peerFound"))
	  		}
	  		return
	  	}
	  	if (msg.type === 'peer-joined') {
	  		resetPeer(false, false);
	  		setStatus(t("connecting"), 'warn');
	  		sys(t("peerJoined"));
	  		await setupPc();
	  		createDc();
	  		await makeOffer();
	  		return
	  	}
	  	if (msg.type === 'peer-left') {
	  		resetPeer(false, false);
	  		setStatus(t("peerLeft"));
	  		sys(t("peerLeftWait"));
	  		return
	  	}
	  	if (!pc) await setupPc();
	  	if (msg.type === 'offer' || msg.type === 'answer') {
	  		const desc = msg.description;
	  		const collide = desc.type === 'offer' && (makingOffer || pc.signalingState !== 'stable');
	  		ignoreOffer = !polite && collide;
	  		if (ignoreOffer) return;
	  		await pc.setRemoteDescription(desc);
	  		if (desc.type === 'offer') {
	  			await pc.setLocalDescription();
	  			sendSignal({
	  				type: 'answer',
	  				description: pc.localDescription
	  			})
	  		}
	  	} else if (msg.type === 'candidate') {
	  		try {
	  			await pc.addIceCandidate(msg.candidate)
	  		} catch (e) {
	  			if (!ignoreOffer) console.warn(e)
	  		}
	  	}
	  }
	  async function handleData(data) {
	  	try {
	  		if (typeof data === 'string') {
	  			let m;
	  			try {
	  				m = JSON.parse(data)
	  			} catch {
	  				return
	  			}
	  			if (m.kind === 'text') text(m.text, false);
	  			if (m.kind === 'file-meta') {
	  				recv.set(m.id, {
	  					meta: m,
	  					chunks: [],
	  					received: 0,
	  					el: fileBubble(m.id, m.name, m.size, false)
	  				});
	  				sys(`${t("receivingStart")}：${m.name} (${fmt(m.size)})`)
	  			}
	  			if (m.kind === 'file-end') finishFile(m.id);
	  			return
	  		}
	  		if (data instanceof Blob) data = await data.arrayBuffer();
	  		if (data && data.buffer instanceof ArrayBuffer && !(data instanceof ArrayBuffer)) data = data.buffer.slice(data.byteOffset, data.byteOffset + data.byteLength);
	  		const idLen = new DataView(data, 0, 2).getUint16(0);
	  		const id = new TextDecoder().decode(data.slice(2, 2 + idLen));
	  		const chunk = data.slice(2 + idLen);
	  		const r = recv.get(id);
	  		if (!r) {
	  			sys(t("missingFileInfo"));
	  			return
	  		}
	  		r.chunks.push(chunk);
	  		r.received += chunk.byteLength;
	  		updateBubble(r.el, r.received, r.meta.size)
	  	} catch (e) {
	  		sys(`${t("receiveFailed")}：${e.message || e}`);
	  	}
	  }

	  function fileBubble(id, name, size, me) {
	  	const d = node('msg ' + (me ? 'me' : 'peer'), `<div class="filecard" id="f-${id}"><div class="filetop"><b>${esc(name)}</b><span class="small">0 / ${fmt(size)}</span></div><div class="barwrap"><div class="bar"></div></div></div>`);
	  	return d.querySelector('.filecard')
	  }

	  function updateBubble(el, done, total) {
	  	if (!el) return;
	  	el.querySelector('.small').textContent = `${fmt(done)} / ${fmt(total)}`;
	  	el.querySelector('.bar').style.width = Math.min(100, done / total * 100) + '%'
	  }

	  function finishFile(id) {
	  	const r = recv.get(id);
	  	if (!r) return;
	  	const blob = new Blob(r.chunks, {
	  		type: r.meta.type || 'application/octet-stream'
	  	});
	  	const url = URL.createObjectURL(blob);
	  	updateBubble(r.el, r.meta.size, r.meta.size);
	  	const host = r.el.closest('.msg');
	  	if ((r.meta.type || '').startsWith('image/')) {
	  		const img = document.createElement('img');
	  		img.src = url;
	  		host.appendChild(img)
	  	}
	  	const a = document.createElement('a');
	  	a.className = 'download';
	  	a.download = r.meta.name;
	  	a.href = url;
	  	a.textContent = t("download") + r.meta.name;
	  	host.appendChild(a);
	  	recv.delete(id)
	  }
	  async function sendFiles() {
	  	if (!els.fileInput.files.length) return;
	  	activeTransfers++;
	  	els.fileInput.disabled = true;
	  	try {
	  		for (const f of els.fileInput.files) await sendOneFile(f)
	  	} finally {
	  		activeTransfers--;
	  		els.fileInput.disabled = false;
	  		els.fileInput.value = '';
	  		els.drawer.classList.remove('open')
	  	}
	  }
	  async function sendOneFile(file) {
	  	const m = mode;
	  	const id = (crypto.randomUUID && crypto.randomUUID()) || `${Date.now()}-${Math.random().toString(36).slice(2)}`;
	  	sendData({
	  		kind: 'file-meta',
	  		id,
	  		name: file.name,
	  		size: file.size,
	  		type: file.type,
	  		lastModified: file.lastModified,
	  		mode: m.label,
	  		chunk: m.chunk
	  	});
	  	const el = fileBubble(id, file.name, file.size, true);
	  	let off = 0;
	  	while (off < file.size) {
	  		while (dc.bufferedAmount > m.buffer) await sleep(40);
	  		const buf = await file.slice(off, off + m.chunk).arrayBuffer();
	  		const idb = new TextEncoder().encode(id);
	  		const out = new Uint8Array(2 + idb.length + buf.byteLength);
	  		new DataView(out.buffer).setUint16(0, idb.length);
	  		out.set(idb, 2);
	  		out.set(new Uint8Array(buf), 2 + idb.length);
	  		dc.send(out.buffer);
	  		off += buf.byteLength;
	  		updateBubble(el, off, file.size)
	  	}
	  	sendData({
	  		kind: 'file-end',
	  		id
	  	})
	  }

	  function resetPeer(closeWs = false, sendBye = false) {
	  	iceTimers.forEach(clearTimeout);
	  	iceTimers = [];
	  	if (sendBye) try {
	  		sendSignal({
	  			type: 'bye'
	  		})
	  	} catch {}
	  	try {
	  		dc && dc.close()
	  	} catch {}
	  	try {
	  		pc && pc.close()
	  	} catch {}
	  	if (closeWs) {
	  		try {
	  			ws && ws.close()
	  		} catch {}
	  		ws = null
	  	}
	  	pc = null;
	  	dc = null;
	  	polite = false;
	  	makingOffer = false;
	  	ignoreOffer = false;
	  	recv.clear();
	  	activeTransfers = 0;
	  	enableSend(false)
	  }

	  function disconnect() {
	  	resetPeer(true, true);
	  	if (currentJoinBtn) currentJoinBtn.disabled = false;
	  	if (currentDisconnectBtn) currentDisconnectBtn.disabled = true;
	  	setStatus(t("disconnected"));
	  	sys(t("disconnected2"))
	  }

	  els.moreBtn.onclick = () => els.drawer.classList.toggle('open');
	  els.sendTextBtn.onclick = () => {
	  	const t = els.textInput.value.trim();
	  	if (!t) return;
	  	sendData({
	  		kind: 'text',
	  		text: t
	  	});
	  	text(t, true);
	  	els.textInput.value = ''
	  };
	  els.textInput.onkeydown = e => {
	  	if (e.key === 'Enter' && !e.shiftKey) {
	  		e.preventDefault();
	  		els.sendTextBtn.click()
	  	}
	  };
	  els.fileInput.onchange = sendFiles;
	  // ==========================================
	  // 新增：首页与聊天室的路由及交互逻辑
	  // ==========================================
const homeScreen = document.getElementById('homeScreen');
const roomScreen = document.getElementById('roomScreen');
const quickStartBtn = document.getElementById('quickStartBtn');

function enterRoomView() {

  homeScreen.style.opacity = '0';

  setTimeout(() => {

    homeScreen.style.display = 'none';
    roomScreen.style.display = 'grid';

    // 切换房间背景
    document.body.classList.add('room-mode');

  }, 400);
}


// 1. 初始化判断：如果 URL 中带了房间号，直接隐藏首页并触发加入逻辑

if (roomFromUrl()) {

  homeScreen.style.display = 'none';
  roomScreen.style.display = 'grid';

  // URL直接进入房间时，也需要切换背景
  document.body.classList.add('room-mode');


  // 延迟一点等待 DOM 和 cardApi 渲染完毕
  setTimeout(() => {

    const jBtn = document.getElementById('joinBtn');

    if (jBtn) {
      jBtn.click();
    }

  }, 100);

}
	
	  // 2. 首页“新建并进入”按钮逻辑
	  quickStartBtn.onclick = () => {
	    enterRoomView();
	    setTimeout(() => {
	      // 找到卡片内的随机按钮和进入按钮
	      const rBtn = document.getElementById('randomBtn');
	      const jBtn = document.getElementById('joinBtn');
	      
	      if (rBtn) rBtn.click(); // 生成随机房间号并更新 UI/URL
	      if (jBtn) jBtn.click(); // 触发 WebSocket 和 WebRTC 连接
	    }, 450); 
	  };
	
	sys({i18n:"ready"});
	})();
</script>
<script>
	document.getElementById("zhBtn").onclick=()=>{
	setLanguage("zh");
	};
	
	document.getElementById("enBtn").onclick=()=>{
	setLanguage("en");
	};
	
	setLanguage(
	localStorage.getItem("aerodrop_language")||"zh"
	);
	
</script>

</body>
</html>
