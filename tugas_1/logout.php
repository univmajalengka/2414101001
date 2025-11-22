<?php
session_start();

// Hapus semua session
$_SESSION = [];
session_unset();
session_destroy();

// Hapus cookie login (jika ada)
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Arahkan ke halaman login
header("Location: dfscenery.php");
exit;
?>