<?php
session_start();
include 'db.php';

// Cek apakah user sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Cek ID pesanan
 $order_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($order_id <= 0) {
    header("Location: tickets.php");
    exit;
}

// Ambil data pesanan dari database
 $stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ?");
 $stmt->bind_param("i", $order_id);
 $stmt->execute();
 $result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: tickets.php");
    exit;
}

 $order = $result->fetch_assoc();
 $stmt->close();

// Verifikasi bahwa pesanan milik user yang sedang login atau admin
if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], ['admin', 'developer', 'dev_master'])) {
    if ($order['name'] !== $_SESSION['username']) {
        header("Location: tickets.php");
        exit;
    }
}

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $participant_count = intval($_POST['participant_count'] ?? 1);
    $travel_days = intval($_POST['travel_days'] ?? 1);
    $accommodation = (isset($_POST['accommodation']) && $_POST['accommodation'] == '1') ? 1 : 0;
    $transportation = (isset($_POST['transportation']) && $_POST['transportation'] == '1') ? 1 : 0;
    $food = (isset($_POST['food']) && $_POST['food'] == '1') ? 1 : 0;
    $travel_date = $_POST['travel_date'] ?? date('Y-m-d');
    $payment_method = $_POST['payment_method'] ?? '';
    
    // Hitung ulang package total
    $package_total = 0;
    if ($accommodation) $package_total += 100000;
    if ($transportation) $package_total += 120000;
    if ($food) $package_total += 65000;
    $package_total *= $participant_count * $travel_days;
    
    // Total harga baru
    $total_price = $order['ticket_price'] + $package_total;
    
    // Update data pesanan
    $stmt = $conn->prepare("UPDATE tickets SET participant_count=?, travel_days=?, accommodation=?, transportation=?, food=?, travel_date=?, payment_method=?, package_total=?, total_price=? WHERE id=?");
    $stmt->bind_param("iiiiisssdi", $participant_count, $travel_days, $accommodation, $transportation, $food, $travel_date, $payment_method, $package_total, $total_price, $order_id);
    
    if ($stmt->execute()) {
        header("Location: tickets.php?status=success");
        exit;
    } else {
        $error = "Gagal memperbarui pesanan: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Pesanan - DFScenery</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Variabel Warna untuk Tema Terang - Sama dengan tickets.php */
    :root {
      --bg-color: linear-gradient(135deg, #d7efff, #ffffff);
      --text-color: #003049;
      --card-bg: rgba(255, 255, 255, 0.25);
      --header-bg: rgba(0, 119, 182, 0.7);
      --footer-bg: rgba(0, 119, 182, 0.9);
      --primary-color: #0077b6;
      --secondary-color: #00b4d8;
      --accent-color: #90e0ef;
      --border-color: rgba(255,255,255,0.18);
      --input-bg: rgba(255, 255, 255, 0.5);
      --success-color: #06d6a0;
      --error-color: #ef476f;
      --warning-color: #ffd166;
      --info-color: #118ab2;
      --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
      --transition: all 0.3s ease;
    }

    /* Variabel Warna untuk Tema Gelap - Sama dengan tickets.php */
    body.dark-mode {
      --bg-color: linear-gradient(135deg, #1a1a2e, #16213e);
      --text-color: #e0e0e0;
      --card-bg: rgba(40, 40, 55, 0.25);
      --header-bg: rgba(22, 33, 62, 0.9);
      --footer-bg: rgba(22, 33, 62, 0.9);
      --primary-color: #4cc9f0;
      --secondary-color: #4895ef;
      --accent-color: #7209b7;
      --border-color: rgba(255,255,255,0.18);
      --input-bg: rgba(49, 50, 57, 0.5);
      --shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: "Poppins", Arial, sans-serif;
      background: var(--bg-color);
      color: var(--text-color);
      min-height: 100vh;
      padding-top: 80px;
      transition: var(--transition);
      position: relative;
      overflow-x: hidden;
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
    
    /* Header styles - Navbar seperti di halaman tickets.php */
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
    
    /* Tombol navigasi - Sama dengan tickets.php */
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
    
    /* Profile area - Sama dengan tickets.php */
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

    /* Tombol logout - Sama dengan tickets.php */
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
    
    /* Main container */
    .main-container {
      display: flex;
      max-width: 1200px;
      margin: 40px auto;
      gap: 30px;
      padding: 0 20px;
    }
    
    /* Form container - Liquid Glass Effect */
    .edit-container {
      flex: 2;
      background: var(--card-bg);
      border-radius: 20px;
      box-shadow: var(--shadow);
      overflow: hidden;
      animation: fadeInUp 0.5s ease;
      border: 1px solid var(--border-color);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      position: relative;
      overflow: hidden;
    }
    
    /* Efek cahaya untuk liquid glass */
    .edit-container::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.1),
        transparent
      );
      transform: rotate(45deg);
      transition: all 0.6s;
      pointer-events: none;
    }
    
    .edit-container:hover::before {
      animation: shine 0.5s ease-in-out;
    }
    
    @keyframes shine {
      0% {
        transform: translateX(-100%) translateY(-100%) rotate(45deg);
      }
      100% {
        transform: translateX(100%) translateY(100%) rotate(45deg);
      }
    }
    
    .edit-header {
      background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
      color: white;
      padding: 25px 30px;
      position: relative;
      overflow: hidden;
    }
    
    .edit-header::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none"/><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(255,255,255,0.1)" stroke-width="1"/></svg>');
      opacity: 0.2;
    }
    
    .edit-header h2 {
      font-size: 28px;
      font-weight: 700;
      margin-bottom: 5px;
      position: relative;
      z-index: 1;
    }
    
    .edit-header p {
      opacity: 0.9;
      position: relative;
      z-index: 1;
    }
    
    .edit-body {
      padding: 30px;
    }
    
    /* Form styles - Liquid Glass Effect */
    .form-section {
      margin-bottom: 30px;
      background-color: rgba(255, 255, 255, 0.15);
      padding: 20px;
      border-radius: 15px;
      border: 1px solid var(--border-color);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      position: relative;
      overflow: hidden;
    }
    
    /* Efek cahaya untuk form section */
    .form-section::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.05),
        transparent
      );
      transform: rotate(45deg);
      transition: all 0.6s;
      pointer-events: none;
    }
    
    .form-section:hover::before {
      animation: shine 0.5s ease-in-out;
    }
    
    .section-title {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 18px;
      font-weight: 600;
      color: var(--primary-color);
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--border-color);
      position: relative;
      z-index: 1;
    }
    
    .section-title i {
      font-size: 20px;
    }
    
    .form-row {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }
    
    .form-group {
      flex: 1;
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--text-color);
    }
    
    .form-group input, .form-group select {
      width: 100%;
      padding: 12px 15px;
      border-radius: 10px;
      border: 1px solid var(--border-color);
      background: var(--input-bg);
      color: var(--text-color);
      font-weight: 500;
      transition: var(--transition);
      backdrop-filter: blur(3px);
      -webkit-backdrop-filter: blur(3px);
    }
    
    .form-group input:focus, .form-group select:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(0, 119, 182, 0.2);
    }
    
    /* Checkbox styles - tata letak dengan Liquid Glass Effect */
    .checkbox-group {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }
    
    .checkbox-item {
      position: relative;
      padding: 20px;
      border-radius: 15px;
      background: rgba(255, 255, 255, 0.15);
      border: 2px solid var(--border-color);
      cursor: pointer;
      transition: var(--transition);
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      display: flex;
      align-items: center;
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      overflow: hidden;
    }
    
    /* Efek cahaya untuk checkbox item */
    .checkbox-item::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.1),
        transparent
      );
      transform: rotate(45deg);
      transition: all 0.6s;
      pointer-events: none;
    }
    
    .checkbox-item:hover::before {
      animation: shine 0.5s ease-in-out;
    }
    
    .checkbox-item:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
      border-color: var(--primary-color);
    }
    
    .checkbox-item.checked {
      border-color: var(--primary-color);
      background: rgba(0, 119, 182, 0.15);
    }
    
    .checkbox-item input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
    }
    
    .checkbox-label {
      display: flex;
      align-items: center;
      width: 100%;
      justify-content: space-between;
      position: relative;
      z-index: 1;
    }
    
    .checkbox-left {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    
    .checkbox-icon {
      width: 28px;
      height: 28px;
      border-radius: 8px;
      border: 2px solid var(--primary-color);
      display: flex;
      align-items: center;
      justify-content: center;
      transition: var(--transition);
      background: rgba(255, 255, 255, 0.2);
    }
    
    .checkbox-item.checked .checkbox-icon {
      background: var(--primary-color);
    }
    
    .checkbox-item.checked .checkbox-icon i {
      color: white;
      font-size: 16px;
    }
    
    .checkbox-text {
      font-weight: 600;
      font-size: 16px;
    }
    
    .checkbox-price {
      font-weight: 700;
      color: var(--accent-color);
      font-size: 16px;
    }
    
    /* Price calculator - Liquid Glass Effect */
    .price-calculator {
      background: rgba(255, 255, 255, 0.15);
      border-radius: 15px;
      padding: 20px;
      margin-top: 20px;
      border: 1px solid var(--border-color);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      position: relative;
      overflow: hidden;
    }
    
    /* Efek cahaya untuk price calculator */
    .price-calculator::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.05),
        transparent
      );
      transform: rotate(45deg);
      transition: all 0.6s;
      pointer-events: none;
    }
    
    .price-calculator:hover::before {
      animation: shine 0.5s ease-in-out;
    }
    
    .price-row {
      display: flex;
      justify-content: space-between;
      padding: 10px 0;
      border-bottom: 1px solid var(--border-color);
      position: relative;
      z-index: 1;
    }
    
    .price-row:last-child {
      border-bottom: none;
      font-weight: 600;
      font-size: 18px;
      margin-top: 10px;
      padding-top: 15px;
      border-top: 2px solid var(--border-color);
    }
    
    .price-label {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    
    .price-value {
      font-weight: 600;
    }
    
    /* Payment method styles - Liquid Glass Effect */
    .payment-methods {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 15px;
    }
    
    .payment-method {
      position: relative;
      padding: 20px 15px;
      border-radius: 15px;
      background: rgba(255, 255, 255, 0.15);
      border: 2px solid var(--border-color);
      cursor: pointer;
      transition: var(--transition);
      text-align: center;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      overflow: hidden;
    }
    
    /* Efek cahaya untuk payment method */
    .payment-method::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.1),
        transparent
      );
      transform: rotate(45deg);
      transition: all 0.6s;
      pointer-events: none;
    }
    
    .payment-method:hover::before {
      animation: shine 0.5s ease-in-out;
    }
    
    .payment-method:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    .payment-method.selected {
      border-color: var(--primary-color);
      background: rgba(0, 119, 182, 0.15);
    }
    
    .payment-method input {
      position: absolute;
      opacity: 0;
      cursor: pointer;
    }
    
    .payment-icon {
      font-size: 28px;
      margin-bottom: 10px;
      color: var(--primary-color);
      position: relative;
      z-index: 1;
    }
    
    .payment-name {
      font-weight: 600;
      font-size: 16px;
      position: relative;
      z-index: 1;
    }
    
    /* Form actions */
    .form-actions {
      display: flex;
      gap: 15px;
      margin-top: 30px;
    }
    
    .btn {
      flex: 1;
      padding: 15px 20px;
      border-radius: 12px;
      border: none;
      font-weight: 600;
      font-size: 16px;
      cursor: pointer;
      transition: var(--transition);
      text-align: center;
      text-decoration: none;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
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
      background: linear-gradient(135deg, #ff4757, #ff3838);
      color: white;
    }
    
    .btn-secondary:hover {
      background: linear-gradient(135deg, #ff3838, #d63031);
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(255, 71, 87, 0.3);
    }
    
    /* Order summary - Liquid Glass Effect */
    .order-summary {
      flex: 1;
      background: var(--card-bg);
      border-radius: 20px;
      box-shadow: var(--shadow);
      overflow: hidden;
      animation: fadeInUp 0.5s ease 0.2s both;
      border: 1px solid var(--border-color);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      position: relative;
      overflow: hidden;
    }
    
    /* Efek cahaya untuk order summary */
    .order-summary::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.1),
        transparent
      );
      transform: rotate(45deg);
      transition: all 0.6s;
      pointer-events: none;
    }
    
    .order-summary:hover::before {
      animation: shine 0.5s ease-in-out;
    }
    
    .summary-header {
      background: linear-gradient(135deg, var(--secondary-color), var(--accent-color));
      color: white;
      padding: 20px;
      position: relative;
      z-index: 1;
    }
    
    .summary-header h3 {
      font-size: 20px;
      font-weight: 600;
      margin-bottom: 5px;
    }
    
    .summary-body {
      padding: 20px;
      position: relative;
      z-index: 1;
    }
    
    .summary-item {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid var(--border-color);
    }
    
    .summary-item:last-child {
      border-bottom: none;
      font-weight: 600;
      font-size: 18px;
      margin-top: 10px;
      padding-top: 15px;
      border-top: 2px solid var(--border-color);
      color: var(--primary-color);
    }
    
    .summary-label {
      font-weight: 500;
    }
    
    .summary-value {
      font-weight: 600;
    }
    
    /* Error message - Liquid Glass Effect */
    .error-message {
      background: rgba(239, 71, 111, 0.15);
      color: var(--error-color);
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
      border: 1px solid rgba(239, 71, 111, 0.2);
      display: flex;
      align-items: center;
      gap: 10px;
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      position: relative;
      overflow: hidden;
    }
    
    /* Efek cahaya untuk error message */
    .error-message::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(239, 71, 111, 0.05),
        transparent
      );
      transform: rotate(45deg);
      transition: all 0.6s;
      pointer-events: none;
    }
    
    .error-message:hover::before {
      animation: shine 0.5s ease-in-out;
    }
    
    .error-message i {
      font-size: 20px;
      position: relative;
      z-index: 1;
    }
    
    .error-message span {
      position: relative;
      z-index: 1;
    }
    
    /* Success notification */
    .success-notification {
      position: fixed;
      top: 100px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(135deg, #28a745, #20c997);
      color: white;
      padding: 15px 25px;
      border-radius: 10px;
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      z-index: 2000;
      animation: fadeInOut 3s ease-in-out;
    }

    @keyframes fadeInOut {
      0% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
      20% { opacity: 1; transform: translateX(-50%) translateY(0); }
      80% { opacity: 1; transform: translateX(-50%) translateY(0); }
      100% { opacity: 0; transform: translateX(-50%) translateY(-20px); }
    }
    
    /* Theme toggle - Liquid Glass Effect */
    .theme-toggle {
      position: fixed;
      bottom: 20px;
      right: 20px;
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: rgba(0, 119, 182, 0.7);
      color: white;
      border: none;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      box-shadow: var(--shadow);
      transition: var(--transition);
      z-index: 1000;
      backdrop-filter: blur(5px);
      -webkit-backdrop-filter: blur(5px);
      border: 1px solid var(--border-color);
      overflow: hidden;
    }
    
    /* Efek cahaya untuk theme toggle */
    .theme-toggle::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: linear-gradient(
        45deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
      );
      transform: rotate(45deg);
      transition: all 0.6s;
      pointer-events: none;
    }
    
    .theme-toggle:hover::before {
      animation: shine 0.5s ease-in-out;
    }
    
    .theme-toggle:hover {
      transform: scale(1.1);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }
    
    /* Animations */
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
    
    /* Responsive styles */
    @media (max-width: 992px) {
      .main-container {
        flex-direction: column;
      }
      
      .form-row {
        flex-direction: column;
        gap: 0;
      }
    }
    
    @media (max-width: 768px) {
      .edit-container, .order-summary {
        width: 95%;
        margin: 20px auto;
      }
      
      .form-actions {
        flex-direction: column;
      }
      
      .checkbox-group, .payment-methods {
        grid-template-columns: 1fr;
      }
      
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

  <!-- Header - Navbar seperti di halaman tickets.php -->
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
      <button onclick="goHome()">Home</button>
      <button onclick="goTickets()" class="active">Pemesanan</button>
      <button onclick="goPreview()">Galeri</button>
      <button onclick="scrollToSection('obyek-wisata')">Obyek Wisata</button>
      <button onclick="scrollToSection('fasilitas-wisata')">Fasilitas Wisata</button>
      <button onclick="scrollToSection('about')">About</button>
      
      <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'developer', 'dev_master'])): ?>
        <button onclick="window.location.href='admin.php'">Admin Panel</button>
      <?php endif; ?>
    </nav>

    <nav class="auth-nav" id="authNav">
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
    </nav>
  </header>

  <div class="main-container">
    <!-- Edit Form -->
    <div class="edit-container">
      <div class="edit-header">
        <h2><i class="fas fa-edit"></i> Edit Pesanan #<?= $order['id'] ?></h2>
        <p>Perbarui detail pesanan Anda sesuai kebutuhan</p>
      </div>
      
      <div class="edit-body">
        <?php if (isset($error)): ?>
          <div class="error-message">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= $error ?></span>
          </div>
        <?php endif; ?>
        
        <form method="post" action="" id="editForm">
          <!-- Detail Perjalanan -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-route"></i>
              <span>Detail Perjalanan</span>
            </div>
            
            <div class="form-row">
              <div class="form-group">
                <label for="participant_count">Jumlah Peserta</label>
                <input type="number" id="participant_count" name="participant_count" min="1" value="<?= $order['participant_count'] ?>" required onchange="calculatePrice()">
              </div>
              
              <div class="form-group">
                <label for="travel_days">Lama Perjalanan</label>
                <input type="number" id="travel_days" name="travel_days" min="1" value="<?= $order['travel_days'] ?>" required onchange="calculatePrice()">
                <small>hari</small>
              </div>
            </div>
            
            <div class="form-group">
              <label for="travel_date">Tanggal Perjalanan</label>
              <input type="date" id="travel_date" name="travel_date" value="<?= $order['travel_date'] ?>" required>
            </div>
          </div>
          
          <!-- Paket Perjalanan -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-suitcase"></i>
              <span>Paket Perjalanan</span>
            </div>
            
            <div class="checkbox-group">
              <div class="checkbox-item <?= $order['accommodation'] ? 'checked' : '' ?>" onclick="toggleCheckbox(this, 'accommodation')">
                <input type="checkbox" id="accommodation" name="accommodation" value="1" <?= $order['accommodation'] ? 'checked' : '' ?> onchange="calculatePrice()">
                <div class="checkbox-label">
                  <div class="checkbox-left">
                    <div class="checkbox-icon">
                      <?php if ($order['accommodation']): ?>
                        <i class="fas fa-check"></i>
                      <?php endif; ?>
                    </div>
                    <span class="checkbox-text">Penginapan</span>
                  </div>
                  <div class="checkbox-price">Rp. 100.000</div>
                </div>
              </div>
              
              <div class="checkbox-item <?= $order['transportation'] ? 'checked' : '' ?>" onclick="toggleCheckbox(this, 'transportation')">
                <input type="checkbox" id="transportation" name="transportation" value="1" <?= $order['transportation'] ? 'checked' : '' ?> onchange="calculatePrice()">
                <div class="checkbox-label">
                  <div class="checkbox-left">
                    <div class="checkbox-icon">
                      <?php if ($order['transportation']): ?>
                        <i class="fas fa-check"></i>
                      <?php endif; ?>
                    </div>
                    <span class="checkbox-text">Transportasi</span>
                  </div>
                  <div class="checkbox-price">Rp. 120.000</div>
                </div>
              </div>
              
              <div class="checkbox-item <?= $order['food'] ? 'checked' : '' ?>" onclick="toggleCheckbox(this, 'food')">
                <input type="checkbox" id="food" name="food" value="1" <?= $order['food'] ? 'checked' : '' ?> onchange="calculatePrice()">
                <div class="checkbox-label">
                  <div class="checkbox-left">
                    <div class="checkbox-icon">
                      <?php if ($order['food']): ?>
                        <i class="fas fa-check"></i>
                      <?php endif; ?>
                    </div>
                    <span class="checkbox-text">Makanan</span>
                  </div>
                  <div class="checkbox-price">Rp. 65.000</div>
                </div>
              </div>
            </div>
            
            <div class="price-calculator">
              <div class="price-row">
                <div class="price-label">
                  <i class="fas fa-users"></i>
                  <span>Jumlah Peserta</span>
                </div>
                <div class="price-value" id="calc-participants"><?= $order['participant_count'] ?></div>
              </div>
              <div class="price-row">
                <div class="price-label">
                  <i class="fas fa-calendar-days"></i>
                  <span>Lama Perjalanan</span>
                </div>
                <div class="price-value" id="calc-days"><?= $order['travel_days'] ?> hari</div>
              </div>
              <div class="price-row" id="calc-accommodation" style="<?= $order['accommodation'] ? '' : 'display:none' ?>">
                <div class="price-label">
                  <i class="fas fa-bed"></i>
                  <span>Penginapan</span>
                </div>
                <div class="price-value">Rp <?= number_format(100000 * $order['participant_count'] * $order['travel_days'], 0, ',', '.') ?></div>
              </div>
              <div class="price-row" id="calc-transportation" style="<?= $order['transportation'] ? '' : 'display:none' ?>">
                <div class="price-label">
                  <i class="fas fa-bus"></i>
                  <span>Transportasi</span>
                </div>
                <div class="price-value">Rp <?= number_format(120000 * $order['participant_count'] * $order['travel_days'], 0, ',', '.') ?></div>
              </div>
              <div class="price-row" id="calc-food" style="<?= $order['food'] ? '' : 'display:none' ?>">
                <div class="price-label">
                  <i class="fas fa-utensils"></i>
                  <span>Makanan</span>
                </div>
                <div class="price-value">Rp <?= number_format(65000 * $order['participant_count'] * $order['travel_days'], 0, ',', '.') ?></div>
              </div>
              <div class="price-row">
                <div class="price-label">
                  <i class="fas fa-tag"></i>
                  <span>Total Paket</span>
                </div>
                <div class="price-value" id="calc-package-total">Rp <?= number_format($order['package_total'], 0, ',', '.') ?></div>
              </div>
            </div>
          </div>
          
          <!-- Metode Pembayaran -->
          <div class="form-section">
            <div class="section-title">
              <i class="fas fa-credit-card"></i>
              <span>Metode Pembayaran</span>
            </div>
            
            <div class="payment-methods">
              <div class="payment-method <?= $order['payment_method'] == 'Gopay' ? 'selected' : '' ?>" onclick="selectPayment(this, 'Gopay')">
                <input type="radio" name="payment_method" value="Gopay" <?= $order['payment_method'] == 'Gopay' ? 'checked' : '' ?>>
                <div class="payment-icon">
                  <i class="fas fa-wallet"></i>
                </div>
                <div class="payment-name">Gopay</div>
              </div>
              
              <div class="payment-method <?= $order['payment_method'] == 'OVO' ? 'selected' : '' ?>" onclick="selectPayment(this, 'OVO')">
                <input type="radio" name="payment_method" value="OVO" <?= $order['payment_method'] == 'OVO' ? 'checked' : '' ?>>
                <div class="payment-icon">
                  <i class="fas fa-wallet"></i>
                </div>
                <div class="payment-name">OVO</div>
              </div>
              
              <div class="payment-method <?= $order['payment_method'] == 'Dana' ? 'selected' : '' ?>" onclick="selectPayment(this, 'Dana')">
                <input type="radio" name="payment_method" value="Dana" <?= $order['payment_method'] == 'Dana' ? 'checked' : '' ?>>
                <div class="payment-icon">
                  <i class="fas fa-wallet"></i>
                </div>
                <div class="payment-name">Dana</div>
              </div>
              
              <div class="payment-method <?= $order['payment_method'] == 'Transfer Bank' ? 'selected' : '' ?>" onclick="selectPayment(this, 'Transfer Bank')">
                <input type="radio" name="payment_method" value="Transfer Bank" <?= $order['payment_method'] == 'Transfer Bank' ? 'checked' : '' ?>>
                <div class="payment-icon">
                  <i class="fas fa-building-columns"></i>
                </div>
                <div class="payment-name">Transfer Bank</div>
              </div>
            </div>
          </div>
          
          <div class="form-actions">
            <a href="tickets.php" class="btn btn-secondary">
              <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="btn btn-primary">
              <i class="fas fa-save"></i> Simpan Perubahan
            </button>
          </div>
        </form>
      </div>
    </div>
    
    <!-- Order Summary -->
    <div class="order-summary">
      <div class="summary-header">
        <h3><i class="fas fa-receipt"></i> Ringkasan Pesanan</h3>
      </div>
      
      <div class="summary-body">
        <div class="summary-item">
          <div class="summary-label">ID Pesanan</div>
          <div class="summary-value">#<?= $order['id'] ?></div>
        </div>
        
        <div class="summary-item">
          <div class="summary-label">Jenis Tiket</div>
          <div class="summary-value"><?= $order['ticket_type'] ?></div>
        </div>
        
        <div class="summary-item">
          <div class="summary-label">Harga Tiket</div>
          <div class="summary-value">Rp <?= number_format($order['ticket_price'], 0, ',', '.') ?></div>
        </div>
        
        <div class="summary-item">
          <div class="summary-label">Jumlah Peserta</div>
          <div class="summary-value" id="summary-participants"><?= $order['participant_count'] ?></div>
        </div>
        
        <div class="summary-item">
          <div class="summary-label">Lama Perjalanan</div>
          <div class="summary-value" id="summary-days"><?= $order['travel_days'] ?> hari</div>
        </div>
        
        <div class="summary-item">
          <div class="summary-label">Harga Paket</div>
          <div class="summary-value" id="summary-package-total">Rp <?= number_format($order['package_total'], 0, ',', '.') ?></div>
        </div>
        
        <div class="summary-item">
          <div class="summary-label">Total Pembayaran</div>
          <div class="summary-value" id="summary-total">Rp <?= number_format($order['total_price'], 0, ',', '.') ?></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Theme Toggle -->
  <button class="theme-toggle" id="themeToggle">
    <i class="fas fa-moon" id="themeIcon"></i>
  </button>

  <script>
    // Toggle menu mobile
    document.getElementById('menuToggle').addEventListener('click', function() {
      document.getElementById('mainNav').classList.toggle('active');
      document.getElementById('authNav').classList.toggle('active');
    });
    
    // Toggle checkbox
    function toggleCheckbox(element, checkboxId) {
      const checkbox = document.getElementById(checkboxId);
      checkbox.checked = !checkbox.checked;
      
      if (checkbox.checked) {
        element.classList.add('checked');
        element.querySelector('.checkbox-icon').innerHTML = '<i class="fas fa-check"></i>';
      } else {
        element.classList.remove('checked');
        element.querySelector('.checkbox-icon').innerHTML = '';
      }
      
      calculatePrice();
    }
    
    // Select payment method
    function selectPayment(element, method) {
      // Remove selected class from all payment methods
      document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
      });
      
      // Add selected class to clicked element
      element.classList.add('selected');
      
      // Check the radio button
      document.querySelector(`input[name="payment_method"][value="${method}"]`).checked = true;
    }
    
    // Calculate price
    function calculatePrice() {
      const participants = parseInt(document.getElementById('participant_count').value) || 1;
      const days = parseInt(document.getElementById('travel_days').value) || 1;
      const accommodation = document.getElementById('accommodation').checked;
      const transportation = document.getElementById('transportation').checked;
      const food = document.getElementById('food').checked;
      
      // Update calculator
      document.getElementById('calc-participants').textContent = participants;
      document.getElementById('calc-days').textContent = days + ' hari';
      
      // Show/hide package items
      document.getElementById('calc-accommodation').style.display = accommodation ? 'flex' : 'none';
      document.getElementById('calc-transportation').style.display = transportation ? 'flex' : 'none';
      document.getElementById('calc-food').style.display = food ? 'flex' : 'none';
      
      // Calculate package total
      let packageTotal = 0;
      if (accommodation) packageTotal += 100000;
      if (transportation) packageTotal += 120000;
      if (food) packageTotal += 65000;
      packageTotal *= participants * days;
      
      // Update calculator
      if (accommodation) {
        document.getElementById('calc-accommodation').querySelector('.price-value').textContent = 'Rp ' + (100000 * participants * days).toLocaleString('id-ID');
      }
      if (transportation) {
        document.getElementById('calc-transportation').querySelector('.price-value').textContent = 'Rp ' + (120000 * participants * days).toLocaleString('id-ID');
      }
      if (food) {
        document.getElementById('calc-food').querySelector('.price-value').textContent = 'Rp ' + (65000 * participants * days).toLocaleString('id-ID');
      }
      document.getElementById('calc-package-total').textContent = 'Rp ' + packageTotal.toLocaleString('id-ID');
      
      // Update summary
      document.getElementById('summary-participants').textContent = participants;
      document.getElementById('summary-days').textContent = days + ' hari';
      document.getElementById('summary-package-total').textContent = 'Rp ' + packageTotal.toLocaleString('id-ID');
      
      const ticketPrice = <?= $order['ticket_price'] ?>;
      const totalPrice = ticketPrice + packageTotal;
      document.getElementById('summary-total').textContent = 'Rp ' + totalPrice.toLocaleString('id-ID');
    }
    
    // Theme toggle
    const themeToggle = document.getElementById('themeToggle');
    const themeIcon = document.getElementById('themeIcon');
    
    // Check for saved theme preference
    const currentTheme = localStorage.getItem('theme');
    if (currentTheme === 'dark') {
      document.body.classList.add('dark-mode');
      themeIcon.className = 'fas fa-sun';
    }
    
    themeToggle.addEventListener('click', function() {
      document.body.classList.toggle('dark-mode');
      
      if (document.body.classList.contains('dark-mode')) {
        themeIcon.className = 'fas fa-sun';
        localStorage.setItem('theme', 'dark');
      } else {
        themeIcon.className = 'fas fa-moon';
        localStorage.setItem('theme', 'light');
      }
    });
    
    // Navigation
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
      // Jika berada di halaman form_edit_pesanan.php, pindah ke dfscenery.php
      window.location.href = "dfscenery.php#" + sectionId;
    }
    
    // Initialize price calculator
    document.addEventListener('DOMContentLoaded', function() {
      calculatePrice();
    });
  </script>
</body>
</html>