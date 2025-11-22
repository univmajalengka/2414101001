<?php
include 'db.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
  header("Location: logindfkomik.php");
  exit;
}

 $username = $_SESSION['username'];

// Ambil data user dari database
 $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
 $stmt->bind_param("s", $username);
 $stmt->execute();
 $user = $stmt->get_result()->fetch_assoc();

// Jika belum ada foto profil di session, ambil dari database
if (empty($_SESSION['profile_pic'])) {
  $_SESSION['profile_pic'] = $user['profile_pic'] ?? 'default.png';
}

 $error = "";
 $success = "";

// === GANTI NAMA ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_username'])) {
  $new_username = trim($_POST['new_username']);

  if ($new_username === "") {
    $error = "Nama tidak boleh kosong.";
  } else {
    $check = $conn->prepare("SELECT username FROM users WHERE username=? AND username!=?");
    $check->bind_param("ss", $new_username, $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
      $error = "Nama sudah dipakai pengguna lain.";
    } else {
      $stmt = $conn->prepare("UPDATE users SET username=? WHERE username=?");
      $stmt->bind_param("ss", $new_username, $username);
      if ($stmt->execute()) {
        $_SESSION['username'] = $new_username;
        $username = $new_username;
        $success = "Nama berhasil diperbarui!";
      } else {
        $error = "Gagal memperbarui nama.";
      }
    }
  }
}

// === GANTI EMAIL ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_email'])) {
  $new_email = trim($_POST['new_email']);
  if ($new_email === "") {
    $error = "Email tidak boleh kosong.";
  } elseif (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
    $error = "Format email tidak valid.";
  } else {
    // Cek apakah email sudah dipakai
    $check = $conn->prepare("SELECT email FROM users WHERE email=? AND username!=?");
    $check->bind_param("ss", $new_email, $username);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
      $error = "Email sudah digunakan oleh pengguna lain.";
    } else {
      $stmt = $conn->prepare("UPDATE users SET email=? WHERE username=?");
      $stmt->bind_param("ss", $new_email, $username);
      if ($stmt->execute()) {
        $success = "Email berhasil diperbarui!";
      } else {
        $error = "Gagal memperbarui email.";
      }
    }
  }
}

// === GANTI NOMOR TELEPON ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_phone'])) {
  $new_phone = trim($_POST['new_phone']);
  if ($new_phone === "") {
    $error = "Nomor telepon tidak boleh kosong.";
  } elseif (!preg_match('/^[0-9]{10,15}$/', $new_phone)) {
    $error = "Format nomor telepon tidak valid. Gunakan 10-15 digit angka.";
  } else {
    $stmt = $conn->prepare("UPDATE users SET phone=? WHERE username=?");
    $stmt->bind_param("ss", $new_phone, $username);
    if ($stmt->execute()) {
      $success = "Nomor telepon berhasil diperbarui!";
      // Refresh data user
      $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $user = $stmt->get_result()->fetch_assoc();
    } else {
      $error = "Gagal memperbarui nomor telepon.";
    }
  }
}

// === GANTI FOTO PROFIL ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_pic'])) {
  $file = $_FILES['profile_pic'];
  if ($file['error'] === 0) {
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (in_array($ext, $allowed)) {
      $newName = uniqid('pf_') . '.' . $ext;
      $uploadDir = 'uploads/profiles/';
      $uploadPath = $uploadDir . $newName;

      if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

      if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        if (!empty($user['profile_pic']) && $user['profile_pic'] !== 'default.png' && file_exists($uploadDir . $user['profile_pic'])) {
          @unlink($uploadDir . $user['profile_pic']);
        }
        $stmt = $conn->prepare("UPDATE users SET profile_pic=? WHERE username=?");
        $stmt->bind_param("ss", $newName, $username);
        $stmt->execute();

        $_SESSION['profile_pic'] = $newName;
        $success = "Foto profil berhasil diperbarui!";
        
        // Refresh data user
        $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
      } else {
        $error = "Gagal memindahkan file ke folder upload.";
      }
    } else {
      $error = "Format file tidak didukung. Gunakan JPG, JPEG, PNG, GIF, atau WEBP.";
    }
  } elseif ($file['error'] !== 4) {
    $error = "Gagal mengunggah file. Coba lagi.";
  }
}

// === GANTI PASSWORD ===
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['old_password'], $_POST['new_password'], $_POST['confirm_password'])) {
  $old = $_POST['old_password'];
  $new = $_POST['new_password'];
  $confirm = $_POST['confirm_password'];

  if ($new === "" || $confirm === "" || $old === "") {
    $error = "Semua kolom password harus diisi.";
  } elseif ($new !== $confirm) {
    $error = "Konfirmasi password tidak cocok.";
  } elseif (!password_verify($old, $user['password'])) {
    $error = "Password lama salah.";
  } else {
    $hashed = password_hash($new, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE username=?");
    $stmt->bind_param("ss", $hashed, $username);
    if ($stmt->execute()) {
      $success = "Password berhasil diubah!";
    } else {
      $error = "Gagal mengubah password.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profil Saya - DF Komik</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --bg: #0f0f10;
      --text: #f2f2f2;
      --card: #151518;
      --border: #2a2a2d;
      --accent: #7b5bff;
      --hover: #6244e5;
      --input: #222;
      --success: #06d6a0;
      --error: #ff5555;
    }

    body.light {
      --bg: linear-gradient(135deg, #d7efff, #ffffff);
      --text: #121212;
      --card: rgba(255, 255, 255, 0.95);
      --border: #ccc;
      --accent: #0077b6;
      --hover: #005f87;
      --input: #eee;
      --success: #06d6a0;
      --error: #ff5555;
    }

    body {
      margin: 0;
      background: var(--bg);
      color: var(--text);
      font-family: "Poppins", sans-serif;
      transition: background 0.4s, color 0.4s;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* Background decoration */
    .bg-decoration {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: -1;
      overflow: hidden;
    }

    .bg-circle {
      position: absolute;
      border-radius: 50%;
      filter: blur(60px);
      opacity: 0.4;
    }

    .bg-circle:nth-child(1) {
      width: 300px;
      height: 300px;
      background: var(--accent);
      top: -100px;
      right: -100px;
    }

    .bg-circle:nth-child(2) {
      width: 250px;
      height: 250px;
      background: var(--hover);
      bottom: -50px;
      left: -50px;
    }

    .bg-circle:nth-child(3) {
      width: 200px;
      height: 200px;
      background: var(--success);
      top: 50%;
      left: 30%;
    }

    /* Header */
    header {
      backdrop-filter: blur(12px);
      background: rgba(0, 119, 182, 0.7);
      padding: 18px 45px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid rgba(255,255,255,0.3);
      box-shadow: 0 10px 35px rgba(0,0,0,0.12);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    header h1 {
      margin: 0;
      font-weight: 700;
      font-size: 26px;
      letter-spacing: 1px;
      color: #ffffff;
      display: flex;
      align-items: center;
    }

    header h1 i {
      margin-right: 12px;
      font-size: 24px;
    }

    nav {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    nav button {
      padding: 10px 18px;
      border-radius: 14px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      background: rgba(255,255,255,0.15);
      color: #ffffff;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.4);
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    nav button i {
      font-size: 16px;
    }

    nav button:hover {
      transform: translateY(-3px);
      background: rgba(255,255,255,0.32);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    /* Main content */
    main {
      flex: 1;
      padding: 40px 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    .profile-container {
      width: 100%;
      max-width: 900px;
      display: grid;
      grid-template-columns: 1fr 2fr;
      gap: 30px;
    }

    /* Profile card */
    .profile-card {
      background: var(--card);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.3);
      text-align: center;
      height: fit-content;
      position: sticky;
      top: 100px;
    }

    .profile-pic-container {
      position: relative;
      margin-bottom: 20px;
    }

    .profile-pic {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid var(--accent);
      box-shadow: 0 0 20px rgba(123, 91, 255, 0.3);
    }

    .upload-overlay {
      position: absolute;
      bottom: 5px;
      right: 5px;
      background: var(--accent);
      color: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .upload-overlay:hover {
      background: var(--hover);
      transform: scale(1.1);
    }

    .profile-name {
      font-size: 24px;
      font-weight: 700;
      margin-bottom: 5px;
      color: var(--text);
    }

    .profile-role {
      font-size: 16px;
      color: var(--accent);
      margin-bottom: 20px;
      font-weight: 500;
    }

    .profile-info {
      text-align: left;
      margin-top: 20px;
    }

    .info-item {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      color: var(--text);
    }

    .info-item i {
      width: 20px;
      margin-right: 10px;
      color: var(--accent);
    }

    /* Settings card */
    .settings-card {
      background: var(--card);
      border-radius: 20px;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255,255,255,0.3);
    }

    .settings-tabs {
      display: flex;
      margin-bottom: 25px;
      border-bottom: 2px solid rgba(0, 119, 182, 0.1);
    }

    .tab {
      padding: 12px 20px;
      cursor: pointer;
      font-weight: 600;
      color: var(--text);
      border-bottom: 3px solid transparent;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .tab:hover {
      color: var(--accent);
    }

    .tab.active {
      color: var(--accent);
      border-bottom: 3px solid var(--accent);
    }

    .tab i {
      font-size: 18px;
    }

    .tab-content {
      display: none;
    }

    .tab-content.active {
      display: block;
      animation: fadeIn 0.3s ease-in-out;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--text);
    }

    .form-group input {
      width: 100%;
      padding: 12px;
      border-radius: 12px;
      border: 1px solid var(--border);
      background: var(--input);
      color: var(--text);
      font-size: 16px;
      transition: all 0.3s;
    }

    .form-group input:focus {
      border-color: var(--accent);
      box-shadow: 0 0 6px rgba(123, 91, 255, 0.4);
      outline: none;
    }

    .btn {
      background: var(--accent);
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 12px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn:hover {
      background: var(--hover);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(123, 91, 255, 0.3);
    }

    .btn-danger {
      background: #ff5555;
    }

    .btn-danger:hover {
      background: #ff3333;
      box-shadow: 0 6px 15px rgba(255, 85, 85, 0.3);
    }

    /* Message */
    .message {
      padding: 15px;
      border-radius: 12px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .message.success {
      background: rgba(6, 214, 160, 0.1);
      color: var(--success);
      border: 1px solid var(--success);
    }

    .message.error {
      background: rgba(255, 85, 85, 0.1);
      color: var(--error);
      border: 1px solid var(--error);
    }

    /* Animations */
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(-5px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* Theme toggle */
    .mode-toggle {
      position: fixed;
      right: 20px;
      bottom: 20px;
      background: var(--accent);
      border: none;
      color: white;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      font-size: 20px;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 0 15px rgba(0,0,0,0.3);
      transition: all 0.3s;
      z-index: 1000;
    }

    .mode-toggle:hover {
      background: var(--hover);
      transform: scale(1.1);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .profile-container {
        grid-template-columns: 1fr;
      }

      .profile-card {
        position: static;
      }

      header {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
      }

      nav {
        flex-wrap: wrap;
        justify-content: center;
      }
    }
  </style>
</head>
<body>
  <!-- Background decoration -->
  <div class="bg-decoration">
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
  </div>

  <!-- Header -->
  <header>
    <h1><i class="fas fa-user-circle"></i> Profil Saya</h1>
    <nav>
      <button onclick="location.href='dfscenery.php'">
        <i class="fas fa-home"></i> DFScenery
      </button>
      <button onclick="location.href='dfkomik.php'">
        <i class="fas fa-book"></i> DFKomik
      </button>
      <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'developer', 'dev_master'])): ?>
        <button onclick="location.href='admin.php'">
          <i class="fas fa-cogs"></i> Admin
        </button>
      <?php endif; ?>
    </nav>
  </header>

  <!-- Main content -->
  <main>
    <div class="profile-container">
      <!-- Profile card -->
      <div class="profile-card">
        <div class="profile-pic-container">
          <img src="uploads/profiles/<?= htmlspecialchars($_SESSION['profile_pic'] ?? 'default.png') ?>" alt="Foto Profil" class="profile-pic">
          <label for="profile_pic_upload" class="upload-overlay">
            <i class="fas fa-camera"></i>
          </label>
        </div>
        
        <!-- Form untuk upload foto profil -->
        <form id="upload_form" method="POST" enctype="multipart/form-data" style="display: none;">
          <input type="file" name="profile_pic" id="profile_pic_upload" accept="image/*">
        </form>

        <h2 class="profile-name"><?= htmlspecialchars($username) ?></h2>
        <div class="profile-role"><?= ucfirst($user['role'] ?? 'user') ?></div>

        <div class="profile-info">
          <div class="info-item">
            <i class="fas fa-envelope"></i>
            <span><?= htmlspecialchars($user['email'] ?? 'Email belum diatur') ?></span>
          </div>
          <div class="info-item">
            <i class="fas fa-phone"></i>
            <span><?= htmlspecialchars($user['phone'] ?? 'Nomor telepon belum diatur') ?></span>
          </div>
          <div class="info-item">
            <i class="fas fa-calendar"></i>
            <span>Bergabung sejak <?= date('d M Y', strtotime($user['created_at'] ?? 'now')) ?></span>
          </div>
        </div>
      </div>

      <!-- Settings card -->
      <div class="settings-card">
        <?php if (!empty($error)): ?>
          <div class="message error">
            <i class="fas fa-exclamation-circle"></i>
            <?= htmlspecialchars($error) ?>
          </div>
        <?php elseif (!empty($success)): ?>
          <div class="message success">
            <i class="fas fa-check-circle"></i>
            <?= htmlspecialchars($success) ?>
          </div>
        <?php endif; ?>

        <div class="settings-tabs">
          <div class="tab active" onclick="switchTab('info')">
            <i class="fas fa-user"></i> Informasi
          </div>
          <div class="tab" onclick="switchTab('security')">
            <i class="fas fa-lock"></i> Keamanan
          </div>
        </div>

        <!-- Info tab -->
        <div id="info-tab" class="tab-content active">
          <form method="POST">
            <div class="form-group">
              <label for="new_username">Nama Pengguna</label>
              <input type="text" id="new_username" name="new_username" value="<?= htmlspecialchars($username) ?>" required>
            </div>

            <div class="form-group">
              <label for="new_email">Email</label>
              <input type="email" id="new_email" name="new_email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
              <label for="new_phone">Nomor Telepon</label>
              <input type="tel" id="new_phone" name="new_phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="Contoh: 08123456789" pattern="[0-9]{10,15}" required>
              <small style="color: var(--text); opacity: 0.7;">Masukkan 10-15 digit angka tanpa spasi atau karakter khusus</small>
            </div>

            <button type="submit" class="btn">
              <i class="fas fa-save"></i> Simpan Perubahan
            </button>
          </form>
        </div>

        <!-- Security tab -->
        <div id="security-tab" class="tab-content">
          <form method="POST">
            <div class="form-group">
              <label for="old_password">Password Lama</label>
              <input type="password" id="old_password" name="old_password" required>
            </div>

            <div class="form-group">
              <label for="new_password">Password Baru</label>
              <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
              <label for="confirm_password">Konfirmasi Password Baru</label>
              <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" class="btn">
              <i class="fas fa-key"></i> Ubah Password
            </button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <!-- Theme toggle -->
  <button class="mode-toggle" id="modeToggle" title="Ganti Mode">🌙</button>

  <script>
    // === Mode Dark/Light ===
    const toggle = document.getElementById('modeToggle');
    const currentMode = localStorage.getItem('theme') || 'dark';
    document.body.classList.toggle('light', currentMode === 'light');
    toggle.textContent = currentMode === 'light' ? '🌙' : '☀️';

    toggle.addEventListener('click', () => {
      document.body.classList.toggle('light');
      const isLight = document.body.classList.contains('light');
      localStorage.setItem('theme', isLight ? 'light' : 'dark');
      toggle.textContent = isLight ? '🌙' : '☀️';
    });

    // === Tab switching ===
    function switchTab(tabName) {
      // Hide all tabs
      document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.remove('active');
      });
      
      // Remove active class from all tab buttons
      document.querySelectorAll('.tab').forEach(tabBtn => {
        tabBtn.classList.remove('active');
      });
      
      // Show selected tab
      document.getElementById(tabName + '-tab').classList.add('active');
      
      // Add active class to clicked tab button
      event.target.closest('.tab').classList.add('active');
    }

    // === Profile picture upload ===
    document.getElementById('profile_pic_upload').addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        // Preview the image
        const reader = new FileReader();
        reader.onload = function(event) {
          document.querySelector('.profile-pic').src = event.target.result;
        };
        reader.readAsDataURL(file);
        
        // Submit the form
        document.getElementById('upload_form').submit();
      }
    });
  </script>
</body>
</html>