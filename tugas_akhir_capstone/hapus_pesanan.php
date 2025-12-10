<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db.php';

// Set header respons ke JSON
header('Content-Type: application/json');

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(["success" => false, "message" => "Anda harus login terlebih dahulu"]);
    exit;
}

// Cek ID pesanan
 $order_id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
if ($order_id <= 0) {
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "message" => "ID pesanan tidak valid"]);
    exit;
}

// Ambil data pesanan dari database untuk verifikasi kepemilikan
 $stmt = $conn->prepare("SELECT name FROM tickets WHERE id = ?");
if (!$stmt) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["success" => false, "message" => "Kesalahan database: " . $conn->error]);
    exit;
}
 $stmt->bind_param("i", $order_id);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows === 0) {
    http_response_code(404); // Not Found
    echo json_encode(["success" => false, "message" => "Pesanan tidak ditemukan"]);
    $stmt->close();
    exit;
}

 $order = $result->fetch_assoc();
 $stmt->close();

// Verifikasi bahwa pesanan milik user yang sedang login atau admin
 $is_admin = isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'developer', 'dev_master']);
if (!$is_admin && $order['name'] !== $_SESSION['username']) {
    http_response_code(403); // Forbidden
    echo json_encode(["success" => false, "message" => "Anda tidak memiliki izin untuk menghapus pesanan ini"]);
    exit;
}

// Hapus pesanan
 $stmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");
if (!$stmt) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Kesalahan database saat menyiapkan penghapusan: " . $conn->error]);
    exit;
}
 $stmt->bind_param("i", $order_id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Pesanan berhasil dihapus"]);
    } else {
        echo json_encode(["success" => false, "message" => "Tidak ada perubahan pada pesanan. Pesanan mungkin sudah dihapus."]);
    }
} else {
    http_response_code(500);
    $db_error = $stmt->error;
    echo json_encode([
        "success" => false, 
        "message" => "Gagal menghapus pesanan.", 
        "db_error" => $db_error 
    ]);
}
 $stmt->close();
?>