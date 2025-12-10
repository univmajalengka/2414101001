<?php
session_start();
include 'db.php';

// --- Cek ID pesanan dari URL ---
 $order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) die("<h2>❌ Tiket tidak ditemukan.</h2>");

// --- Ambil data tiket dari database ---
  $stmt = $conn->prepare("SELECT id, name, email, phone, ticket_type, quantity, ticket_price, total_price, travel_date, participant_count, travel_days, accommodation, transportation, food, package_total, payment_method FROM tickets WHERE id = ?");
 $stmt->bind_param("i", $order_id);
 $stmt->execute();

// Ambil hasil sebagai array
 $result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("<h2>❌ Data tiket tidak ditemukan.</h2>");
}

 $row = $result->fetch_assoc();
 $stmt->close(); // Tutup statement

// Assign data ke variabel
 $id          = $row['id'];
 $name        = $row['name'];
 $email       = $row['email'];
 $phone       = $row['phone'];
 $ticket_type = $row['ticket_type'];
 $quantity    = $row['quantity'];
 $ticket_price = $row['ticket_price'];
 $total_price = $row['total_price'];
 $travel_date = $row['travel_date'];
 $participant_count = $row['participant_count'];
 $travel_days  = $row['travel_days']; 
 $accommodation = $row['accommodation'];
 $transportation = $row['transportation'];
 $food = $row['food'];
 $package_total = $row['package_total'];
 $payment_method = $row['payment_method'];

// Cek apakah tanggal valid sebelum diformat
if (!empty($travel_date)) {
    $timestamp = strtotime($travel_date);
    if ($timestamp !== false) {
        $formatted_date = date('d F Y', $timestamp);
    } else {
        $formatted_date = "Tanggal tidak valid";
    }
} else {
    $formatted_date = "Tanggal tidak tersedia";
}

// Hitung harga per layanan berdasarkan jumlah peserta
 $accommodation_price = $accommodation ? 100000 * $participant_count : 0;
 $transportation_price = $transportation ? 120000 * $participant_count : 0;
 $food_price = $food ? 65000 * $participant_count : 0;

// --- Pilih gambar sesuai jenis tiket ---
switch (strtolower($ticket_type)) {
    case 'family': $ticket_image = 'binigua.jpg'; break;
    case 'single': $ticket_image = 'binigua1.jpg'; break;
    case 'double': $ticket_image = 'binigua2.jpg'; break;
    default: $ticket_image = 'ticket_bg.jpg'; break;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>E-Ticket - DFScenery</title>
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
      --header-bg: rgba(0, 119, 182, 0.7);
      --footer-bg: rgba(0, 119, 182, 0.9);
      --primary-color: #0077b6;
      --secondary-color: #00b4d8;
      --accent-color: #90e0ef;
      --border-color: rgba(255,255,255,0.3);
      --ticket-info-border: rgba(0, 119, 182, 0.1);
      --ticket-id-color: #777;
      --success-bg: linear-gradient(135deg, #06d6a0, #2a9d8f);
      --package-bg: rgba(0, 119, 182, 0.05);
      --package-border: rgba(0, 119, 182, 0.2);
    }

    /* Variabel Warna untuk Tema Gelap */
    body.dark-mode {
      --bg-color: linear-gradient(135deg, #1a1a2e, #16213e);
      --text-color: #e0e0e0;
      --card-bg: rgba(40, 40, 55, 0.95);
      --header-bg: rgba(22, 33, 62, 0.9);
      --footer-bg: rgba(22, 33, 62, 0.9);
      --primary-color: #4cc9f0;
      --secondary-color: #4895ef;
      --accent-color: #7209b7;
      --border-color: rgba(255,255,255,0.1);
      --ticket-info-border: rgba(76, 201, 240, 0.2);
      --ticket-id-color: #b0b0b0;
      --success-bg: linear-gradient(135deg, #06d6a0, #2a9d8f);
      --package-bg: rgba(76, 201, 240, 0.1);
      --package-border: rgba(76, 201, 240, 0.3);
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--bg-color);
      color: var(--text-color);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      padding: 80px 20px 20px;
      position: relative;
      overflow-x: hidden;
      transition: background 0.3s ease, color 0.3s ease;
    }

    /* Dekorasi Background */
    .bg-decoration {
      position: absolute;
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
      background: var(--primary-color);
      top: -100px;
      right: -100px;
    }

    .bg-circle:nth-child(2) {
      width: 250px;
      height: 250px;
      background: var(--secondary-color);
      bottom: -50px;
      left: -50px;
    }

    .bg-circle:nth-child(3) {
      width: 200px;
      height: 200px;
      background: var(--accent-color);
      top: 50%;
      left: 30%;
    }

    /* === GAYA HEADER === */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 30px;
      backdrop-filter: blur(12px);
      background: var(--header-bg);
      color: white;
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 10000;
      border-bottom: 1px solid var(--border-color);
      box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    header h1 {
      margin: 0;
      font-weight: 700;
      font-size: 22px;
      letter-spacing: 1px;
    }

    header h1 .white {
      color: #fff;
    }

    header h1 .black {
      color: #000;
    }

    nav button {
      margin: 0 5px;
      padding: 8px 18px;
      border-radius: 12px;
      border: none;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      transition: 0.3s ease;
      background: rgba(255,255,255,0.15);
      color: #ffffff;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.4);
    }

    nav button:hover {
      transform: translateY(-2px);
      background: rgba(255,255,255,0.32);
      box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    }

    /* === Tautan Masuk & Daftar di Header === */
    .left-header {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .auth-nav a {
      margin-left: 10px;
      text-decoration: none;
      color: white;
      font-weight: bold;
      font-size: 14px;
      background: rgba(255, 255, 255, 0.15);
      padding: 6px 12px;
      border-radius: 18px;
      transition: 0.3s;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.4);
    }

    .auth-nav a:hover {
      background: rgba(255, 255, 255, 0.32);
      color: #000;
      transform: translateY(-2px);
    }

    /* FOTO PROFIL */
    .profile-area {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .profile-wrapper {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      padding: 5px 10px;
      border-radius: 25px;
      background: rgba(255, 255, 255, 0.15);
      cursor: pointer;
      transition: 0.25s;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.4);
    }

    .profile-wrapper:hover {
      background: rgba(255, 255, 255, 0.32);
      transform: translateY(-2px);
    }

    .profile-pic,
    .profile-avatar {
      width: 32px;
      height: 32px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid white;
    }

    .username-click,
    .username-text {
      color: white;
      font-weight: bold;
      font-size: 13px;
    }

    /* Tombol logout */
    .logout-btn {
      background: rgba(255, 255, 255, 0.15);
      padding: 5px 12px;
      border-radius: 18px;
      text-decoration: none;
      color: white;
      font-weight: 600;
      font-size: 14px;
      transition: 0.25s;
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.4);
    }

    .logout-btn:hover {
      background: rgba(255, 255, 255, 0.32);
      color: #000;
      transform: translateY(-2px);
    }

    nav button.active {
      background: rgba(255,255,255,0.9) !important;
      color: var(--primary-color) !important;
      font-weight: 700;
      padding-bottom: 5px;
    }

    /* Tombol toggle menu mobile */
    .menu-toggle {
      display: none;
      flex-direction: column;
      cursor: pointer;
    }

    .menu-toggle span {
      width: 25px;
      height: 3px;
      background: #fff;
      margin: 3px 0;
      transition: 0.3s;
    }

    /* === KONTAINER TIKET === */
    .ticket-container {
      width: 100%;
      max-width: 500px;
      background: var(--card-bg);
      border-radius: 20px;
      box-shadow: 0 8px 35px rgba(0, 0, 0, 0.12);
      backdrop-filter: blur(10px);
      border: 1px solid var(--border-color);
      overflow: hidden;
      position: relative;
      z-index: 1;
      animation: fadeInUp 0.5s ease;
    }

    .ticket-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 5px;
      background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
    }

    .ticket-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 20px;
      text-align: center;
      position: relative;
    }

    .ticket-header h2 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 5px;
      text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .ticket-header p {
      font-size: 16px;
      opacity: 0.9;
    }

    .ticket-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .ticket-content {
      padding: 25px;
    }

    .ticket-info {
      margin-bottom: 20px;
    }

    .ticket-info-item {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid var(--ticket-info-border);
    }

    .ticket-info-item:last-child {
      border-bottom: none;
    }

    .ticket-info-label {
      font-weight: 600;
      color: var(--primary-color);
    }

    .ticket-info-value {
      font-weight: 500;
    }

    .ticket-id {
      text-align: center;
      margin-top: 15px;
      font-size: 14px;
      color: var(--ticket-id-color);
    }

    /* === BAGIAN PAKET PERJALANAN === */
    .package-section {
      margin-top: 20px;
      padding: 15px;
      background: var(--package-bg);
      border-radius: 15px;
      border: 1px solid var(--package-border);
    }

    .package-title {
      font-size: 18px;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 15px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .package-details {
      display: grid;
      grid-template-columns: 1fr;
      gap: 12px;
      margin-bottom: 15px;
    }

    .package-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px;
      background: rgba(255, 255, 255, 0.5);
      border-radius: 10px;
      border: 1px solid rgba(0, 119, 182, 0.1);
    }

    .package-item-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .package-item i {
      color: var(--primary-color);
      font-size: 18px;
    }

    .package-item-name {
      font-weight: 500;
    }

    .package-item-price {
      font-weight: 600;
      color: var(--secondary-color);
    }

    .package-item-detail {
      font-size: 12px;
      color: var(--ticket-id-color);
      margin-top: 2px;
    }

    .participant-info {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px;
      background: rgba(0, 119, 182, 0.1);
      border-radius: 10px;
      margin-bottom: 15px;
    }

    .participant-info i {
      color: var(--primary-color);
      font-size: 18px;
    }

    .package-total {
      display: flex;
      justify-content: space-between;
      margin-top: 15px;
      padding-top: 15px;
      border-top: 1px solid var(--package-border);
      font-weight: 600;
      font-size: 18px;
    }

    .package-total-value {
      color: var(--secondary-color);
    }

    /* === BAGIAN PEMBAYARAN === */
    .payment-section {
      margin-top: 20px;
      padding: 15px;
      background: var(--package-bg);
      border-radius: 15px;
      border: 1px solid var(--package-border);
    }

    .payment-title {
      font-size: 18px;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .payment-method {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .payment-method i {
      color: var(--primary-color);
    }

    .ticket-actions {
      display: flex;
      justify-content: space-between;
      margin-top: 25px;
      gap: 15px;
    }

    .btn {
      flex: 1;
      padding: 12px 20px;
      border-radius: 12px;
      border: none;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: all 0.3s ease;
      text-align: center;
      text-decoration: none;
    }

    .btn-primary {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
    }

    .btn-primary:hover {
      background: linear-gradient(135deg, #00a3c4, #0096c7);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(0, 119, 182, 0.3);
    }

    .btn-secondary {
      background: linear-gradient(135deg, #06d6a0, #2a9d8f);
      color: white;
    }

    .btn-secondary:hover {
      background: linear-gradient(135deg, #05b88a, #238a7a);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(6, 214, 160, 0.3);
    }

    /* === PESAN SUKSES === */
    .success-message {
      position: fixed;
      top: 100px;
      left: 50%;
      transform: translateX(-50%);
      background: var(--success-bg);
      color: white;
      padding: 15px 25px;
      border-radius: 12px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
      z-index: 1000;
      opacity: 0;
      transition: all 0.3s ease;
    }

    .success-message.show {
      opacity: 1;
      top: 80px;
    }

    /* === TOMBOL TEMA MENGAMBANG === */
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

    /* === ANIMASI === */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* === RESPONSIF === */
    @media (max-width: 768px) {
      /* HEADER */
      header {
        flex-direction: column;
        gap: 10px;
        padding: 12px 16px;
      }

      .left-header {
        width: 100%;
        justify-content: space-between;
      }

      .menu-toggle {
        display: flex;
      }

      .main-nav {
        display: none;
        width: 100%;
        margin-top: 10px;
      }

      .main-nav.active {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
      }

      .auth-nav {
        display: none;
      }

      .auth-nav.active {
        display: flex;
        width: 100%;
        justify-content: center;
        margin-top: 10px;
      }

      /* KONTAINER TIKET */
      .ticket-container {
        width: 95%;
        margin: 20px auto;
      }

      .ticket-header h2 {
        font-size: 24px;
      }

      .ticket-actions {
        flex-direction: column;
        gap: 10px;
      }
    }

    /* Layar sangat kecil */
    @media (max-width: 480px) {
      header h1 {
        font-size: 20px;
      }

      .ticket-header h2 {
        font-size: 22px;
      }

      .ticket-actions {
        flex-direction: column;
        gap: 10px;
      }

      .btn {
        padding: 10px 15px;
        font-size: 14px;
      }
    }

/* === CETAK === */
@media print {
  /* Reset body untuk mencetak */
  body {
    background: white !important;
    color: black !important;
    font-size: 12pt;
    padding: 0;
    margin: 0;
  }
  
  /* Sembunyikan semua elemen yang tidak perlu */
  header, .success-message, .theme-toggle-float, .bg-decoration {
    display: none !important;
  }
  
  /* Style kontainer tiket untuk cetakan */
  .ticket-container {
    box-shadow: none !important;
    border: 1px solid #ddd !important;
    max-width: 100% !important;
    width: 100% !important;
    margin: 0 !important;
    page-break-inside: avoid; /* Mencegah tiket terpotong halaman */
  }
  
  /* Sembunyikan tombol aksi */
  .ticket-actions {
    display: none !important;
  }
}
  </style>
</head>

<body>
  <!-- Dekorasi Background -->
  <div class="bg-decoration">
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
    <div class="bg-circle"></div>
  </div>

  <!-- HEADER -->
  <header>
    <div class="left-header">
      <h1><span class="white">DF</span><span class="black">Scenery</span></h1>
      
      <div class="menu-toggle" id="menuToggle">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>

    <nav class="main-nav" id="mainNav">
      <button id="btnHome" onclick="goHome()">Home</button>
      <button id="btnTickets" onclick="goTickets()">Pemesanan</button>
      <button id="btnPreview" onclick="goPreview()">Galeri</button>
      <button id="btnObyek" onclick="scrollToSection('obyek-wisata')">Obyek Wisata</button>
      <button id="btnFasilitas" onclick="scrollToSection('fasilitas-wisata')">Fasilitas Wisata</button>
      <button id="btnAbout" onclick="scrollToSection('about')">About</button>

      <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'developer', 'dev_master'])): ?>
        <button class="admin-btn" onclick="window.location.href='admin.php'">Admin Panel</button>
      <?php endif; ?>
    </nav>
    
    <nav class="auth-nav" id="authNav">
      <?php if (!isset($_SESSION['username'])): ?>
        <!-- Jika belum login -->
      <?php else: ?>
        <?php
        $fotoNama = $_SESSION['profile_pic'] ?? 'default.png';
        $foto = 'uploads/profiles/' . $fotoNama;
        ?>
        <div class="profile-area">
          <a href="profile_dfscenery.php" class="profile-wrapper">
            <img src="<?= $foto ?>" class="profile-avatar" alt="Foto Profil">
            <span class="username-text"><?= htmlspecialchars($_SESSION['username']) ?></span>
          </a>
          <a href="logout.php" class="logout-btn">Logout</a>
        </div>
      <?php endif; ?>
    </nav>
  </header>

  <!-- KONTAINER TIKET -->
  <div class="ticket-container">
    <div class="ticket-header">
      <h2>🎟 E-Ticket DF Scenery</h2>
      <p>Terima kasih telah melakukan pemesanan</p>
    </div>

    <img src="<?= htmlspecialchars($ticket_image) ?>" alt="E-Ticket DF Scenery" class="ticket-image">

    <div class="ticket-content">
      <div class="ticket-info">
        <div class="ticket-info-item">
          <span class="ticket-info-label">Nama:</span>
          <span class="ticket-info-value"><?= htmlspecialchars($name) ?></span>
        </div>
        <div class="ticket-info-item">
          <span class="ticket-info-label">Email:</span>
          <span class="ticket-info-value"><?= htmlspecialchars($email) ?></span>
        </div>
        <div class="ticket-info-item">
          <span class="ticket-info-label">Telepon:</span>
          <span class="ticket-info-value"><?= htmlspecialchars($phone) ?></span>
        </div>
        <div class="ticket-info-item">
          <span class="ticket-info-label">Jenis Tiket:</span>
          <span class="ticket-info-value"><?= htmlspecialchars($ticket_type) ?></span>
        </div>
        <div class="ticket-info-item">
          <span class="ticket-info-label">Jumlah:</span>
          <span class="ticket-info-value"><?= htmlspecialchars($quantity) ?></span>
        </div>
        <div class="ticket-info-item">
          <span class="ticket-info-label">Tanggal Perjalanan:</span>
          <span class="ticket-info-value"><?= htmlspecialchars($formatted_date) ?></span>
        </div>
        <div class="ticket-info-item">
          <span class="ticket-info-label">Lama Perjalanan:</span>
          <span class="ticket-info-value"><?= htmlspecialchars($travel_days) ?> hari</span>
        </div>
        <div class="ticket-info-item">
          <span class="ticket-info-label">Harga Tiket:</span>
          <span class="ticket-info-value">Rp <?= number_format($ticket_price, 0, ',', '.') ?></span>
        </div>
      </div>

      <!-- BAGIAN PAKET PERJALANAN -->
      <?php if ($accommodation || $transportation || $food): ?>
      <div class="package-section">
        <div class="package-title">
          <i class="fas fa-suitcase"></i>
          Paket Perjalanan
        </div>
        
        <div class="participant-info">
          <i class="fas fa-users"></i>
          <div>
            <div>Jumlah Peserta: <strong><?= htmlspecialchars($participant_count) ?></strong> orang</div>
            <div class="package-item-detail">Harga per layanan dikalikan dengan jumlah peserta</div>
          </div>
        </div>
        
        <div class="package-details">
          <?php if ($accommodation): ?>
          <div class="package-item">
            <div class="package-item-left">
              <i class="fas fa-bed"></i>
              <div>
                <div class="package-item-name">Penginapan</div>
                <div class="package-item-detail">Rp. 100.000 × <?= $participant_count ?> orang</div>
              </div>
            </div>
            <div class="package-item-price">Rp <?= number_format($accommodation_price, 0, ',', '.') ?></div>
          </div>
          <?php endif; ?>
          
          <?php if ($transportation): ?>
          <div class="package-item">
            <div class="package-item-left">
              <i class="fas fa-bus"></i>
              <div>
                <div class="package-item-name">Transportasi</div>
                <div class="package-item-detail">Rp. 120.000 × <?= $participant_count ?> orang</div>
              </div>
            </div>
            <div class="package-item-price">Rp <?= number_format($transportation_price, 0, ',', '.') ?></div>
          </div>
          <?php endif; ?>
          
          <?php if ($food): ?>
          <div class="package-item">
            <div class="package-item-left">
              <i class="fas fa-utensils"></i>
              <div>
                <div class="package-item-name">Makanan</div>
                <div class="package-item-detail">Rp. 65.000 × <?= $participant_count ?> orang</div>
              </div>
            </div>
            <div class="package-item-price">Rp <?= number_format($food_price, 0, ',', '.') ?></div>
          </div>
          <?php endif; ?>
        </div>
        
        <div class="package-total">
          <span>Total Paket:</span>
          <span class="package-total-value">Rp <?= number_format($package_total, 0, ',', '.') ?></span>
        </div>
      </div>
      <?php endif; ?>

      <!-- BAGIAN PEMBAYARAN -->
      <div class="payment-section">
        <div class="payment-title">
          <i class="fas fa-credit-card"></i>
          Metode Pembayaran
        </div>
        <div class="payment-method">
          <i class="fas fa-money-check"></i>
          <span><?= htmlspecialchars($payment_method) ?></span>
        </div>
      </div>

      <div class="ticket-info-item" style="margin-top: 20px; font-weight: 600; font-size: 18px;">
        <span class="ticket-info-label">Total Pembayaran:</span>
        <span class="ticket-info-value">Rp <?= number_format($total_price, 0, ',', '.') ?></span>
      </div>

      <div class="ticket-id">ID Pesanan: #<?= htmlspecialchars($id) ?></div>

      <div class="ticket-actions">
        <a href="dfscenery.php" class="btn btn-primary">
          <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
        <button class="btn btn-secondary" onclick="window.print()">
          <i class="fas fa-print"></i> Cetak Tiket
        </button>
      </div>
    </div>
  </div>

  <!-- PESAN SUKSES -->
  <div class="success-message" id="successMessage">
    <h3>Pemesanan Berhasil!</h3>
    <p>Tiket Anda telah berhasil dipesan. Silakan simpan e-ticket ini sebagai bukti pemesanan.</p>
  </div>

  <!-- Tombol Toggle Tema Mengambang -->
  <div class="theme-toggle-float">
    <button id="theme-toggle-btn" aria-label="Toggle theme">
      <i class="fas fa-sun" id="theme-icon-light"></i>
      <i class="fas fa-moon" id="theme-icon-dark" style="display: none;"></i>
    </button>
  </div>

  <script>
    // Toggle menu mobile
    document.getElementById('menuToggle').addEventListener('click', function() {
      document.getElementById('mainNav').classList.toggle('active');
      document.getElementById('authNav').classList.toggle('active');
    });

    // Tampilkan pesan sukses
    window.addEventListener('load', function() {
      const successMessage = document.getElementById('successMessage');
      setTimeout(() => {
        successMessage.classList.add('show');
        setTimeout(() => {
          successMessage.classList.remove('show');
        }, 3000);
      }, 500);
    });

    // Fungsi Navigasi
    function goHome() {
      window.location.href = "dfscenery.php";
    }

    function goTickets() {
      window.location.href = "tickets.php";
    }

    function goPreview() {
      window.location.href = "preview.php";
    }

    function scrollToSection(sectionId) {
      // Jika berada di halaman dfscenery.php, pindah ke preview.php
      if (window.location.pathname.includes("dfscenery.php")) {
        window.location.href = "preview.php#" + sectionId;
      }
    }

    // Atur navigasi aktif
    function setActiveNav() {
      const page = window.location.pathname.split("/").pop();
      
      document.querySelectorAll(".main-nav button").forEach(btn => {
        btn.classList.remove("active");
      });
      
      if (page === "dfscenery.php" || page === "") {
        document.getElementById("btnHome").classList.add("active");
      } else if (page === "tickets.php") {
        document.getElementById("btnTickets").classList.add("active");
      } else if (page === "preview.php") {
        document.getElementById("btnPreview").classList.add("active");
      }
    }

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

    // Inisialisasi
    setActiveNav();
  </script>
</body>
</html>