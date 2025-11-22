<?php
session_start();

// Ambil data user untuk ditampilkan di chat
 $username = $_SESSION['username'] ?? 'Guest';
 $profilePic = $_SESSION['profile_pic'] ?? 'default.png';
 $profilePicPath = 'uploads/profiles/' . $profilePic;
 $userRole = $_SESSION['role'] ?? 'user'; // Ambil role pengguna
 $isAdmin = in_array($userRole, ['admin', 'developer', 'dev_master']); // Cek apakah admin
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Chatroom - DF Scenery</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* === GAYA GLOBAL === */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* Variabel Warna untuk Tema Terang */
:root {
  --tg-bg-color: #f4f4f4;
  --tg-window-bg: #ffffff;
  --tg-own-bg: #3390ec;
  --tg-own-text: #ffffff;
  --tg-reply-color: #6a7a8c;
  --tg-reply-line: #e1e4e8;
  --tg-reply-line-active: #3390ec;
  --tg-chat-bg: #f7f7f7;
  --tg-chat-text: #222222;
  --tg-chat-date: #8a8a8a;
  --tg-header-bg: #f7f7f7;
  --tg-input-bg: #ffffff;
  --tg-input-border: #e1e4e8;
  --tg-icon-color: #8a8a8a;
  --tg-online-color: #4eba5e;
  --tg-unread-bg: #3390ec;
  --tg-unread-text: #ffffff;
  --tg-ai-bg: #e8f4fd;
  --tg-ai-border: #c2e0ff;
  --tg-ai-text: #222222;
  --tg-admin-color: #e242424;
  --tg-developer-color: #f59e0b;
  --tg-dev-master-color: #8b5cf6;
}

/* Variabel Warna untuk Tema Gelap */
body.dark-mode {
  --tg-bg-color: #18222d;
  --tg-window-bg: #1c2a38;
  --tg-own-bg: #2b5278;
  --tg-own-text: #ffffff;
  --tg-reply-color: #6a7a8c;
  --tg-reply-line: #2b5278;
  --tg-reply-line-active: #3390ec;
  --tg-chat-bg: #18222d;
  --tg-chat-text: #e4e6eb;
  --tg-chat-date: #8a8a8a;
  --tg-header-bg: #1c2a38;
  --tg-input-bg: #1f2d3a;
  --tg-input-border: #3b4a58;
  --tg-icon-color: #8a8a8a;
  --tg-online-color: #4eba5e;
  --tg-unread-bg: #3390ec;
  --tg-unread-text: #ffffff;
  --tg-ai-bg: #1f2d3a;
  --tg-ai-border: #3b4a58;
  --tg-ai-text: #e4e6eb;
}

body {
  font-family: 'Poppins', sans-serif;
  background: var(--tg-bg-color);
  color: var(--tg-chat-text);
  height: 100vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  transition: background 0.3s ease, color 0.3s ease;
}

/* === HEADER CHATROOM === */
.chat-header {
  background: var(--tg-header-bg);
  padding: 10px 16px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid var(--tg-input-border);
  z-index: 10;
  flex-shrink: 0;
  min-height: 60px;
}

.chat-header h1 {
  font-size: 1.2rem;
  font-weight: 500;
  color: var(--tg-chat-text);
}

.chat-status {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.8rem;
  color: var(--tg-chat-date);
}

.status-dot {
  width: 10px;
  height: 10px;
  background-color: var(--tg-online-color);
  border-radius: 50%;
}

.back-btn {
  background: none;
  color: var(--tg-icon-color);
  border: none;
  padding: 8px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s ease;
}

.back-btn:hover {
  background: var(--tg-reply-line);
}

.header-actions {
  display: flex;
  gap: 8px;
}

.header-btn {
  background: none;
  color: var(--tg-icon-color);
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s ease;
}

.header-btn:hover {
  background: var(--tg-reply-line);
}

/* === KONTAINER CHAT UTAMA === */
.chat-main {
  flex-grow: 1;
  overflow-y: auto;
  padding: 16px;
  display: flex;
  flex-direction: column;
  gap: 8px;
  background: var(--tg-chat-bg);
  -webkit-overflow-scrolling: touch;
  height: calc(100vh - 130px); /* Adjusted for new header/footer height */
  min-height: 0;
}

/* Pesan awal (sistem) */
.system-message {
  text-align: center;
  font-size: 0.8rem;
  color: var(--tg-chat-date);
  margin: 10px 0;
  padding: 6px 12px;
  background: var(--tg-window-bg);
  border-radius: 12px;
  align-self: center;
  max-width: 80%;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
}

.date-separator {
  text-align: center;
  margin: 16px 0;
  position: relative;
}

.date-separator span {
  background: var(--tg-chat-bg);
  padding: 4px 12px;
  border-radius: 12px;
  font-size: 0.75rem;
  color: var(--tg-chat-date);
  position: relative;
  z-index: 1;
}

.date-separator::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 0;
  right: 0;
  height: 1px;
  background: var(--tg-reply-line);
  z-index: 0;
}

/* === GAYA PESAN CHAT === */
.chat-message {
  display: flex;
  margin-bottom: 12px;
  position: relative;
  max-width: 75%;
  animation: fadeIn 0.3s ease;
}

.chat-avatar {
  width: 42px;
  height: 42px;
  border-radius: 50%;
  flex-shrink: 0;
  object-fit: cover;
  cursor: pointer;
  transition: transform 0.2s ease;
}

.chat-avatar:hover {
  transform: scale(1.05);
}

.chat-content {
  display: flex;
  flex-direction: column;
  min-width: 0; /* Allows flex item to shrink */
}

.chat-username {
  font-size: 0.85rem;
  font-weight: 500;
  margin-bottom: 2px;
  color: var(--tg-chat-date);
  padding-left: 12px;
  display: flex;
  align-items: center;
  gap: 5px;
}

.user-role {
  font-size: 0.65rem;
  padding: 2px 6px;
  border-radius: 10px;
  font-weight: 500;
  color: white;
}

.role-admin {
  background-color: var(--tg-admin-color);
}

.role-developer {
  background-color: var(--tg-developer-color);
}

.role-dev-master {
  background-color: var(--tg-dev-master-color);
}

.chat-bubble {
  padding: 10px 14px;
  border-radius: 18px;
  position: relative;
  word-wrap: break-word;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
}

.chat-text {
  font-size: 0.95rem;
  line-height: 1.45;
  color: var(--tg-chat-text);
  white-space: pre-wrap; /* Important for newlines */
}

.chat-timestamp {
  font-size: 0.75rem;
  color: var(--tg-chat-date);
  margin-top: 4px;
  padding: 0 12px;
}

/* Pesan di sebelah kanan (pengguna saat ini) */
.chat-message.right {
  align-self: flex-end;
  flex-direction: row-reverse;
}

.chat-message.right .chat-avatar {
  margin-left: 8px;
  margin-right: 0;
}

.chat-message.right .chat-bubble {
  background: var(--tg-own-bg);
  color: var(--tg-own-text);
  border-bottom-right-radius: 4px;
}

.chat-message.right .chat-timestamp {
  text-align: right;
}

/* Pesan di sebelah kiri (pengguna lain) */
.chat-message.left {
  align-self: flex-start;
}

.chat-message.left .chat-avatar {
  margin-right: 8px;
}

.chat-message.left .chat-bubble {
  background: var(--tg-window-bg);
  color: var(--tg-chat-text);
  border-bottom-left-radius: 4px;
}

/* === GAYA PESAN AI === */
.chat-message.ai {
  align-self: center;
  flex-direction: column;
  align-items: center;
  max-width: 90%;
  background: var(--tg-ai-bg);
  border: 1px solid var(--tg-ai-border);
  border-radius: 18px;
  padding: 12px 16px;
  margin: 15px 0;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
  position: relative;
  z-index: 1;
}

.chat-message.ai .chat-avatar {
  width: 40px;
  height: 40px;
  border: 2px solid var(--tg-own-bg);
  box-shadow: 0 0 10px rgba(51, 144, 236, 0.2);
  margin-bottom: 8px;
}

.chat-message.ai .chat-content {
  align-items: center;
  width: 100%;
}

.chat-message.ai .chat-username {
  justify-content: center;
  color: var(--tg-own-bg);
  font-size: 0.9rem;
  margin-bottom: 5px;
  font-weight: 600;
}

.chat-message.ai .chat-bubble {
  background: transparent;
  color: var(--tg-ai-text);
  text-align: center;
  box-shadow: none;
  padding: 0;
}

.chat-message.ai .chat-text {
  font-style: normal;
  line-height: 1.5;
}

.chat-message.ai .chat-timestamp {
  text-align: center;
  margin-top: 8px;
  font-size: 0.7rem;
}

/* Role badge untuk AI */
.role-ai-assistant {
  background: var(--tg-own-bg);
  color: white;
}

/* Indikator Vivi sedang berpikir */
.vivi-thinking {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 15px;
  margin: 10px auto;
  max-width: 70%;
  background: var(--tg-ai-bg);
  border: 1px solid var(--tg-ai-border);
  border-radius: 20px;
  align-self: center;
}

.vivi-thinking-avatar {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 2px solid var(--tg-own-bg);
  margin-right: 10px;
  animation: pulse 1.5s infinite;
}

.vivi-thinking-text {
  font-style: italic;
  color: var(--tg-ai-text);
}

.vivi-thinking-dots {
  display: inline-flex;
  margin-left: 5px;
}

.vivi-thinking-dots span {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background-color: var(--tg-own-bg);
  margin: 0 2px;
  animation: thinking 1.4s infinite ease-in-out;
}

.vivi-thinking-dots span:nth-child(1) {
  animation-delay: -0.32s;
}

.vivi-thinking-dots span:nth-child(2) {
  animation-delay: -0.16s;
}

/* Typing indicator */
.typing-indicator {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 12px;
  background: var(--tg-window-bg);
  border-radius: 18px;
  max-width: 200px;
  align-self: flex-start;
  box-shadow: 0 1px 1px rgba(0, 0, 0, 0.05);
}

.typing-indicator .chat-avatar {
  width: 32px;
  height: 32px;
}

.typing-indicator .chat-bubble {
  display: flex;
  align-items: center;
  gap: 4px;
  padding: 6px 10px;
}

.typing-indicator span {
  height: 8px;
  width: 8px;
  background-color: var(--tg-chat-date);
  border-radius: 50%;
  display: inline-block;
  animation: typing 1.4s infinite ease-in-out;
}

.typing-indicator span:nth-child(1) {
  animation-delay: -0.32s;
}

.typing-indicator span:nth-child(2) {
  animation-delay: -0.16s;
}

/* === FOOTER UNTUK INPUT CHAT === */
.chat-input-area {
  background: var(--tg-header-bg);
  padding: 12px 16px;
  border-top: 1px solid var(--tg-input-border);
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.chat-input-area form {
  display: flex;
  width: 100%;
  gap: 8px;
  align-items: center;
}

.chat-input-wrapper {
  position: relative;
  flex-grow: 1;
}

.chat-input {
  width: 100%;
  padding: 10px 45px 10px 16px;
  border-radius: 20px;
  border: 1px solid var(--tg-input-border);
  background: var(--tg-input-bg);
  color: var(--tg-chat-text);
  font-size: 0.95rem;
  font-family: 'Poppins', sans-serif;
  outline: none;
  transition: border-color 0.2s ease;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.chat-input:focus {
  border-color: var(--tg-own-bg);
}

.input-actions {
  position: absolute;
  right: 8px;
  top: 50%;
  transform: translateY(-50%);
  display: flex;
  gap: 4px;
}

.input-action-btn {
  background: none;
  border: none;
  color: var(--tg-icon-color);
  cursor: pointer;
  font-size: 20px;
  width: 32px;
  height: 32px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.input-action-btn:hover {
  background: var(--tg-reply-line);
  color: var(--tg-own-bg);
}

.send-btn {
  background: var(--tg-own-bg);
  color: var(--tg-own-text);
  border: none;
  width: 44px;
  height: 44px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 18px;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.send-btn:hover {
  background: #2a7cd3;
}

.send-btn:active {
  transform: scale(0.95);
}

/* Tombol aksi admin */
.message-actions {
  position: absolute;
  top: -5px;
  right: -5px;
  display: none;
  gap: 4px;
  background: var(--tg-window-bg);
  border-radius: 16px;
  padding: 4px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  z-index: 2;
}

.chat-message:hover .message-actions {
  display: flex;
}

.action-btn {
  background: none;
  color: var(--tg-icon-color);
  border: none;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  cursor: pointer;
  font-size: 14px;
  transition: all 0.2s ease;
  display: flex;
  align-items: center;
  justify-content: center;
}

.action-btn:hover {
  background: var(--tg-reply-line);
}

.action-btn.mute {
  color: var(--tg-developer-color);
}

.action-btn.unmute {
  color: var(--tg-online-color);
}

.action-btn.delete {
  color: var(--tg-admin-color);
}

/* === ANIMASI === */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(8px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes pulse {
  0% {
    box-shadow: 0 0 0 0 rgba(51, 144, 236, 0.4);
  }
  70% {
    box-shadow: 0 0 0 10px rgba(51, 144, 236, 0);
  }
  100% {
    box-shadow: 0 0 0 0 rgba(51, 144, 236, 0);
  }
}

@keyframes thinking {
  0%, 80%, 100% {
    transform: scale(0.8);
    opacity: 0.5;
  }
  40% {
    transform: scale(1);
    opacity: 1;
  }
}

/* === RESPONSIF === */
@media (max-width: 768px) {
  .chat-header {
    padding: 8px 12px;
    min-height: 56px;
  }
  
  .chat-header h1 {
    font-size: 1.1rem;
  }
  
  .chat-main {
    padding: 12px;
    height: calc(100vh - 110px);
  }

  .chat-message {
    max-width: 80%;
  }

  .chat-input-area {
    padding: 8px 12px;
  }
  
  .chat-input {
    padding: 10px 40px 10px 14px;
    font-size: 0.9rem;
  }
  
  .chat-bubble {
    padding: 8px 12px;
  }
  
  .chat-text {
    font-size: 0.9rem;
  }
  
  .message-actions {
    display: flex; /* Selalu tampilkan di mobile untuk admin */
    top: -5px;
    right: -5px;
    padding: 2px;
  }
  
  .action-btn {
    width: 24px;
    height: 24px;
    font-size: 12px;
  }
}
</style>
</head>

<body>

  <!-- HEADER -->
  <header class="chat-header">
    <a href="dfscenery.php" class="back-btn">
      <i class="fas fa-arrow-left"></i>
    </a>
    <div>
      <h1>Chatroom DFScenery</h1>
      <div class="chat-status">
        <span class="status-dot"></span>
        <span>Online</span>
      </div>
    </div>
    <div class="header-actions">
      <button class="header-btn" id="themeToggle" title="Ganti Tema">
        <i class="fas fa-moon" id="themeIcon"></i>
      </button>
    </div>
  </header>

  <!-- KONTAINER CHAT UTAMA -->
  <main class="chat-main" id="chatContainer">
    <!-- Pesan selamat datang akan ditambahkan oleh JavaScript -->
  </main>

  <!-- FOOTER INPUT CHAT -->
  <footer class="chat-input-area" id="inputArea">
    <form id="chatForm">
      <div class="chat-input-wrapper">
        <input type="text" id="messageInput" class="chat-input" placeholder="Ketik pesan Anda..." autocomplete="off" required>
        <div class="input-actions">
          <button type="button" class="input-action-btn" id="attachBtn" title="Lampirkan file">
            <i class="fas fa-paperclip"></i>
          </button>
          <button type="button" class="input-action-btn" id="emojiBtn" title="Tambah emoji">
            <i class="fas fa-smile"></i>
          </button>
        </div>
      </div>
      <button type="submit" class="send-btn" aria-label="Kirim pesan">
        <i class="fas fa-paper-plane"></i>
      </button>
    </form>
  </footer>

<script>
document.addEventListener('DOMContentLoaded', function() {
  
  const chatContainer = document.getElementById('chatContainer');
  const chatForm = document.getElementById('chatForm');
  const messageInput = document.getElementById('messageInput');
  const attachBtn = document.getElementById('attachBtn');
  const emojiBtn = document.getElementById('emojiBtn');
  const themeToggle = document.getElementById('themeToggle');
  const themeIcon = document.getElementById('themeIcon');
  const inputArea = document.getElementById('inputArea');

  // Data pengguna dari PHP
  const currentUser = {
    username: '<?= htmlspecialchars($username) ?>',
    avatar: '<?= htmlspecialchars($profilePicPath) ?>',
    role: '<?= htmlspecialchars($userRole) ?>',
    isAdmin: <?= $isAdmin ? 'true' : 'false' ?>
  };

  let lastMessageId = 0;
  let typingTimeout;
  let isTyping = false;
  let mutedUsers = []; // Daftar pengguna yang di-mute
  let fetchInterval;
  let isSubmitting = false; // Tambahkan flag untuk mencegah pengiriman ganda
  let conversationContext = []; // Konteks percakapan untuk AI
  let viviLearningData = {}; // Data pembelajaran Vivi

  // Data AI Vivi
  const aiData = {
    username: 'Vivi',
    avatar: 'binigua.jpg',
    role: 'AI Assistant'
  };

// Fungsi untuk mendapatkan badge role
function getRoleBadge(role, isAI = false) {
  // Jika ini adalah AI, tampilkan badge AI
  if (isAI) {
    return '<span class="user-role role-ai-assistant">AI</span>';
  }
  
  if (!role || role === 'user') return '';
  
  const roleClass = `role-${role.toLowerCase().replace('_', '-')}`;
  const roleText = role.replace('_', ' ').toUpperCase();
  
  return `<span class="user-role ${roleClass}">${roleText}</span>`;
}

  // Fungsi untuk menambahkan indikator "Vivi sedang berpikir"
  function showViviThinking() {
    // Hapus indikator yang sudah ada
    const existingIndicator = document.querySelector('.vivi-thinking');
    if (existingIndicator) {
      existingIndicator.remove();
    }

    const thinkingDiv = document.createElement('div');
    thinkingDiv.classList.add('vivi-thinking');
    thinkingDiv.innerHTML = `
      <img src="${aiData.avatar}" alt="${aiData.username}" class="vivi-thinking-avatar">
      <div class="vivi-thinking-text">
        Vivi sedang mencari jawaban terbaik...
        <div class="vivi-thinking-dots">
          <span></span>
          <span></span>
          <span></span>
        </div>
      </div>
    `;
    
    chatContainer.appendChild(thinkingDiv);
    scrollToBottom();
  }

  // Fungsi untuk menambahkan satu pesan ke dalam tampilan
  function addMessageToChat(data, isMe = false, isAI = false) {
    // Hapus indikator mengetik jika ada
    const typingIndicator = document.querySelector('.typing-indicator');
    if (typingIndicator) {
      typingIndicator.remove();
    }

    // Hapus indikator "Vivi sedang berpikir" jika ada
    const viviThinking = document.querySelector('.vivi-thinking');
    if (viviThinking) {
      viviThinking.remove();
    }

    const messageDiv = document.createElement('div');
    // Tambahkan class 'ai' jika ini pesan dari AI
    messageDiv.classList.add('chat-message', isMe ? 'right' : (isAI ? 'ai' : 'left'));
    messageDiv.setAttribute('data-id', data.id || Date.now()); // Gunakan timestamp untuk AI
    
    // Update ID pesan terakhir
    if (data.id && data.id > lastMessageId) {
      lastMessageId = data.id;
    }
    
    const displayName = isMe ? 'Anda' : data.username;
    const timestamp = formatTime(new Date());
    const isMuted = mutedUsers.includes(data.username);

    messageDiv.innerHTML = `
      <img src="${data.avatar_url}" alt="${displayName}" class="chat-avatar" onerror="this.src='https://i.pravatar.cc/150?img=0'">
      <div class="chat-content">
        ${!isMe ? `
          <div class="chat-username">
            ${displayName}
             ${getRoleBadge(data.role, isAI)}
            ${isMuted ? '(Dibisukan)' : ''}
          </div>
        ` : ''}
        <div class="chat-bubble">
          <p class="chat-text">${data.message}</p>
        </div>
        <div class="chat-timestamp">${timestamp}</div>
      </div>
      ${currentUser.isAdmin && !isMe && !isAI ? `
        <div class="message-actions">
          <button class="action-btn mute" title="${isMuted ? 'Buka bisukan' : 'Bisukan'}" onclick="toggleMute('${data.username}')">
            <i class="fas fa-${isMuted ? 'volume-up' : 'volume-mute'}"></i>
          </button>
          <button class="action-btn delete" title="Hapus pesan" onclick="deleteMessage(${data.id || Date.now()})">
            <i class="fas fa-trash"></i>
          </button>
        </div>
      ` : ''}
    `;
    
    chatContainer.appendChild(messageDiv);
    scrollToBottom();
    
    // Simpan pesan ke konteks percakapan jika ini adalah pesan AI atau pertanyaan ke AI
    if (isAI || (data.message && data.message.toLowerCase().startsWith('vivi '))) {
      conversationContext.push({
        isAI: isAI,
        message: data.message,
        timestamp: new Date()
      });
      
      // Batasi konteks ke 10 pesan terakhir
      if (conversationContext.length > 10) {
        conversationContext.shift();
      }
    }
  }

  // Fungsi untuk menambahkan pemisah tanggal
  function addDateSeparator() {
    const today = new Date().toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
    
    const dateDiv = document.createElement('div');
    dateDiv.classList.add('date-separator');
    dateDiv.innerHTML = `<span>${today}</span>`;
    
    chatContainer.appendChild(dateDiv);
  }

  // Fungsi untuk menambahkan indikator mengetik
  function showTypingIndicator(username, avatar) {
    // Hapus indikator yang sudah ada
    const existingIndicator = document.querySelector('.typing-indicator');
    if (existingIndicator) {
      existingIndicator.remove();
    }

    const typingDiv = document.createElement('div');
    typingDiv.classList.add('typing-indicator');
    typingDiv.innerHTML = `
      <img src="${avatar}" alt="${username}" class="chat-avatar">
      <div class="chat-bubble">
        <span></span>
        <span></span>
        <span></span>
      </div>
    `;
    
    chatContainer.appendChild(typingDiv);
    scrollToBottom();
  }

  // Fungsi untuk memformat waktu
  function formatTime(date) {
    return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  }

  // Fungsi untuk scroll ke bawah
  function scrollToBottom() {
    chatContainer.scrollTop = chatContainer.scrollHeight;
  }

  // Fungsi untuk mengambil pesan dari server
  async function fetchMessages() {
    try {
      const response = await fetch(`chat_api.php?action=fetch&last_id=${lastMessageId}`);
      const data = await response.json();

      // Pastikan respons sukses dan ada data pesan
      if (data.success && data.data && data.data.messages) {
        data.data.messages.forEach(msg => {
          // Tentukan apakah ini pesan dari 'saya', dari 'Vivi', atau dari user lain
          const isMe = msg.username === currentUser.username;
          const isAI = msg.username === 'Vivi';
          
          addMessageToChat(msg, isMe, isAI);
        });
      }
    } catch (error) {
      console.error('Gagal mengambil pesan:', error);
    }
  }

  // Fungsi untuk memeriksa apakah pesan mengandung link
  function containsLink(text) {
    const urlPattern = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    return urlPattern.test(text);
  }

  // Event listener untuk input pesan (untuk indikator mengetik)
  messageInput.addEventListener('input', function() {
    if (!isTyping) {
      isTyping = true;
      // mengirim status "mengetik" ke server
      // showTypingIndicator('Pengguna lain');
    }

    clearTimeout(typingTimeout);
    typingTimeout = setTimeout(() => {
      isTyping = false;
      // Hapus indikator mengetik
      const typingIndicator = document.querySelector('.typing-indicator');
      if (typingIndicator) {
        typingIndicator.remove();
      }
    }, 1000);
  });

  // Event listener untuk tombol lampirkan
  attachBtn.addEventListener('click', function() {
    // Di aplikasi nyata, ini akan membuka dialog untuk memilih file
    alert('Fitur lampirkan file akan segera tersedia!');
  });

  // Event listener untuk tombol emoji
  emojiBtn.addEventListener('click', function() {
    // Di aplikasi nyata, ini akan membuka pemilih emoji
    alert('Fitur emoji akan segera tersedia!');
  });

  // Event listener untuk tombol tema
  themeToggle.addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');
    const isDarkMode = document.body.classList.contains('dark-mode');
    themeIcon.className = isDarkMode ? 'fas fa-sun' : 'fas fa-moon';
    localStorage.setItem('theme', isDarkMode ? 'dark' : 'light');
  });

    // Event listener untuk mengirim pesan
  chatForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (isSubmitting) return;
    isSubmitting = true;

    const messageText = messageInput.value.trim();
    if (messageText === '') {
      isSubmitting = false;
      return;
    }

    // --- CEK APAKAH INI PERTANYAAN UNTUK AI ---
    if (messageText.toLowerCase().startsWith('vivi ')) {
      const question = messageText.substring(5);
      
      // Tampilkan indikator "Vivi sedang berpikir"
      showViviThinking();

      try {
        const response = await fetch('chat_api.php?action=ask_ai', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ 
            question: question,
            context: JSON.stringify(conversationContext.slice(-5)) // Kirim konteks 5 pesan terakhir
          })
        });

        const result = await response.json();

        // Jika server berhasil menyimpan percakapan, ambil pesan baru
        if (result.success) {
          await fetchMessages();
          
          // Simpan data pembelajaran dari respons AI
          if (result.learning_data) {
            updateViviLearning(result.learning_data);
          }
        } else {
          // Jika server gagal, hapus indikator dan tampilkan pesan error
          const viviThinking = document.querySelector('.vivi-thinking');
          if (viviThinking) {
            viviThinking.remove();
          }
          addMessageToChat({
            username: aiData.username,
            avatar_url: aiData.avatar,
            role: aiData.role,
            message: result.message || 'Maaf, saya sedang mengalami gangguan. Coba tanya lagi nanti ya!'
          }, false, true);
        }

      } catch (error) {
        console.error('Error asking AI:', error);
        // Hapus indikator mengetik jika ada error koneksi
        const viviThinking = document.querySelector('.vivi-thinking');
        if (viviThinking) {
          viviThinking.remove();
        }
        addMessageToChat({
          username: aiData.username,
          avatar_url: aiData.avatar,
          role: aiData.role,
          message: 'Waduh, koneksi saya bermasalah. Coba ulangi lagi.'
        }, false, true);
      }

      messageInput.value = '';
      messageInput.focus();
      isSubmitting = false;
      return; // Hentikan eksekusi lebih lanjut
    }

    // --- LOGIKA PENGIRIMAN PESAN NORMAL ---
    if (containsLink(messageText)) {
      alert('Pesan tidak boleh mengandung link!');
      isSubmitting = false;
      return;
    }

    try {
      const response = await fetch('chat_api.php?action=send', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          username: currentUser.username,
          message: messageText,
          avatar_url: currentUser.avatar
        })
      });

      const result = await response.json();

      if (result.success) {
        messageInput.value = '';
      } else {
        alert(result.message);
      }
    } catch (error) {
      console.error('Error sending message:', error);
      alert('Terjadi kesalahan saat mengirim pesan.');
    }
    
    messageInput.focus();
    isSubmitting = false;
  });

  // --- FUNGSI UNTUK MENGAMBIL PESAN BARU ---
  async function fetchMessages() {
    try {
      const response = await fetch(`chat_api.php?action=fetch&last_id=${lastMessageId}`);
      const data = await response.json();

      // Pastikan respons sukses dan ada data pesan
      if (data.success && data.data && data.data.messages) {
        data.data.messages.forEach(msg => {
          // Tentukan apakah ini pesan dari 'saya', dari 'Vivi', atau dari user lain
          const isMe = msg.username === currentUser.username;
          const isAI = msg.username === 'Vivi';
          
          addMessageToChat(msg, isMe, isAI);
        });
      }
    } catch (error) {
      console.error('Gagal mengambil pesan:', error);
    }
  }

  // Fungsi untuk menghapus pesan (admin only)
  window.deleteMessage = async function(messageId) {
    if (!confirm('Apakah Anda yakin ingin menghapus pesan ini?')) return;

    try {
      const response = await fetch('chat_api.php?action=delete', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ message_id: messageId })
      });

      const result = await response.json();

      if (result.success) {
        // Hapus pesan dari tampilan
        const messageElement = document.querySelector(`.chat-message[data-id="${messageId}"]`);
        if (messageElement) {
          messageElement.remove();
        }
      } else {
        alert('Gagal menghapus pesan: ' + result.message);
      }
    } catch (error) {
      console.error('Error deleting message:', error);
      alert('Terjadi kesalahan saat menghapus pesan.');
    }
  };

  // Fungsi untuk mute/unmute pengguna (admin only)
  window.toggleMute = async function(username) {
    const isMuted = mutedUsers.includes(username);
    const action = isMuted ? 'unmute' : 'mute';

    try {
      const response = await fetch(`chat_api.php?action=${action}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ username: username })
      });

      const result = await response.json();

      if (result.success) {
        // Update daftar pengguna yang di-mute
        if (isMuted) {
          mutedUsers = mutedUsers.filter(u => u !== username);
        } else {
          mutedUsers.push(username);
        }

        // Refresh tampilan
        refreshMessages();
      } else {
        alert(`Gagal ${isMuted ? 'membuka bisukan' : 'membisukan'} pengguna: ` + result.message);
      }
    } catch (error) {
      console.error(`Error ${action} user:`, error);
      alert(`Terjadi kesalahan saat ${isMuted ? 'membuka bisukan' : 'membisukan'} pengguna.`);
    }
  };

  // Fungsi untuk refresh semua pesan
  async function refreshMessages() {
    try {
      const response = await fetch('chat_api.php?action=fetch_all');
      const data = await response.json();

      if (data.success) {
        // Kosongkan kontainer
        chatContainer.innerHTML = '';
        lastMessageId = 0;

        // Tambahkan pemisah tanggal
        addDateSeparator();

        // Tambahkan semua pesan
        data.messages.forEach(msg => {
          const isMe = msg.username === currentUser.username;
          addMessageToChat(msg, isMe);
        });
      }
    } catch (error) {
      console.error('Gagal refresh pesan:', error);
    }
  }

  // Fungsi untuk memperbarui data pembelajaran Vivi
  function updateViviLearning(learningData) {
    if (!learningData) return;
    
    // Perbarui data pembelajaran lokal
    if (learningData.patterns) {
      if (!viviLearningData.patterns) viviLearningData.patterns = {};
      
      Object.keys(learningData.patterns).forEach(key => {
        if (!viviLearningData.patterns[key]) {
          viviLearningData.patterns[key] = {};
        }
        
        // Gabungkan pola yang ada dengan yang baru
        Object.assign(viviLearningData.patterns[key], learningData.patterns[key]);
      });
    }
    
    // Simpan ke localStorage untuk persistensi
    localStorage.setItem('viviLearningData', JSON.stringify(viviLearningData));
  }

// Fungsi untuk menampilkan kemampuan AI (DIPERBAIKI)
function showAICapabilities() {
    // Cek apakah pesan kemampuan sudah ada di chat
    const chatContainer = document.getElementById('chatContainer');
    // Gunakan string unik dari pesan sebagai identifier
    const capabilitiesMessageIdentifier = "Coba ketik 'Vivi [pertanyaan Anda]' untuk memulai!";
    
    if (chatContainer.innerHTML.includes(capabilitiesMessageIdentifier)) {
        // Jika pesan sudah ada, tidak perlu menambahkannya lagi
        console.log("Pesan kemampuan Vivi sudah ditampilkan.");
        return; 
    }

    const capabilities = [
        "Saya bisa memberikan informasi tentang DF Scenery",
        "Tanya saya tentang anime, donghua, dan komik",
        "Saya tahu informasi tentang novel, film, dan drakor",
        "Bertanya tentang film China, film Jepang, dan kartun",
        "Saya bisa membantu operasi matematika sederhana",
        "Tanya saya tentang sains, sejarah, atau teknologi",
        "Saya tahu informasi umum tentang geografi dan kesehatan",
        "Saya terus belajar dari percakapan kita untuk memberikan jawaban yang lebih baik"
    ];
    
    // Buat daftar kemampuan dengan format bullet point
    const capabilitiesList = capabilities.map(cap => `• ${cap}`).join('\n');

    // Tampilkan kemampuan AI sebagai balasan dari Vivi
    addMessageToChat({
        username: aiData.username,
        avatar_url: aiData.avatar,
        role: aiData.role,
        // Gunakan template literal untuk pesan yang lebih rapih dan mudah dibaca
        message: `Halo! Saya Vivi, asisten virtual yang bisa membantu Anda dengan berbagai topik. Saya bisa membantu dengan:

 ${capabilitiesList}

 ${capabilitiesMessageIdentifier}`
    }, false, true);
}

  // Tambahkan tombol untuk menampilkan kemampuan AI
  const inputActions = document.querySelector('.input-actions');
  const aiCapabilitiesBtn = document.createElement('button');
  aiCapabilitiesBtn.className = 'input-action-btn';
  aiCapabilitiesBtn.innerHTML = '<i class="fas fa-robot"></i>';
  aiCapabilitiesBtn.title = 'Kemampuan Vivi';
  aiCapabilitiesBtn.addEventListener('click', showAICapabilities);
  inputActions.appendChild(aiCapabilitiesBtn);

  // Inisialisasi tema
  const currentTheme = localStorage.getItem('theme');
  if (currentTheme === 'dark') {
    document.body.classList.add('dark-mode');
    themeIcon.className = 'fas fa-sun';
  }

  // Inisialisasi data pembelajaran Vivi dari localStorage
  const savedLearningData = localStorage.getItem('viviLearningData');
  if (savedLearningData) {
    try {
      viviLearningData = JSON.parse(savedLearningData);
    } catch (e) {
      console.error('Gagal memuat data pembelajaran Vivi:', e);
      viviLearningData = {};
    }
  }

  // Inisialisasi
  addDateSeparator();
  addSystemMessage(`${currentUser.username} bergabung ke dalam chatroom.`);
  
  // Gunakan refreshMessages() untuk mengambil SEMUA pesan saat pertama kali load
  refreshMessages().then(() => {
    // Setelah semua pesan dimuat, mulai interval untuk mengecek pesan baru
    fetchInterval = setInterval(fetchMessages, 3000);
  });

  // Fungsi untuk menambahkan pesan sistem
  function addSystemMessage(text) {
    const systemDiv = document.createElement('div');
    systemDiv.classList.add('system-message');
    systemDiv.textContent = text;
    chatContainer.appendChild(systemDiv);
    scrollToBottom();
  }
});
</script>

</body>
</html>