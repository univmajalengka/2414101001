<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>DF Links | David Firdaus</title>
  <link rel="icon" href="binigua.jpg">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    :root {
      --bg-color: #0d0d0d;
      --text-color: #fff;
      --accent: #ff4081;
      --btn-premium: #5865f2;
      --btn-web: #224569ff;
      --btn-whatsapp: #008e07ff;
      --btn-instagram: #d32f68ff;
    }

    body.light-mode {
      --bg-color: #f8f8fb;
      --text-color: #222;
      --accent: #ff4081;
      --btn-premium: #7b5bff;
      --btn-web: #5a7ca5;
      --btn-whatsapp: #00c853;
      --btn-instagram: #e91e63;
    }

    body {
      background-color: var(--bg-color);
      color: var(--text-color);
      font-family: "Poppins", sans-serif;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      transition: background 0.4s, color 0.4s;
    }

    .profile {
      text-align: center;
      margin-bottom: 20px;
    }

    .profile img {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      object-fit: cover;
      border: 3px solid var(--accent);
      transition: border 0.4s;
    }

    .profile h1 {
      margin: 10px 0 5px;
      font-size: 26px;
      font-weight: 700;
    }

    .profile p {
      color: #ccc;
      font-size: 14px;
      margin-bottom: 30px;
      transition: color 0.4s;
    }

    .links {
      display: flex;
      flex-direction: column;
      gap: 15px;
      width: 260px;
    }

    .link-btn {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      padding: 12px;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      color: white;
      font-size: 15px;
      transition: 0.3s;
    }

    .premium { background-color: var(--btn-premium); }
    .web { background-color: var(--btn-web); }
    .whatsapp { background-color: var(--btn-whatsapp); }
    .instagram { background-color: var(--btn-instagram); }

    .link-btn:hover {
      transform: translateY(-3px);
      filter: brightness(1.1);
    }

    footer {
      margin-top: 40px;
      font-size: 13px;
      color: #777;
      transition: color 0.4s;
    }

    footer a {
      color: var(--accent);
      text-decoration: none;
      transition: color 0.4s;
    }

    /* Tombol ganti tema */
    .theme-toggle {
      position: fixed;
      right: 25px;
      bottom: 25px;
      background: var(--accent);
      color: white;
      border: none;
      border-radius: 50%;
      width: 45px;
      height: 45px;
      font-size: 20px;
      cursor: pointer;
      transition: 0.3s;
      box-shadow: 0 0 10px rgba(0,0,0,0.4);
    }

    .theme-toggle:hover {
      transform: scale(1.1);
    }
  </style>
</head>
<body>

  <div class="profile">
    <img src="binigua.jpg" alt="Profile Picture">
    <h1>David Firdaus</h1>
    <p>Creator of DF Scenery & DF Komik</p>
  </div>

  <div class="links">
    <a href="dfscenery.php" class="link-btn premium" target="_blank">
      <i class="fas fa-crown"></i> DFScenery
    </a>
    <a href="dfkomik.php" class="link-btn web" target="_blank">
      <i class="fas fa-home"></i> DFKomik
    </a>
    <a href="https://wa.me/6285171202190/" class="link-btn whatsapp" target="_blank">
      <i class="fab fa-whatsapp"></i> WhatsApp
    </a>
    <a href="https://www.instagram.com/davidfirdaus_08/" class="link-btn instagram" target="_blank">
      <i class="fas fa-coffee"></i> Instagram
    </a>
  </div>

  <footer>
    DF Links © <?= date("Y") ?> | by <a href="#">David Firdaus</a>
  </footer>

  <!-- Tombol toggle tema -->
  <button class="theme-toggle" id="themeBtn" title="Ganti Mode">🌙</button>

 <script>
  const themeBtn = document.getElementById('themeBtn');
  const currentTheme = localStorage.getItem('theme') || 'dark';

  if (currentTheme === 'light') {
    document.body.classList.add('light-mode');
    themeBtn.textContent = '🌙'; 
  } else {
    themeBtn.textContent = '☀️'; 
  }

  themeBtn.onclick = () => {
    document.body.classList.toggle('light-mode');
    const isLight = document.body.classList.contains('light-mode');
    themeBtn.textContent = isLight ? '🌙' : '☀️'; // Balik ikon
    localStorage.setItem('theme', isLight ? 'light' : 'dark');
  };
</script>

</body>
</html>