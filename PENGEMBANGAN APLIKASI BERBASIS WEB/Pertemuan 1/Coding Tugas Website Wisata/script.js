function goHome() {
  window.location.href = "index.html";
}

function goTickets() {
  // sebelumnya: window.location.href = "tickets.php";
  window.location.href = "tickets.html"; 
}

function goPreview() {
  window.location.href = "preview.html";
}


// === MODAL CONTROL ===
function openModal() {
  document.getElementById("authModal").style.display = "block";
  showLogin();
}

function closeModal() {
  document.getElementById("authModal").style.display = "none";
}

function showRegister() {
  document.getElementById("loginForm").style.display = "none";
  document.getElementById("registerForm").style.display = "block";
}

function showLogin() {
  document.getElementById("registerForm").style.display = "none";
  document.getElementById("loginForm").style.display = "block";
}

// === REGISTER FUNCTION ===
function register() {
  let username = document.getElementById("registerUsername").value;
  let password = document.getElementById("registerPassword").value;

  if (username && password) {
    if (localStorage.getItem(username)) {
      alert("Username sudah terdaftar!");
      return;
    }
    localStorage.setItem(username, password);
    alert("Registrasi berhasil! Silakan login.");
    showLogin();
  } else {
    alert("Isi semua data!");
  }
}

// === LOGIN FUNCTION ===
function login() {
  let username = document.getElementById("loginUsername").value;
  let password = document.getElementById("loginPassword").value;
  let storedPassword = localStorage.getItem(username);

  if (storedPassword && storedPassword === password) {
    alert("Login berhasil! Selamat datang, " + username);
    closeModal();
    localStorage.setItem("loggedInUser", username);
    updateAuthNav();
  } else {
    alert("Username atau password salah!");
  }
}

// === LOGOUT FUNCTION ===
function logout() {
  localStorage.removeItem("loggedInUser");
  updateAuthNav();
}

// === UPDATE HEADER SESUAI LOGIN ===
function updateAuthNav() {
  const authNav = document.getElementById("authNav");
  const user = localStorage.getItem("loggedInUser");

  if (authNav) { // supaya tidak error di halaman yg tidak punya authNav
    if (user) {
      authNav.innerHTML = `
        <span>Welcome, <b>${user}</b></span>
        <a href="#" onclick="logout()">Logout</a>
      `;
    } else {
      authNav.innerHTML = `
        <a href="#" onclick="openModal()">Login</a>
        <a href="#" onclick="openModal();showRegister()">Register</a>
      `;
    }
  }
}

// Jalankan setiap reload
window.onload = updateAuthNav;