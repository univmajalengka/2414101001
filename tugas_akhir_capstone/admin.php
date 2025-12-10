<?php
session_start();
include 'db.php';

// =========================
//  Refresh role dari database
// =========================
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT username, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];
    }
}

 $allowed_roles = ['admin', 'developer', 'dev_master'];
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'] ?? '', $allowed_roles)) {
    header("Location: dfscenery.php");
    exit;
}

 $username = $_SESSION['username'];

// Fungsi untuk mendapatkan avatar Vivi yang aktif
function getViviAvatar() {
    $avatar_file = 'vivi_avatar/current_avatar.txt';
    if (file_exists($avatar_file)) {
        $avatar_name = trim(file_get_contents($avatar_file));
        return !empty($avatar_name) ? 'vivi_avatar/' . $avatar_name : 'binigua.jpg';
    }
    return 'binigua.jpg'; // Default
}

 $vivi_avatar_path = getViviAvatar();

// ==========================
// AI VIVI: HANDLE TEACH AI REQUEST
// ==========================
 $success_message = '';
 $error_message = '';
if (isset($_POST['teach_ai'])) {
    $question = $_POST['question'] ?? '';
    $answer = $_POST['answer'] ?? '';
    $keywords = $_POST['keywords'] ?? '';
    $confidence = $_POST['confidence'] ?? 0.7; // Default confidence

    if (!empty(trim($question)) && !empty(trim($answer))) {
        // If keywords are not provided, extract them from question
        if (empty(trim($keywords))) {
            function extractKeywords_local($question) {
                $stopWords = ['apa', 'siapa', 'dimana', 'kapan', 'mengapa', 'bagaimana', 'berapa', 'adalah', 'yaitu', 'yang', 'dari', 'ke', 'pada', 'di', 'dan', 'atau', 'tapi', 'juga', 'ini', 'itu', 'saya', 'kamu', 'kita', 'mereka', 'dia', 'kamu', 'kalian'];
                $words = preg_split('/[\s,?.!;:]+/', $question);
                $keywords = [];
                foreach ($words as $word) {
                    $word = strtolower(trim($word));
                    if (strlen($word) > 2 && !in_array($word, $stopWords)) {
                        $keywords[] = $word;
                    }
                }
                return $keywords;
            }
            $extractedKeywords = extractKeywords_local($question);
            $keywordsStr = implode(', ', $extractedKeywords);
        } else {
            $keywordsStr = $keywords;
        }

        $stmt = $conn->prepare("INSERT INTO knowledge_base (keywords, question, answer, confidence, source, created_at, updated_at, verified) VALUES (?, ?, ?, ?, 'manual', NOW(), NOW(), TRUE)");
        $stmt->bind_param("sssd", $keywordsStr, $question, $answer, $confidence);
        
        if ($stmt->execute()) {
            // Mark as related unknown question as answered, if it exists
            if (isset($_POST['unknown_question_id'])) {
                $unknown_id = intval($_POST['unknown_question_id']);
                $updateStmt = $conn->prepare("UPDATE unknown_questions SET answered = TRUE, answer = ? WHERE id = ?");
                $updateStmt->bind_param("si", $answer, $unknown_id);
                $updateStmt->execute();
            }
            $success_message = "Pengetahuan baru berhasil ditambahkan untuk Vivi!";
        } else {
            $error_message = "Gagal menambahkan pengetahuan. Error: " . $conn->error;
        }
    } else {
        $error_message = "Pertanyaan dan jawaban tidak boleh kosong.";
    }
}

// =========================
// Pagination Tiket
// =========================
 $limit = 10;
 $page  = isset($_GET['p']) && is_numeric($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
 $offset = ($page - 1) * $limit;

 $totalRes  = $conn->query("SELECT COUNT(*) AS total FROM tickets");
 $totalRows = $totalRes ? (int)$totalRes->fetch_assoc()['total'] : 0;
 $totalPages = max(1, ceil($totalRows / $limit));

 $tickets = $conn->query("SELECT * FROM tickets ORDER BY id DESC LIMIT $limit OFFSET $offset");

// ==========================
// UPDATE PROMO (admin/dev/dev_master)
// ==========================
if (isset($_POST['update_promo'])) {
    $promo_id = intval($_POST['promo_id']);
    $price = $_POST['price'];
    $discount = $_POST['discount'];
    $desc = $_POST['description'];
    $exp = $_POST['expires'];

    $stmt = $conn->prepare("UPDATE promo_ticket
                            SET price=?, discount=?, description=?, expires=?
                            WHERE id=?");
    $stmt->bind_param("dsssi", $price, $discount, $desc, $exp, $promo_id);
    $stmt->execute();
}

// ==========================
// FETCH DATA FOR AI VIVI TAB
// ==========================
 $unknown_questions_res = $conn->query("SELECT * FROM unknown_questions WHERE answered = FALSE ORDER BY date_asked DESC");
 $knowledge_base_res = $conn->query("SELECT * FROM knowledge_base ORDER BY created_at DESC LIMIT 50");

// Ambil semua user
 $users = $conn->query("SELECT id, username, email, role FROM users ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Admin Panel - DF Scenery</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* === GLOBAL === */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #d7efff, #ffffff);
    color: #003049;
    overflow-x: hidden;
}

/* === BACKGROUND DECORATION === */
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
    background: #0077b6;
    top: -100px;
    right: -100px;
}

.bg-circle:nth-child(2) {
    width: 250px;
    height: 250px;
    background: #00b4d8;
    bottom: -50px;
    left: -50px;
}

.bg-circle:nth-child(3) {
    width: 200px;
    height: 200px;
    background: #90e0ef;
    top: 50%;
    left: 30%;
}

/* === HEADER === */
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

/* === NAV BUTTONS === */
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

/* SPECIAL BUTTON: DevMaster */
.btn-devmaster {
    background: linear-gradient(135deg, #ff006e, #d00052);
    color: white;
    border: none;
    box-shadow: 0 4px 12px rgba(255,0,110,0.35);
}
.btn-devmaster:hover {
    background: linear-gradient(135deg, #ff2a85, #d7005f);
}

/* === MAIN CONTAINER === */
.container {
    width: 94%;
    max-width: 1100px;
    margin: 60px auto;
    padding-bottom: 40px;
}

/* === CARD / SECTION === */
.section-card {
    background: rgba(255, 255, 255, 0.95);
    padding: 35px;
    border-radius: 22px;
    margin-bottom: 45px;
    box-shadow: 0 8px 35px rgba(0,0,0,0.12);
    backdrop-filter: blur(10px);
    animation: fadeUp 0.4s ease;
    border: 1px solid rgba(255,255,255,0.3);
}

.section-card h2 {
    margin: 0 0 25px 0;
    text-align: center;
    color: #0077b6;
    font-size: 24px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.section-card h2 i {
    font-size: 22px;
}

/* === TABLE === */
.table-container {
    overflow-x: auto;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: #ffffff;
}

th {
    background: linear-gradient(135deg, #0077b6, #0096c7);
    color: white;
    font-weight: 600;
    padding: 14px 18px;
    font-size: 15px;
    text-align: left;
}

td {
    padding: 14px 18px;
    border-bottom: 1px solid #eef3f7;
}

tr:hover td {
    background: #f4fbff;
    transition: 0.2s;
}

tr:last-child td {
    border-bottom: none;
}

/* === ROLE BADGE === */
.badge {
    padding: 7px 14px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: bold;
    display: inline-block;
    color: white;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
}

.admin { background: linear-gradient(135deg, #06d6a0, #04b686); }
.developer { background: linear-gradient(135deg, #ff006e, #d00052); }
.dev_master { background: linear-gradient(135deg, #7b2cbf, #6a1b9a); }
.user { background: linear-gradient(135deg, #6c757d, #5a6268); }

/* === PAGINATION === */
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin-top: 25px;
}

.pagination a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: #0077b6;
    color: white;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.22s;
}

.pagination a:hover {
    background: #005f87;
    transform: translateY(-3px);
}

.pagination .active {
    background: #00b4d8;
}

/* === FORMS === */
.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #0077b6;
}

.form-container input,
.form-container textarea {
    width: 100%;
    padding: 12px;
    border-radius: 12px;
    border: 1px solid #ccc;
    outline: none;
    font-size: 14px;
    font-family: "Poppins", sans-serif;
    transition: all 0.2s;
}

.form-container input:focus,
.form-container textarea:focus {
    border-color: #00a6fb;
    box-shadow: 0 0 6px rgba(0,166,251,0.4);
}

/* BUTTON EDIT / SAVE / TEACH */
.edit-btn, .teach-btn {
    background: #0096c7;
    color: white;
    padding: 9px 18px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    transition: all 0.25s;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
}
.edit-btn:hover, .teach-btn:hover {
    background: #0077b6;
    transform: translateY(-2px);
}

.save-btn {
    background: linear-gradient(135deg, #06d6a0, #04b686);
    color: white;
    padding: 12px 20px;
    border-radius: 12px;
    border: none;
    cursor: pointer;
    font-weight: 700;
    transition: all 0.25s;
    display: flex;
    align-items: center;
    gap: 8px;
}
.save-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 15px rgba(6, 214, 160, 0.3);
}

/* Success/Error messages */
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 12px;
    color: white;
}
.alert-success {
    background: linear-gradient(135deg, #06d6a0, #04b686);
}
.alert-error {
    background: linear-gradient(135deg, #f94144, #d62828);
}

/* === TABS === */
.tabs {
    display: flex;
    margin-bottom: 25px;
    border-bottom: 2px solid rgba(0, 119, 182, 0.1);
    flex-wrap: wrap;
}

.tab {
    padding: 12px 20px;
    cursor: pointer;
    font-weight: 600;
    color: #555;
    border-bottom: 3px solid transparent;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tab:hover {
    color: #0077b6;
}

.tab.active {
    color: #0077b6;
    border-bottom: 3px solid #0077b6;
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

/* === STATS CARDS === */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-icon.users { background: linear-gradient(135deg, #0077b6, #0096c7); }
.stat-icon.tickets { background: linear-gradient(135deg, #06d6a0, #04b686); }
.stat-icon.revenue { background: linear-gradient(135deg, #ff006e, #d00052); }
.stat-icon.promos { background: linear-gradient(135deg, #7b2cbf, #6a1b9a); }
.stat-icon.ai { background: linear-gradient(135deg, #f77f00, #fcbf49); }


.stat-info h3 {
    font-size: 14px;
    color: #555;
    margin-bottom: 5px;
}

.stat-info p {
    font-size: 24px;
    font-weight: 700;
    color: #333;
}

/* === EMPTY STATE === */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: #777;
}

.empty-state i {
    font-size: 48px;
    color: #ccc;
    margin-bottom: 15px;
}

/* === ANIMATIONS === */
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

/* === AI PROFILE SECTION === */
.ai-profile-container {
    display: flex;
    align-items: center;
    gap: 30px;
    padding: 25px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 20px;
    margin-bottom: 30px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.05);
}

.ai-profile-avatar {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #0077b6;
    box-shadow: 0 8px 20px rgba(0, 119, 182, 0.2);
}

.ai-profile-info {
    flex-grow: 1;
}

.ai-profile-name {
    font-size: 28px;
    font-weight: 700;
    color: #0077b6;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.ai-profile-name i {
    font-size: 24px;
}

.ai-profile-description {
    font-size: 16px;
    line-height: 1.6;
    color: #555;
    margin-bottom: 15px;
}

.ai-profile-stats {
    display: flex;
    gap: 20px;
}

.ai-profile-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 15px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

.ai-profile-stat-value {
    font-size: 20px;
    font-weight: 700;
    color: #0077b6;
}

.ai-profile-stat-label {
    font-size: 12px;
    color: #777;
}

/* === RESPONSIVE (MOBILE-FRIENDLY) === */
@media (max-width: 768px) {
    header {
        flex-direction: column;
        gap: 15px;
        padding: 15px;
    }
    
    header h1 {
        font-size: 1.2rem;
    }
    
    nav {
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
    }
    
    nav button {
        font-size: 14px;
        padding: 8px 14px;
    }
    
    .container {
        width: 95%;
        margin: 20px auto;
        padding-bottom: 20px;
    }
    
    .section-card {
        padding: 20px;
        margin-bottom: 25px;
    }
    
    .section-card h2 {
        font-size: 1.1rem;
        margin-bottom: 15px;
    }

    .tabs {
        margin-bottom: 15px;
    }
    
    .tab {
        padding: 10px 15px;
        font-size: 14px;
        flex-grow: 1; /* Make tabs take equal width */
        text-align: center;
    }
    
    .stats-container {
        grid-template-columns: 1fr; /* 1 column on mobile */
        gap: 15px;
    }
    
    .stat-card {
        padding: 15px;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .stat-info p {
        font-size: 1.2rem;
    }
    
    .table-container {
        overflow-x: auto; /* Allow horizontal scrolling for tables */
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
    }
    
    th, td {
        padding: 10px;
        font-size: 13px;
        white-space: nowrap; /* Prevent text from wrapping in table cells */
    }
    
    .form-group {
        margin-bottom: 12px;
    }
    
    .form-container input,
    .form-container textarea {
        padding: 10px;
        font-size: 14px;
    }
    
    .save-btn, .edit-btn, .teach-btn {
        width: 100%;
        justify-content: center;
        padding: 12px;
        margin-top: 10px;
    }
    
    .ai-profile-container {
        flex-direction: column;
        text-align: center;
    }
    
    .ai-profile-avatar {
        margin-bottom: 15px;
    }
    
    .pagination a {
        width: 35px;
        height: 35px;
        font-size: 14px;
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

<header>
    <h1><i class="fas fa-cogs"></i> DFScenery Admin</h1>
    <nav>
        <button class="btn-home" onclick="location.href='dfscenery.php'">
            <i class="fas fa-home"></i> Home
        </button>
        <button class="btn-komik" onclick="location.href='admin_dfkomik.php'">
            <i class="fas fa-book"></i> Admin DFKomik
        </button>

        <?php if (in_array($_SESSION['role'], ['admin','developer','dev_master'])): ?>
            <button class="btn-devmaster" onclick="location.href='manage_users.php'">
                <i class="fas fa-user-shield"></i> Dev Master Panel
            </button>
        <?php endif; ?>
    </nav>
</header>

<div class="container">
    <!-- STATS CARDS -->
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon users">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-info">
                <h3>Total Pengguna</h3>
                <p><?= $users ? $users->num_rows : 0 ?></p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon tickets">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-info">
                <h3>Total Tiket Terjual</h3>
                <p><?= $totalRows ?></p>
            </div>
        </div>
        
         <div class="stat-card">
            <div class="stat-icon revenue">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-info">
                <h3>Total Pendapatan</h3>
                <p>
                    <?php 
                    $revenueRes = $conn->query("SELECT SUM(total_price) AS total FROM tickets");
                    $revenue = $revenueRes ? $revenueRes->fetch_assoc()['total'] : 0;
                    echo "Rp " . number_format($revenue, 0, ',', '.');
                    ?>
                </p>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon promos">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-info">
                <h3>Aktif Promo</h3>
                <p>
                    <?php 
                    $promoRes = $conn->query("SELECT COUNT(*) AS total FROM promo_ticket WHERE expires >= CURDATE()");
                    $activePromos = $promoRes ? $promoRes->fetch_assoc()['total'] : 0;
                    echo $activePromos;
                    ?>
                </p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon ai">
                <i class="fas fa-brain"></i>
            </div>
            <div class="stat-info">
                <h3>Pengetahuan Vivi</h3>
                <p>
                    <?php 
                    $knowledgeRes = $conn->query("SELECT COUNT(*) AS total FROM knowledge_base");
                    $totalKnowledge = $knowledgeRes ? $knowledgeRes->fetch_assoc()['total'] : 0;
                    echo $totalKnowledge;
                    ?>
                </p>
            </div>
        </div>
    </div>

    <!-- TABS -->
    <div class="tabs">
        <div class="tab active" onclick="switchTab('users')">
            <i class="fas fa-users"></i> Pengguna
        </div>
        <div class="tab" onclick="switchTab('tickets')">
            <i class="fas fa-ticket-alt"></i> Tiket
        </div>
        <div class="tab" onclick="switchTab('promos')">
            <i class="fas fa-tags"></i> Promo
        </div>
        <div class="tab" onclick="switchTab('ai-vivi')">
            <i class="fas fa-brain"></i> AI Vivi
        </div>
    </div>

    <!-- USER LIST TAB -->
    <div id="users-tab" class="tab-content active">
        <div class="section-card">
            <h2><i class="fas fa-users"></i> Daftar Pengguna</h2>

            <?php if ($users && $users->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                    <?php while ($u = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $u['id']; ?></td>
                        <td><?= $u['username']; ?></td>
                        <td><?= $u['email']; ?></td>
                        <td>
                            <span class="badge <?= $u['role']; ?>">
                                <?= ucfirst($u['role']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-users-slash"></i>
                    <p>Tidak ada user terdaftar.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- TICKETS TAB -->
    <div id="tickets-tab" class="tab-content">
        <div class="section-card">
            <h2><i class="fas fa-ticket-alt"></i> Riwayat Pembelian Tiket</h2>

            <?php if ($tickets && $tickets->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Total</th>
                    </tr>

                    <?php while ($t = $tickets->fetch_assoc()): ?>
                    <tr>
                        <td><?= $t['id']; ?></td>
                        <td><?= $t['name']; ?></td>
                        <td><?= $t['email']; ?></td>
                        <td><?= $t['ticket_type']; ?></td>
                        <td><?= $t['quantity']; ?></td>
                        <td>Rp <?= number_format($t['total_price'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>

            <!-- PAGINATION -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php
                $maxButtons = 5;
                $start = max(1, $page - floor($maxButtons / 2));
                $end   = min($totalPages, $start + $maxButtons - 1);

                if ($page > 1)
                    echo "<a href='?p=1'><i class='fas fa-angle-double-left'></i></a>";

                for ($i = $start; $i <= $end; $i++) {
                    $active = $i == $page ? "active" : "";
                    echo "<a class='$active' href='?p=$i'>$i</a>";
                }

                if ($page < $totalPages)
                    echo "<a href='?p=$totalPages'><i class='fas fa-angle-double-right'></i></a>";
                ?>
            </div>
            <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-ticket-alt"></i>
                    <p>Tidak ada transaksi ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- PROMOS TAB -->
    <div id="promos-tab" class="tab-content">
        <div class="section-card">
            <h2><i class="fas fa-tags"></i> Kelola Promo Tiket</h2>

            <?php
            $promoRes = $conn->query("SELECT * FROM promo_ticket");
            ?>

            <?php if ($promoRes && $promoRes->num_rows > 0): ?>
            <div class="table-container">
                <table>
                    <tr>
                        <th>Jenis Tiket</th>
                        <th>Harga</th>
                        <th>Diskon (%)</th>
                        <th>Deskripsi</th>
                        <th>Masa Berlaku</th>
                        <th>Aksi</th>
                    </tr>

                    <?php while ($p = $promoRes->fetch_assoc()): ?>
                    <tr>
                        <td><?= $p['ticket_type']; ?></td>
                        <td>Rp <?= number_format($p['price'], 0, ',', '.'); ?></td>
                        <td><?= $p['discount']; ?>%</td>
                        <td><?= $p['description']; ?></td>
                        <td><?= $p['expires']; ?></td>
                        <td>
                            <button class="edit-btn" onclick="toggleEdit('promo<?= $p['id']; ?>')">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                        </td>
                    </tr>

                    <tr id="promo<?= $p['id']; ?>" style="display:none;">
                        <td colspan="6">
                            <div class="form-container">
                                <form method="POST">
                                    <input type="hidden" name="promo_id" value="<?= $p['id']; ?>">
                                    <div class="form-group">
                                        <label><b>Harga Tiket</b></label>
                                        <input type="number" name="price" value="<?= $p['price']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label><b>Diskon (%)</b></label>
                                        <input type="number" name="discount" value="<?= $p['discount']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label><b>Deskripsi Promo</b></label>
                                        <textarea name="description" rows="3" required><?= $p['description']; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label><b>Masa Berlaku</b></label>
                                        <input type="date" name="expires" value="<?= $p['expires']; ?>" required>
                                    </div>
                                    <button type="submit" name="update_promo" class="save-btn">
                                        <i class="fas fa-save"></i> Simpan Perubahan
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </table>
            </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-tags"></i>
                    <p>Tidak ada data promo.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- AI VIVI TAB START -->
    <div id="ai-vivi-tab" class="tab-content">
        <!-- AI Profile Section -->
        <div class="section-card">
            <div class="ai-profile-container">
                <img src="<?= $vivi_avatar_path ?>" alt="Vivi Avatar" class="ai-profile-avatar">
                <div class="ai-profile-info">
                    <h2 class="ai-profile-name">
                        <i class="fas fa-robot"></i> Vivi AI Assistant
                    </h2>
                    <p class="ai-profile-description">
                        Vivi adalah asisten AI cerdas yang dirancang khusus untuk DFScenery. 
                        Saya siap membantu menjawab pertanyaan seputar wisata, tiket, dan informasi lainnya 
                        tentang DFScenery. Pengetahuan saya terus berkembang berkat kontribusi dari tim admin.
                    </p>
                    <div class="ai-profile-stats">
                        <div class="ai-profile-stat">
                            <div class="ai-profile-stat-value">
                                <?php 
                                $knowledgeRes = $conn->query("SELECT COUNT(*) AS total FROM knowledge_base");
                                $totalKnowledge = $knowledgeRes ? $knowledgeRes->fetch_assoc()['total'] : 0;
                                echo $totalKnowledge;
                                ?>
                            </div>
                            <div class="ai-profile-stat-label">Pengetahuan</div>
                        </div>
                        <div class="ai-profile-stat">
                            <div class="ai-profile-stat-value">
                                <?php 
                                $unknownRes = $conn->query("SELECT COUNT(*) AS total FROM unknown_questions WHERE answered = FALSE");
                                $totalUnknown = $unknownRes ? $unknownRes->fetch_assoc()['total'] : 0;
                                echo $totalUnknown;
                                ?>
                            </div>
                            <div class="ai-profile-stat-label">Pertanyaan Baru</div>
                        </div>
                        <div class="ai-profile-stat">
                            <div class="ai-profile-stat-value">24/7</div>
                            <div class="ai-profile-stat-label">Online</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tambah Pengetahuan Baru -->
        <div class="section-card">
            <h2><i class="fas fa-plus-circle"></i> Tambah Pengetahuan Baru untuk Vivi</h2>
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?= $success_message ?></div>
            <?php endif; ?>
            <?php if ($error_message): ?>
                <div class="alert alert-error"><?= $error_message ?></div>
            <?php endif; ?>
            <div class="form-container">
                <form method="POST" action="">
                    <input type="hidden" id="unknown_question_id" name="unknown_question_id">
                    <div class="form-group">
                        <label for="question"><b>Pertanyaan</b></label>
                        <input type="text" id="question" name="question" placeholder="Contoh: Apa itu DF Scenery?" required>
                    </div>
                    <div class="form-group">
                        <label for="answer"><b>Jawaban</b></label>
                        <textarea id="answer" name="answer" rows="5" placeholder="Jawaban yang akan diberikan Vivi..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="keywords"><b>Kata Kunci (Opsional, dipisahkan dengan koma)</b></label>
                        <input type="text" id="keywords" name="keywords" placeholder="Contoh: df scenery, wisata, tempat rekreasi">
                    </div>
                    <div class="form-group">
                        <label for="confidence"><b>Tingkat Kepercayaan (0.1 - 1.0)</b></label>
                        <input type="number" id="confidence" name="confidence" min="0.1" max="1" step="0.1" value="0.7" required>
                    </div>
                    <button type="submit" name="teach_ai" class="save-btn">
                        <i class="fas fa-graduation-cap"></i> Ajari Vivi
                    </button>
                </form>
            </div>
        </div>

        <!-- Pertanyaan yang Belum Dijawab -->
        <div class="section-card">
            <h2><i class="fas fa-question-circle"></i> Pertanyaan yang Belum Vivi Jawab</h2>
            <?php if ($unknown_questions_res && $unknown_questions_res->num_rows > 0): ?>
                <div class="table-container">
                    <table>
                        <tr>
                            <th>Pertanyaan</th>
                            <th>Tanggal Ditanyakan</th>
                            <th>Aksi</th>
                        </tr>
                        <?php while ($q = $unknown_questions_res->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($q['question']) ?></td>
                            <td><?= date('d M Y, H:i', strtotime($q['date_asked'])) ?></td>
                            <td>
                                <button class="teach-btn" onclick="fillTeachForm(<?= $q['id'] ?>, '<?= htmlspecialchars($q['question'], ENT_QUOTES) ?>')">
                                    <i class="fas fa-reply"></i> Ajari Jawaban
                                </button>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </table>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <p>Great! Tidak ada pertanyaan yang belum terjawab.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- AI VIVI TAB END -->

</div>

<script>
// Tab switching
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

// Toggle edit form
function toggleEdit(id) {
    let el = document.getElementById(id);
    el.style.display = (el.style.display === "none") ? "table-row" : "none";
}

// Fill "Teach AI" form with data from an unanswered question
function fillTeachForm(id, question) {
    document.getElementById('unknown_question_id').value = id;
    document.getElementById('question').value = question;
    document.getElementById('answer').focus();
    
    // Scroll to the form
    document.getElementById('ai-vivi-tab').scrollIntoView({ behavior: 'smooth' });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
});
</script>

</body>
</html>