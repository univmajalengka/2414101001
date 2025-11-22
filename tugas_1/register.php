<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    // Validasi input kosong
    if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
        echo json_encode([
            "success" => false,
            "message" => "⚠️ Harap isi semua data dengan benar!"
        ]);
        exit;
    }

    // Validasi panjang username
    if (strlen($username) < 3 || strlen($username) > 15) {
        echo json_encode([
            "success" => false,
            "message" => "⚠️ Username harus 3–15 karakter."
        ]);
        exit;
    }

    // Validasi karakter username
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        echo json_encode([
            "success" => false,
            "message" => "⚠️ Username hanya boleh berisi huruf, angka, dan underscore (_)."
        ]);
        exit;
    }

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode([
            "success" => false,
            "message" => "⚠️ Format email tidak valid."
        ]);
        exit;
    }

    // Validasi password
    if (strlen($password) < 6) {
        echo json_encode([
            "success" => false,
            "message" => "⚠️ Password harus minimal 6 karakter."
        ]);
        exit;
    }

    // Validasi konfirmasi password
    if ($password !== $confirmPassword) {
        echo json_encode([
            "success" => false,
            "message" => "⚠️ Password dan konfirmasi password tidak cocok."
        ]);
        exit;
    }

    // Cek apakah username sudah digunakan
    $check = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check->bind_param("ss", $username, $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode([
            "success" => false,
            "message" => "❌ Username atau email sudah digunakan, silakan pilih yang lain."
        ]);
        exit;
    }

    // Enkripsi password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $role = "user";

    // Simpan ke database
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        echo json_encode([
            "success" => true,
            "message" => "✅ Registrasi berhasil! Silakan login untuk melanjutkan."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "❌ Gagal menyimpan data ke database: " . $conn->error
        ]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Register - DF Scenery</title>
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
    --bg-color: linear-gradient(135deg, #d7efff, #ffffff);
    --text-color: #003049;
    --card-bg: rgba(255, 255, 255, 0.95);
    --input-bg: rgba(255, 255, 255, 0.8);
    --input-focus-bg: rgba(255, 255, 255, 0.95);
    --primary-color: #0077b6;
    --secondary-color: #00b4d8;
    --accent-color: #90e0ef;
    --border-color: rgba(0, 119, 182, 0.2);
    --placeholder-color: rgba(0, 48, 73, 0.6);
    --overlay-bg: linear-gradient(135deg, rgba(0, 119, 182, 0.8), rgba(0, 180, 216, 0.6));
    --notification-success-bg: linear-gradient(135deg, #06d6a0, #2a9d8f);
    --notification-error-bg: linear-gradient(135deg, #e63946, #d62828);
    --password-strength-weak: #e63946;
    --password-strength-medium: #f9c74f;
    --password-strength-strong: #06d6a0;
  }

  /* Variabel Warna untuk Tema Gelap */
  body.dark-mode {
    --bg-color: linear-gradient(135deg, #1a1a2e, #16213e);
    --text-color: #e0e0e0;
    --card-bg: rgba(40, 40, 55, 0.95);
    --input-bg: rgba(30, 30, 45, 0.8);
    --input-focus-bg: rgba(30, 30, 45, 0.95);
    --primary-color: #4cc9f0;
    --secondary-color: #4895ef;
    --accent-color: #7209b7;
    --border-color: rgba(76, 201, 240, 0.3);
    --placeholder-color: rgba(224, 224, 224, 0.6);
    --overlay-bg: linear-gradient(135deg, rgba(22, 33, 62, 0.8), rgba(40, 40, 55, 0.6));
    --notification-success-bg: linear-gradient(135deg, #06d6a0, #2a9d8f);
    --notification-error-bg: linear-gradient(135deg, #e63946, #d62828);
    --password-strength-weak: #e63946;
    --password-strength-medium: #f9c74f;
    --password-strength-strong: #06d6a0;
  }

  body {
    font-family: 'Poppins', sans-serif;
    background: var(--bg-color);
    color: var(--text-color);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    position: relative;
    overflow: hidden;
    transition: background 0.3s ease, color 0.3s ease;
  }

  /* Latar belakang dengan gambar pemandangan */
  .bg-scenery {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('20250919_123536.jpg') no-repeat center center/cover;
    filter: brightness(0.7);
    z-index: -2;
  }

  .bg-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: var(--overlay-bg);
    z-index: -1;
  }

  /* Kontainer register */
  .register-container {
    width: 100%;
    max-width: 450px;
    padding: 20px;
    z-index: 1;
  }

  .register-card {
    background: var(--card-bg);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 8px 35px rgba(0, 0, 0, 0.12);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .register-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
  }

  /* Logo dan judul */
  .logo-container {
    text-align: center;
    margin-bottom: 30px;
  }

  .logo {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
    overflow: hidden; /* Memastikan gambar tidak keluar dari lingkaran */
  }

  /* CSS untuk gambar logo */
  .logo img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Memastikan gambar menutupi area tanpa distorsi */
  }

  .site-name {
    font-size: 28px;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 5px;
  }

  .site-name .white {
    color: var(--primary-color);
  }

  .site-name .black {
    color: var(--primary-color);
  }

  .subtitle {
    color: var(--text-color);
    opacity: 0.8;
    font-size: 14px;
    margin-bottom: 30px;
  }

  /* Elemen form */
  .form-group {
    margin-bottom: 25px;
  }

  .input-wrapper {
    position: relative;
  }

  .input-wrapper i {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
    font-size: 18px;
    z-index: 2;
  }

  input {
    width: 100%;
    padding: 15px 15px 15px 45px;
    border-radius: 12px;
    border: 1px solid var(--border-color);
    background: var(--input-bg);
    color: var(--text-color);
    font-size: 16px;
    transition: all 0.3s ease;
    outline: none;
  }

  input:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.1);
    background: var(--input-focus-bg);
  }

  input::placeholder {
    color: var(--placeholder-color);
    opacity: 0.6;
  }

  /* Indikator kekuatan password */
  .password-strength {
    height: 5px;
    border-radius: 3px;
    margin-top: 8px;
    background: #e0e0e0;
    overflow: hidden;
  }

  .password-strength-meter {
    height: 100%;
    width: 0;
    transition: width 0.3s, background-color 0.3s;
  }

  .password-strength-meter.weak {
    width: 33%;
    background-color: var(--password-strength-weak);
  }

  .password-strength-meter.medium {
    width: 66%;
    background-color: var(--password-strength-medium);
  }

  .password-strength-meter.strong {
    width: 100%;
    background-color: var(--password-strength-strong);
  }

  .password-hint {
    font-size: 12px;
    color: var(--text-color);
    opacity: 0.7;
    margin-top: 5px;
  }

  /* Checkbox dan terms */
  .form-options {
    display: flex;
    align-items: flex-start;
    margin-bottom: 25px;
  }

  .terms-checkbox {
    display: flex;
    align-items: flex-start;
    font-size: 14px;
    color: var(--text-color);
  }

  .terms-checkbox input {
    width: auto;
    margin-right: 8px;
    margin-top: 2px;
    padding: 0;
  }

  .terms-checkbox label {
    color: var(--text-color);
    opacity: 0.8;
  }

  .terms-checkbox a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 500;
  }

  .terms-checkbox a:hover {
    text-decoration: underline;
  }

  /* Tombol submit */
  .btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
  }

  .btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0, 119, 182, 0.3);
  }

  .btn:active {
    transform: translateY(-1px);
  }

  /* Link login */
  .login-link {
    text-align: center;
    margin-top: 25px;
    font-size: 14px;
    color: var(--text-color);
  }

  .login-link a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
  }

  .login-link a:hover {
    text-decoration: underline;
    color: var(--secondary-color);
  }

  /* Notifikasi */
  .notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 12px;
    color: white;
    font-weight: 500;
    transform: translateX(120%);
    transition: transform 0.3s ease;
    z-index: 1000;
    max-width: 300px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  }

  .notification.show {
    transform: translateX(0);
  }

  .notification.success {
    background: var(--notification-success-bg);
  }

  .notification.error {
    background: var(--notification-error-bg);
  }

  /* Loading spinner */
  .spinner {
    display: none;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 1s ease-in-out infinite;
    margin: 0 auto;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  .btn.loading .btn-text {
    display: none;
  }

  .btn.loading .spinner {
    display: block;
  }

  /* Tombol Tema Mengambang */
  .theme-toggle-float {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 9999;
  }

  #theme-toggle-btn {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--primary-color);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.3s ease;
  }

  #theme-toggle-btn:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
  }

  /* Desain Responsif */
  @media (max-width: 480px) {
    .register-card {
      padding: 30px 20px;
    }

    .site-name {
      font-size: 24px;
    }

    .logo {
      width: 70px;
      height: 70px;
    }
  }
</style>
</head>
<body>

<!-- Latar belakang dengan gambar pemandangan -->
<div class="bg-scenery"></div>
<div class="bg-overlay"></div>

<!-- Kontainer register -->
<div class="register-container">
  <div class="register-card">
    <!-- Logo dan judul -->
    <div class="logo-container">
      <div class="logo">
        <img src="binigua.jpg" alt="DF Scenery Logo">
      </div>
      <h1 class="site-name"><span class="white">DF</span><span class="black">Scenery</span></h1>
      <p class="subtitle">Buat akun baru</p>
    </div>

    <!-- Form register -->
    <form id="registerForm" method="POST">
      <div class="form-group">
        <div class="input-wrapper">
          <i class="fas fa-user"></i>
          <input type="text" name="username" placeholder="Username (3-15 karakter)" required>
        </div>
      </div>

      <div class="form-group">
        <div class="input-wrapper">
          <i class="fas fa-envelope"></i>
          <input type="email" name="email" placeholder="Email" required>
        </div>
      </div>

      <div class="form-group">
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" name="password" id="password" placeholder="Password (minimal 6 karakter)" required>
        </div>
        <div class="password-strength">
          <div class="password-strength-meter" id="passwordStrength"></div>
        </div>
        <div class="password-hint">Gunakan kombinasi huruf, angka, dan simbol untuk password yang kuat</div>
      </div>

      <div class="form-group">
        <div class="input-wrapper">
          <i class="fas fa-lock"></i>
          <input type="password" name="confirm_password" placeholder="Konfirmasi Password" required>
        </div>
      </div>

      <div class="form-options">
        <div class="terms-checkbox">
          <input type="checkbox" name="terms" id="terms" required>
          <label for="terms">Saya setuju dengan <a href="#">Syarat dan Ketentuan</a> serta <a href="#">Kebijakan Privasi</a></label>
        </div>
      </div>

      <button type="submit" class="btn">
        <span class="btn-text">Daftar</span>
        <div class="spinner"></div>
      </button>
    </form>

    <!-- Link login -->
    <div class="login-link">
      Sudah punya akun? <a href="login.php">Login</a>
    </div>
  </div>
</div>

<!-- Kontainer notifikasi -->
<div id="notification" class="notification"></div>

<!-- Tombol Toggle Tema Mengambang -->
<div class="theme-toggle-float">
    <button id="theme-toggle-btn" aria-label="Toggle theme">
      <i class="fas fa-sun" id="theme-icon-light"></i>
      <i class="fas fa-moon" id="theme-icon-dark" style="display: none;"></i>
    </button>
</div>

<script>
// Tampilkan notifikasi
function showNotification(message, type) {
  const notification = document.getElementById('notification');
  notification.textContent = message;
  notification.className = 'notification ' + type;
  notification.classList.add('show');
  
  setTimeout(() => {
    notification.classList.remove('show');
  }, 5000);
}

// Pemeriksa kekuatan password
const passwordInput = document.getElementById('password');
const passwordStrength = document.getElementById('passwordStrength');

passwordInput.addEventListener('input', function() {
  const password = this.value;
  let strength = 0;
  
  if (password.length >= 6) strength += 1;
  if (password.length >= 10) strength += 1;
  if (/[A-Z]/.test(password) && /[a-z]/.test(password)) strength += 1;
  if (/[0-9]/.test(password)) strength += 1;
  if (/[^A-Za-z0-9]/.test(password)) strength += 1;
  
  passwordStrength.className = 'password-strength-meter';
  
  if (password.length > 0) {
    if (strength <= 2) {
      passwordStrength.classList.add('weak');
    } else if (strength <= 4) {
      passwordStrength.classList.add('medium');
    } else {
      passwordStrength.classList.add('strong');
    }
  }
});

// Tangani pengiriman form register
document.getElementById('registerForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  
  const btn = this.querySelector('.btn');
  const btnText = btn.querySelector('.btn-text');
  const spinner = btn.querySelector('.spinner');
  
  // Tampilkan status loading
  btn.classList.add('loading');
  btn.disabled = true;
  
  const formData = new FormData(this);
  
  try {
    const response = await fetch('register.php', { 
      method: 'POST', 
      body: formData 
    });
    
    const data = await response.json();
    
    if (data.success) {
      showNotification(data.message, 'success');
      
      // Alihkan setelah jeda singkat
      setTimeout(() => {
        window.location.href = 'login.php';
      }, 2000);
    } else {
      showNotification(data.message, 'error');
      
      // Reset status tombol
      btn.classList.remove('loading');
      btn.disabled = false;
    }
  } catch (error) {
    showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
    
    // Reset status tombol
    btn.classList.remove('loading');
    btn.disabled = false;
  }
});

// Tambahkan efek fokus ke input
const inputs = document.querySelectorAll('input');
inputs.forEach(input => {
  input.addEventListener('focus', function() {
    this.closest('.input-wrapper').style.transform = 'translateY(-2px)';
  });
  
  input.addEventListener('blur', function() {
    this.closest('.input-wrapper').style.transform = 'translateY(0)';
  });
});

// --- LOGIKA TEMA (TERANG/GELAP) ---
const themeToggle = document.getElementById('theme-toggle-btn');
const themeIconLight = document.getElementById('theme-icon-light');
const themeIconDark = document.getElementById('theme-icon-dark');

// Fungsi untuk memperbarui ikon tema
function updateThemeIcon() {
  if (document.body.classList.contains('dark-mode')) {
    themeIconLight.style.display = 'none';
    themeIconDark.style.display = 'inline';
  } else {
    themeIconLight.style.display = 'inline';
    themeIconDark.style.display = 'none';
  }
}

// Periksa preferensi tema yang tersimpan di localStorage
const currentTheme = localStorage.getItem('theme');
if (currentTheme === 'dark') {
  document.body.classList.add('dark-mode');
}
updateThemeIcon();

// Event listener untuk tombol toggle tema
themeToggle.addEventListener('click', function() {
  document.body.classList.toggle('dark-mode');
  
  // Simpan preferensi tema ke localStorage
  if (document.body.classList.contains('dark-mode')) {
    localStorage.setItem('theme', 'dark');
  } else {
    localStorage.setItem('theme', 'light');
  }
  
  // Perbarui ikon
  updateThemeIcon();
});
</script>
</body>
</html>