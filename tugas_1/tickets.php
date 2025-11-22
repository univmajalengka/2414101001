<?php
session_start();
include 'db.php';

// === MENGAMBIL DATA PROMO DARI DATABASE ===
 $promoData = [];
 $res = $conn->query("SELECT ticket_type, price, discount, description, valid_from, valid_to 
                   FROM promo_ticket");

while ($row = $res->fetch_assoc()) {
    $promoData[$row['ticket_type']] = $row;
}

// === MENGAMBIL DATA USER DARI DATABASE ===
 $userData = [];
if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $stmt = $conn->prepare("SELECT email, phone FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result && $result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        $_SESSION['email'] = $userData['email'];
        $_SESSION['phone'] = $userData['phone'];
    }
}

// === PROSES CHECKOUT ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $type = $_POST['ticket_type'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 0);
    $total_price = floatval($_POST['total_price'] ?? 0);

    if (!$name || !$email || !$type || $quantity <= 0 || $total_price <= 0) {
        echo json_encode(["success" => false, "message" => "❌ Data tidak lengkap atau tidak valid."]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO tickets (name, email, phone, ticket_type, quantity, total_price)
                            VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssid", $name, $email, $phone, $type, $quantity, $total_price);

    if ($stmt->execute()) {
        $last_id = $conn->insert_id;
        echo json_encode([
            "success" => true,
            "message" => "✅ Pembelian tiket berhasil disimpan!",
            "id" => $last_id
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "❌ Gagal menyimpan: " . $conn->error]);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Tiket - DF Scenery</title>
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
  --table-hover-bg: #f4fbff;
  --input-bg: #ffffff;
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
  --table-hover-bg: #2a2d3a;
  --input-bg: #495057;
}

body {
  margin: 0;
  font-family: "Poppins", Arial, sans-serif;
  overflow-x: hidden;
  background: var(--bg-color);
  color: var(--text-color);
  transition: background 0.3s ease, color 0.3s ease;
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
  transition: all 0.3s ease;
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

/* === LATAR BELAKANG HALAMAN TIKET === */
body.tickets-page {
  background: url('20241013_165251.jpg') no-repeat center center fixed;
  background-size: cover;
  color: white;
  min-height: 100vh;
  padding-top: 80px;
  position: relative;
}

body.tickets-page::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0,0,0,0.45);
  z-index: 0;
  pointer-events: none;
}

.ticket-section, .cart-section {
  position: relative;
  z-index: 1;
}

/* === BAGIAN PEMILIHAN TIKET === */
.ticket-section {
  text-align: center;
  padding: 40px 20px;
}

.ticket-section h2 {
  color: #fff;
  margin-bottom: 40px;
  font-size: 32px;
  font-weight: 700;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
  position: relative;
  display: inline-block;
}

.ticket-section h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
  border-radius: 2px;
}

.ticket-container {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.ticket-card {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 25px;
  text-align: center;
  color: var(--text-color);
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
  position: relative;
  z-index: 1;
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-color);
  overflow: hidden;
}

.ticket-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 5px;
  background: linear-gradient(90deg, var(--primary-color), var(--secondary-color), var(--accent-color));
}

.ticket-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.ticket-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  border-radius: 15px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
  margin-bottom: 20px;
  transition: transform 0.3s ease;
}

.ticket-card:hover img {
  transform: scale(1.05);
}

.ticket-card h3 {
  margin-top: 15px;
  font-size: 22px;
  font-weight: 700;
  color: var(--primary-color);
}

.ticket-date {
  color: var(--primary-color);
  font-size: 14px;
  margin-bottom: 8px;
  font-weight: 600;
}

.ticket-desc {
  font-size: 14px;
  color: var(--text-color);
  margin-bottom: 20px;
  min-height: 40px;
  opacity: 0.8;
}

.ticket-card button {
  padding: 12px 25px;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  border: none;
  color: white;
  font-weight: bold;
  border-radius: 20px;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 4px 10px rgba(0, 119, 182, 0.3);
  position: relative;
  overflow: hidden;
}

.ticket-card button::before {
  content: '';
  position: absolute;
  top: 0;
  left: -100%;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  transition: 0.5s;
}

.ticket-card button:hover::before {
  left: 100%;
}

.ticket-card button:hover {
  background: linear-gradient(135deg, #00a3c4, #0096c7);
  transform: translateY(-3px);
  box-shadow: 0 6px 15px rgba(0, 119, 182, 0.4);
}

/* === BAGIAN KERANJANG === */
.cart-section {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 30px;
  width: 90%;
  max-width: 1000px;
  color: var(--text-color);
  margin: 60px auto 100px auto;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-color);
  position: relative;
}

.cart-section h2 {
  color: var(--primary-color);
  margin-bottom: 25px;
  text-align: center;
  font-size: 24px;
  font-weight: 700;
  position: relative;
  display: inline-block;
  width: 100%;
}

.cart-section h2::after {
  content: '';
  position: absolute;
  bottom: -10px;
  left: 50%;
  transform: translateX(-50%);
  width: 80px;
  height: 4px;
  background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
  border-radius: 2px;
}

table {
  width: 100%;
  border-collapse: collapse;
  background: var(--card-bg);
  border-radius: 15px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.08);
  margin-bottom: 20px;
}

table th, table td {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border-color);
  text-align: center;
}

table th {
  background-color: var(--primary-color);
  color: white;
  font-weight: 600;
}

tr:hover td {
  background: var(--table-hover-bg);
  transition: 0.2s;
}

.btn {
  background-color: var(--primary-color);
  color: white;
  border: none;
  padding: 8px 15px;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.3s;
  font-weight: 600;
}

.btn:hover {
  background-color: #005f87;
  transform: translateY(-2px);
}

.btn-delete {
  background: linear-gradient(135deg, #ff4757, #ff3838);
  color: white;
  border: none;
  border-radius: 8px;
  padding: 6px 12px;
  cursor: pointer;
  font-weight: 600;
  transition: 0.3s;
}

.btn-delete:hover { 
  background: linear-gradient(135deg, #ff3838, #d63031);
  transform: translateY(-2px);
}

.total-box {
  text-align: right;
  margin-top: 20px;
  font-size: 18px;
  font-weight: 600;
  padding: 15px;
  background: rgba(0, 119, 182, 0.1);
  border-radius: 10px;
  border: 1px solid rgba(0, 119, 182, 0.2);
}

/* === BAGIAN METODE PEMBAYARAN === */
.payment-section {
  margin-top: 30px;
  background: var(--card-bg);
  padding: 25px 28px;
  border-radius: 20px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  border: 1px solid var(--border-color);
  backdrop-filter: blur(10px);
}

.payment-section h3 {
  margin-bottom: 20px;
  font-size: 22px;
  font-weight: 700;
  color: var(--primary-color);
  position: relative;
  display: inline-block;
}

.payment-section h3::after {
  content: '';
  position: absolute;
  bottom: -8px;
  left: 0;
  width: 60px;
  height: 3px;
  background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
  border-radius: 2px;
}

/* GRID OPSI PEMBAYARAN */
.payment-options {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 18px;
}

/* KARTU OPSI PEMBAYARAN */
.pay-option {
  display: flex;
  align-items: center;
  justify-content: flex-start;
  gap: 15px;
  background: linear-gradient(135deg, var(--card-bg), #f3f6fa);
  padding: 16px 20px;
  border-radius: 18px;
  cursor: pointer;
  border: 2px solid transparent;
  transition: all 0.25s ease-in-out;
  position: relative;
  box-shadow: 0 5px 15px rgba(0,0,0,0.08);
}

/* Logo */
.pay-option img {
  width: 45px;
  height: 45px;
  object-fit: contain;
  filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));
}

/* Radio button */
.pay-option input {
  transform: scale(1.35);
  cursor: pointer;
  accent-color: var(--primary-color);
}

/* Efek hover */
.pay-option:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 22px rgba(0,0,0,0.12);
  background: linear-gradient(135deg, #eef7ff, var(--card-bg));
}

/* Saat aktif dipilih */
.pay-option input:checked + img,
.pay-option input:checked ~ img { 
  transform: scale(1.12);
}

.pay-option input:checked ~ * {
  font-weight: 600;
}

/* Kartu yang dipilih = Border biru tebal + shadow */
.pay-option:has(input:checked) {
  border-color: var(--primary-color);
  box-shadow: 0 10px 25px rgba(0,153,255,0.25);
  background: linear-gradient(135deg, #e6f4ff, var(--card-bg));
  transform: translateY(-5px);
}

/* Label text */
.pay-option span {
  font-size: 17px;
  font-weight: 600;
  color: var(--text-color);
}

/* === POPUP NOTIFIKASI === */
.popup {
  position: fixed;
  top: 20px;
  right: 20px;
  background: linear-gradient(135deg, #28a745, #20c997);
  color: white;
  padding: 15px 25px;
  border-radius: 10px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  opacity: 0;
  transform: translateY(-20px);
  transition: all 0.4s ease;
  z-index: 2000;
  pointer-events: none;
  backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.3);
}

.popup.show {
  opacity: 1;
  transform: translateY(0);
}

/* === KERANJANG MENGAMBANG (Mobile) === */
.floating-cart {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
  cursor: pointer;
  z-index: 1000;
  transition: all 0.3s ease;
}

.floating-cart:hover {
  transform: scale(1.1);
  box-shadow: 0 8px 20px rgba(0, 119, 182, 0.4);
}

.floating-cart i {
  font-size: 24px;
}

.cart-count {
  position: absolute;
  top: 0;
  right: 0;
  background: #ff4757;
  color: white;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: bold;
}

/* === TOMBOL TEMA MENGAMBANG === */
.theme-toggle-float {
  position: fixed;
  bottom: 90px; /* Di atas keranjang */
  right: 20px;
  z-index: 9999;
}

#theme-toggle-btn {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  background: var(--primary-color);
  color: white;
  border: none;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 20px;
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

.fade-in {
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease;
}

.fade-in.visible {
  opacity: 1;
  transform: translateY(0);
}

/* =======================
   RESPONSIF (MOBILE)
   ======================= */
@media (max-width: 992px) {
  .ticket-container {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
  }
}

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

  /* BAGIAN TIKET */
  .ticket-section {
    padding: 30px 15px;
  }

  .ticket-section h2 {
    font-size: 28px;
  }

  .ticket-card {
    margin-bottom: 20px;
  }

  /* BAGIAN KERANJANG */
  .cart-section {
    width: 95%;
    padding: 20px;
    margin-bottom: 100px;
  }

  table th, table td {
    padding: 10px 8px;
    font-size: 14px;
  }

  /* BAGIAN PEMBAYARAN */
  .payment-options {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 12px;
  }

  .pay-option {
    padding: 12px 15px;
  }

  .pay-option img {
    width: 35px;
    height: 35px;
  }

  .pay-option span {
    font-size: 14px;
  }

  /* KERANJANG MENGAMBANG */
  .floating-cart {
    display: flex;
  }
}

/* Layar sangat kecil */
@media (max-width: 480px) {
  header h1 {
    font-size: 20px;
  }

  .ticket-section h2 {
    font-size: 24px;
  }

  .ticket-card {
    padding: 20px;
  }

  .ticket-card h3 {
    font-size: 20px;
  }

  .cart-section {
    width: 98%;
    padding: 15px;
  }

  .payment-section {
    padding: 15px;
  }

  .pay-option span {
    font-size: 13px;
  }
}
</style>
</head>

<body class="tickets-page">

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
    <button id="btnTickets" onclick="goTickets()" class="active">Pemesanan</button>
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
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
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

<!-- Data user untuk JavaScript -->
<input type="hidden" id="userName" value="<?= $_SESSION['username'] ?? '' ?>">
<input type="hidden" id="userEmail" value="<?= $userData['email'] ?? '' ?>">
<input type="hidden" id="userPhone" value="<?= $userData['phone'] ?? '' ?>">

<!-- BAGIAN PEMILIHAN TIKET -->
<section class="ticket-section fade-in">
  <h2>Pilih Tiket Anda</h2>

  <div class="ticket-container">
    <?php
    $imageMap = [
        "Family" => "20241013_162450.jpg",
        "Single" => "20241013_164202.jpg",
        "Double" => "20241013_165251.jpg"
    ];
    ?>
    <?php foreach ($promoData as $type => $p): ?>
      <div class="ticket-card fade-in">
        <img src="<?= $imageMap[$type] ?? 'default_ticket.jpg' ?>" alt="<?= $type ?> Ticket">
        <h3><?= $type ?> Ticket</h3>
        <p class="ticket-date">
          Berlaku:
          <?= !empty($p['valid_from']) ? date("d M Y", strtotime($p['valid_from'])) : "-" ?>
          –
          <?= !empty($p['valid_to']) ? date("d M Y", strtotime($p['valid_to'])) : "-" ?>
        </p>
        <p class="ticket-desc"><?= $p['description'] ?></p>
        <button onclick="addToCart('<?= $type ?>')">Tambah ke Keranjang</button>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- BAGIAN KERANJANG -->
<div class="cart-section fade-in">
  <h2>🛒 Keranjang Tiket</h2>

  <table id="cartTable">
    <thead>
      <tr>
        <th>Tipe Tiket</th>
        <th>Harga</th>
        <th>Diskon</th>
        <th>Jumlah</th>
        <th>Subtotal</th>
        <th>Aksi</th>
      </tr>
    </thead>

    <tbody id="cartBody">
      <tr><td colspan="6">Belum ada tiket di keranjang.</td></tr>
    </tbody>
  </table>

  <div class="total-box">Total: <b id="grandTotal">Rp 0</b></div>

  <!-- BAGIAN METODE PEMBAYARAN -->
  <div class="payment-section fade-in">
    <h3>Pilih Metode Pembayaran</h3>

    <div class="payment-options">
      <label class="pay-option">
        <input type="radio" name="payment" value="Gopay">
        <img src="gopay.png" alt="Gopay">
        <span>Gopay</span>
      </label>

      <label class="pay-option">
        <input type="radio" name="payment" value="OVO">
        <img src="ovo.png" alt="OVO">
        <span>OVO</span>
      </label>

      <label class="pay-option">
        <input type="radio" name="payment" value="Dana">
        <img src="dana.png" alt="Dana">
        <span>Dana</span>
      </label>

      <label class="pay-option">
        <input type="radio" name="payment" value="Transfer Bank">
        <span style="font-size: 40px;">💳</span>
        <span>Transfer Bank</span>
      </label>
    </div>
  </div>

  <div style="text-align:right; margin-top:20px;">
    <button class="btn" onclick="checkout()">Checkout Sekarang</button>
  </div>
</div>

<!-- KERANJANG MENGAMBANG (Mobile) -->
<div class="floating-cart" id="floatingCart" style="display: none;">
  <i class="fas fa-shopping-cart"></i>
  <span class="cart-count" id="cartCount">0</span>
</div>

<!-- POPUP -->
<div id="popup" class="popup">Tiket berhasil ditambahkan ke keranjang ✅</div>

<!-- Tombol Tema Mengambang -->
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

let cart = [];

const PRICES = <?= json_encode(array_column($promoData, 'price', 'ticket_type')); ?>;
const DISCOUNT = <?= json_encode(array_column($promoData, 'discount', 'ticket_type')); ?>;

// Fungsi untuk menambah item ke keranjang
function addToCart(type) {
  const existing = cart.find(item => item.type === type);

  if (existing) {
    existing.quantity++;
  } else {
    cart.push({
      type,
      price: PRICES[type],
      discount: DISCOUNT[type],
      quantity: 1
    });
  }

  showPopup();
  renderCart();
  updateFloatingCart();
}

// Fungsi untuk menampilkan keranjang
function renderCart() {
  const body = document.getElementById("cartBody");
  body.innerHTML = "";

  if (cart.length === 0) {
    body.innerHTML = `<tr><td colspan="6">Belum ada tiket di keranjang.</td></tr>`;
    document.getElementById("grandTotal").innerText = "Rp 0";
    return;
  }

  let total = 0;

  cart.forEach((item, i) => {
    const diskonNominal = item.price * item.discount;
    const hargaFinal = item.price - diskonNominal;
    const subtotal = hargaFinal * item.quantity;

    total += subtotal;

    body.innerHTML += `
      <tr>
        <td>${item.type}</td>
        <td>Rp ${item.price.toLocaleString()}</td>
        <td>${item.discount * 100}%</td>
        <td>${item.quantity}</td>
        <td>Rp ${subtotal.toLocaleString()}</td>
        <td><button class="btn-delete" onclick="removeItem(${i})">Hapus</button></td>
      </tr>
    `;
  });

  document.getElementById("grandTotal").innerText = "Rp " + total.toLocaleString();
}

// Fungsi untuk menghapus item dari keranjang
function removeItem(i) {
  cart.splice(i, 1);
  renderCart();
  updateFloatingCart();
}

// Fungsi untuk memperbarui tampilan keranjang mengambang di mobile
function updateFloatingCart() {
  const floatingCart = document.getElementById('floatingCart');
  const cartCount = document.getElementById('cartCount');
  
  if (window.innerWidth <= 768) {
    floatingCart.style.display = 'flex';
    
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    cartCount.textContent = totalItems;
  } else {
    floatingCart.style.display = 'none';
  }
}

// Fungsi untuk menampilkan popup notifikasi
function showPopup() {
  const pop = document.getElementById("popup");
  pop.classList.add("show");
  setTimeout(() => pop.classList.remove("show"), 1600);
}

// Fungsi untuk proses checkout
function checkout() {
  if (cart.length === 0) {
    alert("Keranjang masih kosong!");
    return;
  }

  // CEK METODE PEMBAYARAN
  const selectedPayment = document.querySelector('input[name="payment"]:checked');
  if (!selectedPayment) {
    alert("Pilih metode pembayaran terlebih dahulu!");
    return;
  }

  const paymentMethod = selectedPayment.value;

  const first = cart[0];
  const total = cart.reduce((sum, item) => {
    const hargaFinal = item.price - item.price * item.discount;
    return sum + (hargaFinal * item.quantity);
  }, 0);

  // Ambil data user dari input hidden
  const userName = document.getElementById('userName').value;
  const userEmail = document.getElementById('userEmail').value;
  const userPhone = document.getElementById('userPhone').value;

  const formData = new FormData();
  formData.append("name", userName || "Pembeli");
  formData.append("email", userEmail || "email@example.com");
  formData.append("phone", userPhone || "0000000000");
  formData.append("ticket_type", first.type);
  formData.append("quantity", first.quantity);
  formData.append("total_price", total);
  formData.append("payment_method", paymentMethod);

  fetch("", { method: "POST", body: formData })
    .then(res => res.json())
    .then(data => {
      alert("Metode pembayaran: " + paymentMethod + "\n" + data.message);

      if (data.success) {
        window.location.href = "ticket_success.php?id=" + data.id;
      }
    })
    .catch(err => {
      alert("Terjadi kesalahan saat checkout!");
      console.error(err);
    });
}

// Fungsi navigasi
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
  // Jika berada di halaman tickets.php, pindah ke dfscenery.php
  if (window.location.pathname.includes("tickets.php")) {
    window.location.href = "dfscenery.php#" + sectionId;
  }
}

// Animasi scroll
window.addEventListener('scroll', function() {
  const fadeElements = document.querySelectorAll('.fade-in');
  fadeElements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const elementBottom = element.getBoundingClientRect().bottom;
    
    if (elementTop < window.innerHeight && elementBottom > 0) {
      element.classList.add('visible');
    }
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


// Event listener untuk resize window
window.addEventListener('resize', updateFloatingCart);

// Inisialisasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
  updateFloatingCart();
  
  // Trigger animasi fade in untuk elemen di atas liputan (above the fold)
  const fadeElements = document.querySelectorAll('.fade-in');
  fadeElements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const elementBottom = element.getBoundingClientRect().bottom;
    
    if (elementTop < window.innerHeight && elementBottom > 0) {
      element.classList.add('visible');
    }
  });
});

// Set active navigation
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

setActiveNav();
</script>

</body>
</html>