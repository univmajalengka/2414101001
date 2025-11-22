<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>DF Scenery - Jelajahi Keindahan Indonesia</title>
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
  --hero-overlay: linear-gradient(135deg, rgba(0, 119, 182, 0.8), rgba(0, 180, 216, 0.6));
  --section-bg-1: linear-gradient(135deg, #eef7ff, #e6f3ff);
  --section-bg-2: linear-gradient(135deg, #f8f9fa, #ffffff);
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
  --hero-overlay: linear-gradient(135deg, rgba(22, 33, 62, 0.8), rgba(40, 40, 55, 0.6));
  --section-bg-1: linear-gradient(135deg, #212529, #343a40);
  --section-bg-2: linear-gradient(135deg, #2c3e50, #34495e);
  --input-bg: #495057;
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

header h1 .white { color: #fff; }
header h1 .black { color: #000; }

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

/* === DEKORASI LATAR BELAKANG === */
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

/* === BAGIAN HERO === */
.hero {
  height: 100vh;
  position: relative;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  z-index: 1;
}

.hero-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: url('20250919_123536.jpg') no-repeat center center/cover;
  filter: brightness(0.7);
  z-index: -2;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: var(--hero-overlay);
  z-index: -1;
}

.hero-content {
  text-align: center;
  color: white;
  padding: 0 20px;
  max-width: 900px;
  z-index: 2;
  animation: fadeInUp 1s ease;
}

.hero-content h1 {
  font-size: 3.5rem;
  font-weight: 700;
  margin-bottom: 20px;
  text-shadow: 0 2px 10px rgba(0,0,0,0.3);
}

.hero-content p {
  font-size: 1.2rem;
  margin-bottom: 30px;
  max-width: 700px;
  margin-left: auto;
  margin-right: auto;
  text-shadow: 0 1px 5px rgba(0,0,0,0.3);
}

.hero-buttons {
  display: flex;
  gap: 20px;
  justify-content: center;
  flex-wrap: wrap;
  animation: fadeInUp 1s ease 0.4s both;
}

.btn-primary {
  padding: 12px 30px;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: none;
  border-radius: 30px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.3);
}

.btn-primary:hover {
  background: rgba(255, 255, 255, 0.3);
  transform: translateY(-3px);
  box-shadow: 0 10px 20px rgba(255,255,255,0.2);
}

.btn-secondary {
  padding: 12px 30px;
  background: transparent;
  color: white;
  border: 2px solid white;
  border-radius: 30px;
  font-size: 16px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s ease;
  text-decoration: none;
  display: inline-block;
}

.btn-secondary:hover {
  background: white;
  color: var(--primary-color);
  transform: translateY(-3px);
}

/* === GAYA section === */
section {
  padding: 80px 20px;
  position: relative;
  z-index: 1;
}

.section-header {
  text-align: center;
  margin-bottom: 50px;
  position: relative;
}

.section-header h2 {
  font-size: 2.5rem;
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
  font-size: 1.1rem;
  color: var(--text-color);
  opacity: 0.8;
  max-width: 700px;
  margin: 0 auto;
}

/* === section DESTINASI === */
.destinations-section {
  background: var(--section-bg-1);
}

.destinations-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.destination-card {
  background: var(--card-bg);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  transition: all 0.3s ease;
  position: relative;
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-color);
}

.destination-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

.destination-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
  transition: transform 0.5s ease;
}

.destination-card:hover img {
  transform: scale(1.05);
}

.destination-overlay {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 20px;
  background: linear-gradient(to top, rgba(0,119,182,0.9), rgba(0,119,182,0));
  color: white;
}

.destination-overlay h3 {
  margin: 0 0 10px;
  font-size: 1.5rem;
  font-weight: 700;
}

.destination-overlay p {
  margin: 0;
  font-size: 1rem;
  opacity: 0.9;
}

/* === section ARTIKEL === */
.articles-section {
  background: var(--section-bg-2);
}

.articles-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.article-card {
  background: var(--card-bg);
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-color);
}

.article-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.article-card img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.article-content {
  padding: 25px;
}

.article-content h3 {
  font-size: 1.3rem;
  color: var(--primary-color);
  margin-bottom: 10px;
}

.article-content p {
  color: var(--text-color);
  opacity: 0.8;
  margin-bottom: 15px;
}

.article-content a {
  color: var(--primary-color);
  font-weight: 600;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  transition: all 0.3s ease;
}

.article-content a:hover {
  color: var(--secondary-color);
}

.article-content a i {
  margin-left: 5px;
  transition: transform 0.3s ease;
}

.article-content a:hover i {
  transform: translateX(5px);
}

/* === section VIDEO === */
.video-section {
  background: var(--section-bg-1);
  text-align: center;
}

.video-container {
  max-width: 900px;
  margin: 0 auto;
  position: relative;
  padding: 25px;
  border-radius: 20px;
  overflow: hidden;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  backdrop-filter: blur(10px);
  background: rgba(255,255,255,0.2);
  border: 1px solid var(--border-color);
}

.video-container iframe {
  width: 100%;
  height: 500px;
  border-radius: 15px;
  border: none;
}

/* === section TESTIMONI === */
.testimonials-section {
  background: var(--section-bg-2);
}

.testimonials-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  max-width: 1200px;
  margin: 0 auto;
}

.testimonial-card {
  background: var(--card-bg);
  border-radius: 20px;
  padding: 30px;
  text-align: center;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  transition: all 0.3s ease;
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-color);
}

.testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}

.testimonial-card img {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  margin: 0 auto 15px;
  border: 3px solid var(--primary-color);
}

.testimonial-card p {
  font-style: italic;
  color: var(--text-color);
  opacity: 0.8;
  margin-bottom: 15px;
}

.testimonial-card h4 {
  color: var(--primary-color);
  margin-bottom: 5px;
}

.testimonial-card .rating {
  color: gold;
  font-size: 18px;
  margin-bottom: 10px;
}

/* === section AJAKAN (CTA) === */
.cta-section {
  background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
  color: white;
  text-align: center;
  padding: 80px 20px;
}

.cta-section h2 {
  font-size: 2.5rem;
  margin-bottom: 20px;
}

.cta-section p {
  font-size: 1.2rem;
  max-width: 700px;
  margin: 0 auto 30px;
}

.cta-button {
  display: inline-block;
  padding: 15px 30px;
  background: rgba(255, 255, 255, 0.2);
  color: white;
  border: 2px solid white;
  border-radius: 30px;
  font-size: 18px;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.3s ease;
}

.cta-button:hover {
  background: white;
  color: var(--primary-color);
  transform: translateY(-3px);
}

/* === section TENTANG === */
.about-section {
  background: var(--section-bg-1);
}

.about-content {
  max-width: 900px;
  margin: 0 auto;
  background: var(--card-bg);
  padding: 40px;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.15);
  backdrop-filter: blur(10px);
  border: 1px solid var(--border-color);
}

.about-content h2 {
  color: var(--primary-color);
  margin-bottom: 20px;
  font-size: 2rem;
}

.about-content p {
  line-height: 1.7;
  margin-bottom: 20px;
  color: var(--text-color);
  opacity: 0.8;
}

.about-stats {
  display: flex;
  justify-content: space-around;
  margin-top: 30px;
  flex-wrap: wrap;
}

.stat-item {
  text-align: center;
  margin-bottom: 20px;
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--primary-color);
  margin-bottom: 5px;
}

.stat-label {
  font-size: 1rem;
  color: var(--text-color);
  opacity: 0.8;
}

/* === FOOTER === */
footer {
  background: var(--footer-bg);
  color: white;
  padding: 60px 20px 40px;
  margin-top: 70px;
  backdrop-filter: blur(10px);
  border-top: 1px solid var(--border-color);
}

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 40px;
}

.footer-column h3 {
  font-size: 1.5rem;
  margin-bottom: 20px;
  position: relative;
  padding-bottom: 10px;
}

.footer-column h3::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  width: 50px;
  height: 3px;
  background: white;
}

.footer-column p, .footer-column li {
  margin-bottom: 10px;
  opacity: 0.9;
}

.footer-column ul {
  list-style: none;
}

.footer-column a {
  color: white;
  text-decoration: none;
  transition: all 0.3s ease;
}

.footer-column a:hover {
  opacity: 0.8;
  text-decoration: underline;
}

.social-links {
  display: flex;
  gap: 15px;
  margin-top: 20px;
}

.social-links a {
  color: white;
  font-size: 22px;
  text-decoration: none;
  transition: all 0.3s ease;
}

.social-links a:hover {
  opacity: 0.65;
  transform: translateY(-3px);
}

.copyright {
  margin-top: 20px;
  font-size: 14px;
  opacity: 0.8;
  text-align: center;
  grid-column: 1 / -1;
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

/* === TOMBOL CHAT MENGAMBANG === */
#chatroom-float-btn {
  position: fixed;
  bottom: 20px;
  left: 20px;
  width: 60px;
  height: 60px;
  background: rgba(0, 119, 182, 0.7); /* Warna semi-transparan */
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  text-decoration: none;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  transition: all 0.3s ease;
  z-index: 9998; /* Di bawah tombol tema */
  backdrop-filter: blur(5px);
  border: 1px solid rgba(255,255,255,0.2);
}

#chatroom-float-btn:hover {
  transform: scale(1.1);
  background: rgba(0, 119, 182, 0.9);
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

/* === RESPONSIF === */
@media (max-width: 992px) {
  .destinations-grid, .articles-grid, .testimonials-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

  /* HERO */
  .hero-content h1 {
    font-size: 2.8rem;
  }

  .hero-content p {
    font-size: 1rem;
  }

  .hero-buttons {
    flex-direction: column;
    gap: 15px;
  }

  section {
    padding: 60px 15px;
  }

  .section-header h2 {
    font-size: 2rem;
  }

  .destinations-grid, .articles-grid, .testimonials-grid {
    grid-template-columns: 1fr;
  }

  .about-stats {
    flex-direction: column;
    gap: 20px;
  }

  .footer-content {
    grid-template-columns: 1fr;
    text-align: center;
  }
}

/* Layar sangat kecil */
@media (max-width: 480px) {
  header h1 {
    font-size: 20px;
  }

  .hero-content h1 {
    font-size: 2.2rem;
  }

  .section-header h2 {
    font-size: 1.8rem;
  }

  .video-container iframe {
    height: 300px;
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
      <button id="btnHome" class="active" onclick="goHome()">Home</button>
      <button id="btnTickets" onclick="goTickets()">Pemesanan</button>
      <button id="btnPreview" onclick="goPreview()">Galeri</button>
      <button id="btnObyek" onclick="goToObyek()">Obyek Wisata</button>
      <button id="btnFasilitas" onclick="goToFasilitas()">Fasilitas Wisata</button>
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

  <!-- BAGIAN HERO -->
  <section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content fade-in">
      <h1>Jelajahi Keindahan Indonesia</h1>
      <p>Temukan destinasi wisata menakjubkan dan pengalaman tak terlupakan di berbagai penjuru nusantara</p>
      <div class="hero-buttons">
        <a href="#destinations" class="btn-primary">Jelajahi Sekarang</a>
        <a href="#testimonials" class="btn-secondary">Lihat Testimoni</a>
      </div>
    </div>
  </section>

  <!-- BAGIAN DESTINASI -->
  <section id="destinations" class="destinations-section">
    <div class="section-header fade-in">
      <h2>Destinasi Populer</h2>
      <p>Jelajahi tempat-tempat indah yang menjadi favorit wisatawan</p>
    </div>
    
    <div class="destinations-grid">
      <div class="destination-card fade-in">
        <img src="20241013_162450.jpg" alt="Terasering Panyaweuyan">
        <div class="destination-overlay">
          <h3>Terasering Panyaweuyan</h3>
          <p>Pemandangan terasering yang memukau di ketinggian</p>
        </div>
      </div>
      
      <div class="destination-card fade-in">
        <img src="20241013_165251.jpg" alt="Pegunungan Indah">
        <div class="destination-overlay">
          <h3>Pegunungan Indah</h3>
          <p>Nikmati keindahan alam pegunungan yang memukau</p>
        </div>
      </div>
      
      <div class="destination-card fade-in">
        <img src="20241013_164202.jpg" alt="Pantai Eksotis">
        <div class="destination-overlay">
          <h3>Pemandangan Indah</h3>
          <p>Saksikan matahari terbenam dari puncak tertinggi</p>
        </div>
      </div>
    </div>
  </section>

  <!-- BAGIAN ARTIKEL -->
  <section class="articles-section">
    <div class="section-header fade-in">
      <h2>Artikel Wisata</h2>
      <p>Baca informasi menarik seputar destinasi wisata Indonesia</p>
    </div>
    
    <div class="articles-grid">
      <article class="article-card fade-in">
        <img src="20241013_162340.jpg" alt="Artikel 1">
        <div class="article-content">
          <h3>5 Tips Liburan yang Nyaman</h3>
          <p>Panduan lengkap untuk memastikan liburan Anda berjalan lancar dan menyenangkan</p>
          <a href="#">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
        </div>
      </article>
      
      <article class="article-card fade-in">
        <img src="20241013_161500.jpg" alt="Artikel 2">
        <div class="article-content">
          <h3>Destinasi Tersembunyi di Jawa Barat</h3>
          <p>Temukan tempat-tempat indah yang belum banyak diketahui wisatawan</p>
          <a href="#">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
        </div>
      </article>
      
      <article class="article-card fade-in">
        <img src="20241013_164223.jpg" alt="Artikel 3">
        <div class="article-content">
          <h3>Fotografi Landscape: Teknik Dasar</h3>
          <p>Pelajari teknik dasar fotografi landscape untuk hasil yang memukau</p>
          <a href="#">Baca Selengkapnya <i class="fas fa-arrow-right"></i></a>
        </div>
      </article>
    </div>
  </section>

  <!-- BAGIAN VIDEO -->
  <section class="video-section">
    <div class="section-header fade-in">
      <h2>DFScenery Video Showcase</h2>
      <p>Saksikan keindahan destinasi wisata kami dalam format video berkualitas tinggi</p>
    </div>
    
    <div class="video-container fade-in">
      <iframe 
        src="https://www.youtube.com/embed/U5iPDotw8pk"
        frameborder="0"
        allowfullscreen></iframe>
    </div>
  </section>

  <!-- BAGIAN TESTIMONI -->
  <section id="testimonials" class="testimonials-section">
    <div class="section-header fade-in">
      <h2>Apa Kata Mereka?</h2>
      <p>Dengarkan pengalaman wisatawan yang telah berkunjung ke destinasi kami</p>
    </div>
    
    <div class="testimonials-grid">
      <div class="testimonial-card fade-in">
        <img src="binigua.jpg" alt="Pengunjung 1">
        <p>"DFScenery membantu saya menemukan tempat-tempat indah yang belum saya ketahui sebelumnya. Sangat direkomendasikan!"</p>
        <h4>David Firdaus</h4>
        <div class="rating">★★★★★★</div>
      </div>
      
      <div class="testimonial-card fade-in">
        <img src="binigua1.jpg" alt="Pengunjung 2">
        <p>"Pemandangan di Terasering Panyaweuyan benar-benar memukau. Saya pasti akan kembali lagi!"</p>
        <h4>Virlan Hakuto</h4>
        <div class="rating">★★★★★★★</div>
      </div>
      
      <div class="testimonial-card fade-in">
        <img src="binigua2.jpg" alt="Pengunjung 3">
        <p>"Website ini sangat bagus."</p>
        <h4>Virlan Hikaru</h4>
        <div class="rating">★★★★★</div>
      </div>
    </div>
  </section>

  <!-- BAGIAN AJAKAN (CTA) -->
  <section class="cta-section">
    <h2>Siap Memulai Petualangan Anda?</h2>
    <p>Jelajahi lebih banyak destinasi menakjubkan dan dapatkan pengalaman tak terlupakan bersama DFScenery</p>
    <a href="tickets.php" class="cta-button">Pesan Tiket Sekarang</a>
  </section>

  <!-- BAGIAN TENTANG -->
  <section id="about" class="about-section">
    <div class="about-content fade-in">
      <h2>Tentang DF Scenery</h2>
      <p>
        DFScenery adalah platform wisata yang menampilkan berbagai rekomendasi tempat pemandangan indah dengan foto dan video berkualitas tinggi. Website ini dibuat untuk membantu pengunjung menemukan lokasi wisata terbaik, menikmati visual pemandangan, serta mendapatkan inspirasi destinasi baru.
      </p>
      
      <div class="about-stats">
        <div class="stat-item">
          <div class="stat-number">50+</div>
          <div class="stat-label">Destinasi</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">1000+</div>
          <div class="stat-label">Pengunjung</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">4.9</div>
          <div class="stat-label">Rating</div>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-content">
      <div class="footer-column">
        <h3>DF Scenery</h3>
        <p>Platform wisata yang menampilkan keindahan Indonesia dengan foto dan video berkualitas tinggi.</p>
        <div class="social-links">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="https://www.instagram.com/davidfirdaus_08/"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-youtube"></i></a>
        </div>
      </div>
      
      <div class="footer-column">
        <h3>Link Cepat</h3>
        <ul>
          <li><a href="dfscenery.php">Beranda</a></li>
          <li><a href="tickets.php">Pemesanan</a></li>
          <li><a href="preview.php">Galeri</a></li>
          <li><a href="index.php">Tentang Kami</a></li>
        </ul>
      </div>
      
      <div class="footer-column">
        <h3>Kontak</h3>
        <p><i class="fas fa-map-marker-alt"></i> Desa Sukasari Kidul, Kecamatan Argapura, Kabupaten Majalengka, Jawa Barat.</p>
        <p><i class="fas fa-phone"></i> +62 812-3456-7890</p>
        <p><i class="fas fa-envelope"></i> info@dfscenery.com</p>
      </div>
      
      <div class="footer-column">
        <h3>Newsletter</h3>
        <p>Dapatkan informasi terbaru tentang destinasi wisata menarik</p>
        <form style="margin-top: 15px;">
          <input type="email" placeholder="Email Anda" style="padding: 10px; border-radius: 5px; border: none; width: 100%; margin-bottom: 10px; background: var(--input-bg); color: var(--text-color);">
          <button type="submit" style="padding: 10px 15px; background: white; color: var(--primary-color); border: none; border-radius: 5px; font-weight: 600; cursor: pointer; width: 100%;">Subscribe</button>
        </form>
      </div>
    </div>
    
    <div class="copyright">
      <p>&copy; <?= date("Y") ?> DF Scenery. All Rights Reserved.</p>
    </div>
  </footer>

  <!-- TOMBOL TEMA MENGAMBANG -->
  <div class="theme-toggle-float">
    <button id="theme-toggle-btn" aria-label="Toggle theme">
      <i class="fas fa-sun" id="theme-icon-light"></i>
      <i class="fas fa-moon" id="theme-icon-dark" style="display: none;"></i>
    </button>
  </div>

  <!-- TOMBOL CHAT MENGAMBANG -->
  <a href="chatroom.php" id="chatroom-float-btn" title="Buka Chatroom">
    <i class="fas fa-comments"></i>
  </a>

  <script>
  // Toggle menu mobile
  document.getElementById('menuToggle').addEventListener('click', function() {
    document.getElementById('mainNav').classList.toggle('active');
    document.getElementById('authNav').classList.toggle('active');
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

  // Perubahan: Fungsi khusus untuk tombol Obyek dan Fasilitas
  function goToObyek() {
    window.location.href = "preview.php#obyek-wisata";
  }

  function goToFasilitas() {
    window.location.href = "preview.php#fasilitas-wisata";
  }

  // Fungsi scroll untuk bagian lain (About)
  function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
      const offsetTop = element.offsetTop - 80; 
      window.scrollTo({ top: offsetTop, behavior: 'smooth' });
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

  // Inisialisasi
  document.addEventListener('DOMContentLoaded', function() {
    setActiveNav();
    
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
  </script>
</body>
</html>