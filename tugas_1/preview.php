<?php
session_start();
include "db.php";

// MENGAMBIL DATA KOMENTAR DARI DATABASE
// PAGINASI KOMENTAR
 $limit = 5; // 5 komentar per halaman
 $page  = isset($_GET['cp']) ? (int)$_GET['cp'] : 1;
if ($page < 1) $page = 1;

 $offset = ($page - 1) * $limit;

 $totalCommentRes  = $conn->query("SELECT COUNT(*) AS total FROM comments");
 $totalCommentRows = $totalCommentRes->fetch_assoc()['total'];
 $totalCommentPages = max(1, ceil($totalCommentRows / $limit));

// Ambil komentar dengan LIMIT
 $comments = $conn->query("
 SELECT c.*, u.username, u.profile_pic, u.role 
 FROM comments c
 LEFT JOIN users u ON c.user_id = u.id
 ORDER BY c.id DESC
 LIMIT $limit OFFSET $offset
");

// KETIKA USER MENULIS KOMENTAR
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $rating  = intval($_POST['rating']);
    $comment = trim($_POST['comment']);

    if ($rating >= 1 && $rating <= 5 && $comment !== '') {
        $stmt = $conn->prepare("INSERT INTO comments (user_id, rating, comment) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $user_id, $rating, $comment);
        $stmt->execute();
        header("Location: preview.php");
        exit;
    }
}

// HAPUS KOMENTAR (UNTUK ADMIN, DEVELOPER, DEV_MASTER)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_comment']) && isset($_SESSION['user_id'])) {
    $comment_id = intval($_POST['delete_comment']);
    $user_id = $_SESSION['user_id'];
    
    // Periksa apakah user adalah admin, developer, atau dev_master
    $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (in_array($user['role'], ['admin', 'developer', 'dev_master'])) {
        $stmt = $conn->prepare("DELETE FROM comments WHERE id = ?");
        $stmt->bind_param("i", $comment_id);
        $stmt->execute();
        
        echo json_encode([
            "success" => true,
            "message" => "✅ Komentar berhasil dihapus."
        ]);
        exit;
    } else {
        echo json_encode([
            "success" => false,
            "message" => "❌ Anda tidak memiliki izin untuk menghapus komentar."
        ]);
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pratinjau - DFScenery</title>
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
  --section-bg-1: linear-gradient(135deg, #eef7ff, #e6f3ff);
  --section-bg-2: linear-gradient(135deg, #e6f3ff, #f4faff);
  --input-bg: #ffffff;
  --comment-bg: rgba(246, 250, 255, 0.9);
  --text-muted: #555;
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
  --section-bg-1: linear-gradient(135deg, #212529, #343a40);
  --section-bg-2: linear-gradient(135deg, #2c3e50, #34495e);
  --input-bg: #495057;
  --comment-bg: rgba(60, 60, 75, 0.9);
  --text-muted: #b0b0b0;
}

body {
  font-family: 'Poppins', sans-serif;
  background: var(--bg-color);
  color: var(--text-color);
  overflow-x: hidden;
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

.left-header {
  display: flex;
  align-items: center;
  gap: 15px;
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

nav button.active {
  background: rgba(255,255,255,0.9) !important;
  color: var(--primary-color) !important;
  font-weight: 700;
  padding-bottom: 5px;
}

/* === Tautan Masuk & Daftar di Header === */
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

/* === GALERI === */
.preview-gallery {
  position: relative;
  width: 100%;
  height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  margin-top: 20px;
}

.preview-gallery h2 {
  position: absolute;
  top: 80px;
  left: 50%;
  transform: translateX(-50%);
  font-size: 32px;
  color: rgba(0, 119, 182, 0.8);
  font-weight: 700;
  z-index: 10;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.scroll-wrapper {
  display: flex;
  gap: 30px;
  overflow-x: auto;
  scroll-snap-type: x mandatory;
  padding: 100px 80px;
  scrollbar-width: none;
}
.scroll-wrapper::-webkit-scrollbar { display: none; }

.scroll-item {
  flex: 0 0 auto;
  scroll-snap-align: center;
}
.scroll-item img {
  width: 500px;
  height: 320px;
  border-radius: 18px;
  object-fit: cover;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  transition: 0.3s;
}
.scroll-item:hover img {
  transform: scale(1.05);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
}

/* === TOMBOL KIRI/KANAN === */
.scroll-btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  background: rgba(0, 119, 182, 0.8);
  color: white;
  border: none;
  border-radius: 50%;
  width: 55px;
  height: 55px;
  font-size: 30px;
  cursor: pointer;
  transition: 0.3s;
  z-index: 11;
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.3);
}
.scroll-btn:hover {
  background: rgba(0, 163, 196, 0.9);
  transform: translateY(-50%) scale(1.1);
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
.scroll-btn.left { left: 20px; }
.scroll-btn.right { right: 20px; }

/* === GAYA SECTION === */
section {
  position: relative;
  z-index: 1;
}

.section-header {
  text-align: center;
  margin-bottom: 50px;
  position: relative;
}

.section-header h2 {
  font-size: 32px;
  color: var(--primary-color);
  margin-bottom: 15px;
  font-weight: 700;
  position: relative;
  display: inline-block;
}

.section-header h2::after {
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

.section-header p {
  font-size: 16px;
  color: var(--text-muted);
  max-width: 700px;
  margin: 0 auto;
}

/* === OBJEK WISATA === */
.tourist-spots {
  width: 100%;
  padding: 80px 20px;
  background: var(--section-bg-1);
}

.tourist-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 25px;
  max-width: 1200px;
  margin: auto;
}

.spot-card {
  background: var(--card-bg);
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 8px 20px rgba(0,0,0,0.12);
  transition: 0.3s;
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-color);
}

.spot-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 25px rgba(0,0,0,0.18);
}

.spot-card img {
  width: 100%;
  height: 180px;
  object-fit: cover;
  transition: 0.3s;
}

.spot-card:hover img {
  transform: scale(1.05);
}

.spot-card-content {
  padding: 20px;
  text-align: center;
}

.spot-card h3 {
  font-size: 20px;
  margin: 0 0 8px;
  color: var(--primary-color);
}

.spot-card p {
  font-size: 14px;
  color: var(--text-muted);
  margin: 0;
}

/* === FASILITAS WISATA === */
.tourist-facility {
  width: 100%;
  display: flex;
  justify-content: center;
  padding: 80px 20px;
  background: var(--section-bg-2);
}

.facility-card {
  position: relative;
  width: 900px;
  max-width: 95%;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  transition: transform 0.3s;
}

.facility-card img {
  width: 100%;
  height: 500px;
  object-fit: cover;
  display: block;
  transition: transform 0.5s ease;
}

.facility-card:hover img {
  transform: scale(1.05);
}

.facility-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 30px;
  background: linear-gradient(to top, rgba(0,119,182,0.9), rgba(0,119,182,0));
  color: white;
  backdrop-filter: blur(5px);
}

.facility-overlay h2 {
  margin: 0 0 10px;
  font-size: 32px;
  font-weight: 700;
}

.facility-overlay p {
  margin: 0;
  font-size: 18px;
  opacity: 0.9;
}

/* === KOMENTAR === */
.comment-section {
  width: 100%;
  max-width: 900px;
  background: var(--card-bg);
  padding: 30px;
  border-radius: 20px;
  margin: 80px auto;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-color);
}

.comment-section h3 {
  font-size: 26px;
  color: var(--primary-color);
  margin-bottom: 20px;
  font-weight: 700;
  text-align: center;
  position: relative;
  display: inline-block;
  width: 100%;
}

.comment-section h3::after {
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

.comment-box {
  margin-bottom: 25px;
}

.comment-box textarea {
  width: 100%;
  padding: 15px;
  border-radius: 12px;
  border: 1px solid #ccc;
  background: var(--input-bg);
  color: var(--text-color);
  resize: none;
  font-size: 16px;
  transition: 0.2s;
  outline: none;
}

.comment-box textarea:focus {
  border-color: var(--primary-color);
  box-shadow: 0 0 6px rgba(0, 166, 251, 0.4);
}

.rating-stars {
  display: flex;
  gap: 5px;
  margin-bottom: 15px;
}
.rating-stars input { display: none; }

.rating-stars label {
  font-size: 28px;
  cursor: pointer;
  color: #ccc;
  transition: 0.2s;
}

.rating-stars input:checked ~ label,
.rating-stars label:hover,
.rating-stars label:hover ~ label {
  color: gold;
}

.comment-box button {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  padding: 12px 20px;
  border: none;
  border-radius: 12px;
  font-weight: bold;
  cursor: pointer;
  transition: 0.3s;
  box-shadow: 0 4px 10px rgba(0,119,182,0.3);
}

.comment-box button:hover {
  background: linear-gradient(135deg, #00a3c4, #0096c7);
  transform: translateY(-2px);
  box-shadow: 0 6px 15px rgba(0,119,182,0.4);
}

.comment {
  display: flex;
  gap: 15px;
  background: var(--comment-bg);
  padding: 20px;
  border-radius: 12px;
  margin-top: 15px;
  backdrop-filter: blur(5px);
  border: 1px solid var(--border-color);
  transition: 0.3s;
}

.comment:hover {
  transform: translateY(-2px);
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.comment img {
  width: 50px;
  height: 50px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid var(--primary-color);
}

.comment-content {
  flex: 1;
}

.comment-header {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 5px;
}

.nametag {
  font-size: 10px;
  padding: 2px 6px;
  border-radius: 4px;
  color: white;
  text-transform: uppercase;
}
.nametag.admin { background: #06d6a0; }
.nametag.user { background: var(--primary-color); }
.nametag.developer { background: #ff006e; }
.nametag.dev_master { background: #7b2cbf; }

.comment small {
  color: var(--text-muted);
  margin-left: auto;
}

.comment p { 
  margin: 6px 0 0; 
  line-height: 1.5;
  color: var(--text-color);
}

.comment .rating {
  color: gold;
  font-size: 18px;
  margin-bottom: 5px;
}

.comment-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 5px;
}

.delete-comment-btn {
  background: rgba(255, 0, 0, 0.7);
  color: white;
  border: none;
  border-radius: 4px;
  padding: 4px 8px;
  font-size: 12px;
  cursor: pointer;
  transition: all 0.2s;
}

.delete-comment-btn:hover {
  background: rgba(255, 0, 0, 0.9);
}

/* === PAGINASI === */
.pagination {
  text-align: center;
  margin-top: 30px;
}

.page-btn {
  display: inline-block;
  margin: 0 4px;
  padding: 10px 15px;
  background: var(--primary-color);
  color: white;
  border-radius: 8px;
  text-decoration: none;
  font-weight: 600;
  transition: 0.3s;
}

.page-btn:hover {
  background: #005f87;
  transform: translateY(-2px);
}

.pagination .active {
  background: var(--secondary-color);
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

.scroll-animate {
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.7s ease;
}

.scroll-animate.show {
  opacity: 1;
  transform: translateY(0);
}

/* =======================
   RESPONSIF (MOBILE)
   ======================= */
@media (max-width: 992px) {
  .tourist-grid {
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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

  /* GALLERY */
  .preview-gallery h2 {
    font-size: 24px;
    top: 60px;
  }
  
  .scroll-item img {
    width: 300px;
    height: 200px;
  }
  
  .scroll-wrapper {
    padding: 80px 40px;
  }
  
  /* COMMENT SECTION */
  .comment-section {
    width: 95%;
    padding: 20px;
  }
  
  /* FACILITY */
  .facility-card img {
    height: 300px;
  }
}

/* Extra small screens */
@media (max-width: 480px) {
  header h1 {
    font-size: 20px;
  }

  .preview-gallery h2 {
    font-size: 20px;
  }
  
  .scroll-item img {
    width: 250px;
    height: 160px;
  }
  
  .facility-overlay h2 {
    font-size: 24px;
  }
}
</style>
</head>

<body class="preview-page">

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
    <button id="btnPreview" onclick="goPreview()" class="active">Galeri</button>
    <button id="btnObyek" onclick="scrollToSection('obyek-wisata')">Obyek Wisata</button>
    <button id="btnFasilitas" onclick="scrollToSection('fasilitas-wisata')">Fasilitas Wisata</button>
    <button id="btnAbout" onclick="goToAbout()">About</button>

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

<!-- GALLERY -->
<section class="preview-gallery">
  <h2>Galeri DFScenery</h2>

  <button class="scroll-btn left" id="scrollLeft">❮</button>
  <div class="scroll-wrapper" id="scrollGallery">
    <div class="scroll-item"><img src="20241013_203950.jpg" alt=""></div>
    <div class="scroll-item"><img src="20241013_162450.jpg" alt=""></div>
    <div class="scroll-item"><img src="20241013_165251.jpg" alt=""></div>
    <div class="scroll-item"><img src="20241013_164223.jpg" alt=""></div>
    <div class="scroll-item"><img src="20241013_162340.jpg" alt=""></div>
    <div class="scroll-item"><img src="20241013_161500.jpg" alt=""></div>
  </div>
  <button class="scroll-btn right" id="scrollRight">❯</button>
</section>

<!-- OBJEK WISATA -->
<section id="obyek-wisata" class="tourist-spots">
  <div class="section-header scroll-animate">
    <h2>Objek Wisata</h2>
    <p>Jelajahi tempat-tempat indah yang menjadi favorit wisatawan</p>
  </div>
  
  <div class="tourist-grid">
    <div class="spot-card scroll-animate">
      <img src="DSC01304.JPG" alt="Wisata 1">
      <div class="spot-card-content">
        <h3>Spot Foto 1</h3>
        <p>Nikmati sunrise dan pemandangan menarik.</p>
      </div>
    </div>
    
    <div class="spot-card scroll-animate">
      <img src="20241029_131850.jpg" alt="Wisata 2">
      <div class="spot-card-content">
        <h3>Spot Foto 2</h3>
        <p>Pemandangan.</p>
      </div>
    </div>
    
    <div class="spot-card scroll-animate">
      <img src="DSC01359.JPG" alt="Wisata 3">
      <div class="spot-card-content">
        <h3>Spot Foto 3</h3>
        <p>Ghaisan Terbang.</p>
      </div>
    </div>
    
    <div class="spot-card scroll-animate">
      <img src="20241029_132459.jpg" alt="Wisata 4">
      <div class="spot-card-content">
        <h3>Spot Foto 4</h3>
        <p>Pemandangan yang menarik.</p>
      </div>
    </div>
  </div>
</section>

<!-- FASILITAS WISATA -->
<section id="fasilitas-wisata" class="tourist-facility">
  <div class="facility-card scroll-animate">
    <img src="20241029_132459.jpg" alt="Fasilitas Wisata">
    <div class="facility-overlay">
      <h2>Fasilitas Wisata</h2>
      <p>Nikmati kenyamanan terbaik dengan fasilitas lengkap di area wisata kami.</p>
    </div>
  </div>
</section>

<!-- KOMENTAR & RATING -->
<div class="comment-section">
  <div class="section-header">
    <h3>💬 Berikan Rating & Komentar</h3>
  </div>

  <?php if(isset($_SESSION['username'])): ?>
  <form method="POST" class="comment-box">
    <div class="rating-stars">
      <input type="radio" name="rating" id="star5" value="5" required>
      <label for="star5">★</label>
      <input type="radio" name="rating" id="star4" value="4">
      <label for="star4">★</label>
      <input type="radio" name="rating" id="star3" value="3">
      <label for="star3">★</label>
      <input type="radio" name="rating" id="star2" value="2">
      <label for="star2">★</label>
      <input type="radio" name="rating" id="star1" value="1">
      <label for="star1">★</label>
    </div>

    <textarea name="comment" rows="3" placeholder="Tulis komentar kamu..." required></textarea>
    <button type="submit">Kirim</button>
  </form>

  <?php else: ?>
    <p style="color:var(--text-muted); text-align:center; padding:20px; background:var(--comment-bg); border-radius:10px;">Silakan login untuk memberikan komentar.</p>
  <?php endif; ?>

  <!-- LIST KOMENTAR -->
  <div class="comment-list">
    <?php if ($comments && $comments->num_rows > 0): ?>
      <?php while ($c = $comments->fetch_assoc()):
        $pic = $c['profile_pic'] ?: "default.png";
        $role = strtolower($c['role'] ?? "user");

        $label = match($role) {
          "admin" => "Admin",
          "developer" => "Developer",
          "dev_master" => "Dev Master",
          default => "User",
        };
      ?>

      <div class="comment scroll-animate" data-comment-id="<?= $c['id'] ?>">
        <img src="uploads/profiles/<?= $pic ?>" alt="Profil">

        <div class="comment-content">
          <div class="comment-header">
            <strong><?= htmlspecialchars($c['username']) ?></strong>
            <span class="nametag <?= $role ?>"><?= $label ?></span>
            <small><?= date("d M Y H:i", strtotime($c['created_at'])) ?></small>
            <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'developer', 'dev_master'])): ?>
              <div class="comment-actions">
                <button class="delete-comment-btn" onclick="deleteComment(<?= $c['id'] ?>)">
                  <i class="fas fa-trash"></i> Hapus
                </button>
              </div>
            <?php endif; ?>
          </div>

          <!-- ⭐ Rating User -->
          <div class="rating">
            <?= str_repeat("★", $c['rating']) ?>
            <?= str_repeat("☆", 5 - $c['rating']) ?>
          </div>

          <p><?= nl2br(htmlspecialchars($c['comment'])) ?></p>
        </div>
      </div>

      <?php endwhile; ?>
    <?php else: ?>
      <p style="color:var(--text-muted); text-align:center; padding:20px; background:var(--comment-bg); border-radius:10px;">Belum ada komentar.</p>
    <?php endif; ?>
  </div>
</div>

<?php if ($totalCommentPages > 1): ?>
<div class="pagination">
  <?php
    $maxButtons = 5;
    $start = max(1, $page - floor($maxButtons / 2));
    $end   = min($totalCommentPages, $start + $maxButtons - 1);

    if ($page > 1) {
        echo "<a href='?cp=1' class='page-btn'>&laquo;</a>";
    }

    for ($i = $start; $i <= $end; $i++) {
        $active = ($i == $page) ? "class='active'" : "";
        echo "<a href='?cp=$i' class='page-btn' $active>$i</a>";
    }

    if ($page < $totalCommentPages) {
        echo "<a href='?cp=$totalCommentPages' class='page-btn'>&raquo;</a>";
    }
  ?>
</div>
<?php endif; ?>

<!-- TOMBOL TEMA MENGAMBANG -->
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

// Gallery scroll
const gallery = document.getElementById('scrollGallery');
document.getElementById('scrollLeft').onclick =
  () => gallery.scrollBy({ left: -400, behavior: 'smooth' });
document.getElementById('scrollRight').onclick =
  () => gallery.scrollBy({ left: 400, behavior: 'smooth' });

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
  const element = document.getElementById(sectionId);
  if (element) {
    const offsetTop = element.offsetTop - 80; 
    window.scrollTo({ top: offsetTop, behavior: 'smooth' });
  }
}

// Fungsi untuk mengarah ke halaman utama pada bagian About
function goToAbout() {
  window.location.href = "dfscenery.php#about";
}

// Fungsi untuk menghapus komentar
function deleteComment(commentId) {
  if (confirm('Apakah Anda yakin ingin menghapus komentar ini?')) {
    fetch('preview.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      'X-Requested-With': 'XMLHttpRequest'
      },
      body: `delete_comment=true&comment_id=${commentId}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        // Hapus elemen komentar dari DOM
        const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
        if (commentElement) {
          commentElement.style.transition = 'opacity 0.3s, transform 0.3s';
          commentElement.style.opacity = '0';
          commentElement.style.transform = 'translateY(-20px)';
          
          setTimeout(() => {
            commentElement.remove();
          }, 300);
        }
        
        // Tampilkan notifikasi
        showNotification(data.message, 'success');
      } else {
        showNotification(data.message, 'error');
      }
    })
    .catch(error => {
      console.error('Error:', error);
      showNotification('Terjadi kesalahan saat menghapus komentar.', 'error');
    });
  }
}

// Fungsi untuk menampilkan notifikasi
function showNotification(message, type) {
  // Buat elemen notifikasi jika belum ada
  let notification = document.getElementById('notification');
  if (!notification) {
    notification = document.createElement('div');
    notification.id = 'notification';
    notification.className = 'notification';
    document.body.appendChild(notification);
  }
  
  notification.textContent = message;
  notification.className = `notification ${type}`;
  notification.style.position = 'fixed';
  notification.style.top = '20px';
  notification.style.right = '20px';
  notification.style.padding = '15px 25px';
  notification.style.borderRadius = '12px';
  notification.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.15)';
  notification.style.zIndex = '1000';
  notification.style.opacity = '0';
  notification.style.transform = 'translateX(120%)';
  notification.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
  
  // Gaya berdasarkan tipe
  if (type === 'success') {
    notification.style.background = 'linear-gradient(135deg, #06d6a0, #2a9d8f)';
    notification.style.color = 'white';
  } else {
    notification.style.background = 'linear-gradient(135deg, #e63946, #d62828)';
    notification.style.color = 'white';
  }
  
  document.body.appendChild(notification);
  
  // Tampilkan notifikasi
  setTimeout(() => {
    notification.style.opacity = '1';
    notification.style.transform = 'translateX(0)';
  }, 100);
  
  // Sembunyikan notifikasi setelah beberapa detik
  setTimeout(() => {
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(120%)';
  }, 3000);
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

// Deteksi bagian yang sedang dilihat saat scroll
function updateActiveNavOnScroll() {
  const sections = ['obyek-wisata', 'fasilitas-wisata'];
  const buttons = {
    'obyek-wisata': 'btnObyek',
    'fasilitas-wisata': 'btnFasilitas'
  };
  
  // Reset semua tombol aktif
  Object.values(buttons).forEach(btnId => {
    const btn = document.getElementById(btnId);
    if (btn) btn.classList.remove('active');
  });
  
  // Cek setiap bagian untuk melihat apakah sedang terlihat
  sections.forEach(sectionId => {
    const element = document.getElementById(sectionId);
    if (element) {
      const rect = element.getBoundingClientRect();
      const isVisible = rect.top <= window.innerHeight && rect.bottom >= 0;
      
      if (isVisible) {
        const btn = document.getElementById(buttons[sectionId]);
        if (btn) btn.classList.add('active');
        return; // Hentikan iterasi jika bagian sudah ditemukan
      }
    }
  });
}

// Animasi scroll
window.addEventListener('scroll', function() {
  const fadeElements = document.querySelectorAll('.scroll-animate');
  fadeElements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const elementBottom = element.getBoundingClientRect().bottom;
    
    if (elementTop < window.innerHeight && elementBottom > 0) {
      element.classList.add('show');
    }
  });
  
  // Perbarui tombol navbar aktif berdasarkan scroll
  updateActiveNavOnScroll();
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

// Inisialisasi
document.addEventListener('DOMContentLoaded', function() {
  setActiveNav();
  updateActiveNavOnScroll(); // Perbarui status tombol navbar saat halaman dimuat
  
  // Trigger animasi fade in untuk elemen di atas liputan (above the fold)
  const fadeElements = document.querySelectorAll('.scroll-animate');
  fadeElements.forEach(element => {
    const elementTop = element.getBoundingClientRect().top;
    const elementBottom = element.getBoundingClientRect().bottom;
    
    if (elementTop < window.innerHeight && elementBottom > 0) {
      element.classList.add('show');
    }
  });
});
</script>

<style>
/* Gaya untuk notifikasi */
.notification {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 15px 25px;
  border-radius: 12px;
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
  z-index: 1000;
  max-width: 300px;
  font-weight: 500;
  transform: translateX(120%);
  transition: all 0.3s ease;
}

.notification.show {
  transform: translateX(0);
}
</style>

</body>
</html>