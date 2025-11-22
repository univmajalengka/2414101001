<?php
// Memulai session di awal script, HANYA SATU KALI
session_start();

// Atur header untuk JSON
header('Content-Type: application/json');

// Fungsi untuk mengirim respons JSON dan keluar
function send_json_response($success, $message, $data = null) {
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
}

// Coba memuat file db.php
if (!file_exists('db.php')) {
    send_json_response(false, 'File konfigurasi database (db.php) tidak ditemukan.');
}
require_once 'db.php';

// Cek koneksi database
if (!isset($conn) || $conn->connect_error) {
    send_json_response(false, 'Koneksi ke database gagal. Periksa pengaturan di db.php.');
}

// Cek apakah pengguna adalah admin
function isAdmin() {
    $role = $_SESSION['role'] ?? 'user';
    return in_array($role, ['admin', 'developer', 'dev_master']);
}

// Fungsi setupKnowledgeBase yang lengkap untuk membuat semua tabel
function setupKnowledgeBase() {
    global $conn;
    
    // Tabel untuk menyimpan semua pesan di chatroom
    $conn->query("CREATE TABLE IF NOT EXISTS chat_messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        message TEXT NOT NULL,
        avatar_url VARCHAR(255) NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    
    // Tabel untuk daftar pengguna yang dibisukan (mute)
    $conn->query("CREATE TABLE IF NOT EXISTS muted_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        muted_by VARCHAR(50) NOT NULL,
        muted_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    )");

    // Tabel knowledge base
    $conn->query("CREATE TABLE IF NOT EXISTS knowledge_base (
        id INT AUTO_INCREMENT PRIMARY KEY,
        keywords TEXT NOT NULL,
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        confidence FLOAT DEFAULT 0.5,
        source VARCHAR(50) DEFAULT 'manual',
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL,
        verified BOOLEAN DEFAULT FALSE,
        usage_count INT DEFAULT 0
    )");
    
    // Tabel learning_logs
    $conn->query("CREATE TABLE IF NOT EXISTS learning_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        conversation_id INT NOT NULL,
        question TEXT NOT NULL,
        answer TEXT NOT NULL,
        feedback BOOLEAN NULL,
        analyzed BOOLEAN DEFAULT FALSE,
        created_at DATETIME NOT NULL
    )");
    
    // Tabel conversations
    $conn->query("CREATE TABLE IF NOT EXISTS conversations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user1 VARCHAR(50) NOT NULL,
        user2 VARCHAR(50) NOT NULL,
        start_time DATETIME NOT NULL,
        end_time DATETIME NULL
    )");
}

// Inisialisasi tabel knowledge base
setupKnowledgeBase();

 $action = $_GET['action'] ?? '';

try {
    switch ($action) {
        case 'fetch':
            fetchMessages();
            break;
        
        case 'fetch_all':
            fetchAllMessages();
            break;
        
        case 'send':
            sendMessage();
            break;
        
        case 'delete':
            if (isAdmin()) {
                deleteMessage();
            } else {
                send_json_response(false, 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            }
            break;
        
        case 'mute':
            if (isAdmin()) {
                muteUser();
            } else {
                send_json_response(false, 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            }
            break;
        
        case 'unmute':
            if (isAdmin()) {
                unmuteUser();
            } else {
                send_json_response(false, 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            }
            break;
        
        case 'ask_ai':
            askAI();
            break;
            
        case 'teach_ai':
            if (isAdmin()) {
                teachAI();
            } else {
                send_json_response(false, 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            }
            break;
            
        case 'verify_knowledge':
            if (isAdmin()) {
                verifyKnowledge();
            } else {
                send_json_response(false, 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            }
            break;
            
        case 'get_knowledge':
            if (isAdmin()) {
                getKnowledge();
            } else {
                send_json_response(false, 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            }
            break;
            
        case 'analyze_conversations':
            if (isAdmin()) {
                analyzeConversations();
            } else {
                send_json_response(false, 'Anda tidak memiliki izin untuk melakukan tindakan ini.');
            }
            break;
            
        case 'feedback_ai':
            feedbackAI();
            break;

        default:
            send_json_response(false, 'Aksi tidak valid.');
            break;
    }
} catch (Exception $e) {
    // Tangkap semua exception yang tidak tertangani di tempat lain
    send_json_response(false, 'Terjadi kesalahan server: ' . $e->getMessage());
}

function fetchMessages() {
    global $conn;
    $lastId = isset($_GET['last_id']) ? (int)$_GET['last_id'] : 0;

    $stmt = $conn->prepare("
        SELECT cm.id, cm.username, cm.message, cm.avatar_url, u.role 
        FROM chat_messages cm 
        LEFT JOIN users u ON cm.username = u.username 
        WHERE cm.id > ? 
        ORDER BY cm.id ASC
    ");
    $stmt->bind_param("i", $lastId);
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    send_json_response(true, 'Pesan berhasil diambil', ['messages' => $messages]);
}

function fetchAllMessages() {
    global $conn;

    $stmt = $conn->prepare("
        SELECT cm.id, cm.username, cm.message, cm.avatar_url, u.role 
        FROM chat_messages cm 
        LEFT JOIN users u ON cm.username = u.username 
        ORDER BY cm.id ASC
    ");
    $stmt->execute();
    $result = $stmt->get_result();

    $messages = [];
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    send_json_response(true, 'Semua pesan berhasil diambil', ['messages' => $messages]);
}

function sendMessage() {
    global $conn;

    $username = $_POST['username'] ?? 'Guest';
    $message = $_POST['message'] ?? '';
    $avatarUrl = $_POST['avatar_url'] ?? 'https://i.pravatar.cc/150?img=0';

    if (empty(trim($message))) {
        send_json_response(false, 'Pesan tidak boleh kosong.');
    }

    $urlPattern = '/(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i';
    if (preg_match($urlPattern, $message)) {
        send_json_response(false, 'Pesan tidak boleh mengandung link.');
    }

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM muted_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        send_json_response(false, 'Anda telah dibisukan dan tidak dapat mengirim pesan.');
    }

    $stmt = $conn->prepare("INSERT INTO chat_messages (username, message, avatar_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $message, $avatarUrl);

    if ($stmt->execute()) {
        send_json_response(true, 'Pesan terkirim!');
    } else {
        send_json_response(false, 'Gagal mengirim pesan.');
    }
}

function deleteMessage() {
    global $conn;
    $messageId = $_POST['message_id'] ?? 0;

    if (empty($messageId)) {
        send_json_response(false, 'ID pesan tidak valid.');
    }

    $stmt = $conn->prepare("DELETE FROM chat_messages WHERE id = ?");
    $stmt->bind_param("i", $messageId);

    if ($stmt->execute()) {
        send_json_response(true, 'Pesan berhasil dihapus.');
    } else {
        send_json_response(false, 'Gagal menghapus pesan.');
    }
}

function muteUser() {
    global $conn;
    $username = $_POST['username'] ?? '';
    $mutedBy = $_SESSION['username'] ?? '';

    if (empty($username)) {
        send_json_response(false, 'Username tidak valid.');
    }

    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM muted_users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row['count'] > 0) {
        send_json_response(false, 'Pengguna sudah dibisukan.');
    }

    $stmt = $conn->prepare("INSERT INTO muted_users (username, muted_by) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $mutedBy);

    if ($stmt->execute()) {
        send_json_response(true, 'Pengguna berhasil dibisukan.');
    } else {
        send_json_response(false, 'Gagal membisukan pengguna.');
    }
}

function unmuteUser() {
    global $conn;
    $username = $_POST['username'] ?? '';

    if (empty($username)) {
        send_json_response(false, 'Username tidak valid.');
    }

    $stmt = $conn->prepare("DELETE FROM muted_users WHERE username = ?");
    $stmt->bind_param("s", $username);

    if ($stmt->execute()) {
        send_json_response(true, 'Pengguna berhasil dibuka bisukannya.');
    } else {
        send_json_response(false, 'Gagal membuka bisukan pengguna.');
    }
}

// --- FUNGSI AI YANG ---
function askAI() {
    global $conn;
    $question = $_POST['question'] ?? '';
    $username = $_SESSION['username'] ?? 'Guest'; // Ambil username yang bertanya

    if (empty(trim($question))) {
        send_json_response(false, 'Pertanyaan tidak boleh kosong.');
    }

    saveQuestionHistory($question);
    $response = generateSmartResponse($question);
    
    // JARING PENGAMAN PENTING: Pastikan $response SELALU berupa string yang valid
    if (empty($response) || is_null($response)) {
        $response = getDefaultResponse($question); // Coba lagi dengan default
        if (empty($response)) { // Jika masih gagal
            $response = "Maaf, saya sedang bingung. Bisa tanyakan dengan cara lain?";
        }
    }
    
    // Simpan pertanyaan yang tidak bisa dijawab untuk pembelajaran di masa depan
    if (isDefaultResponse($response)) {
        saveUnknownQuestion($question);
    }

    // --- SIMPAN PESAN VIVI KE DATABASE ---
    // Format pertanyaan agar jelas ini adalah percakapan dengan AI
    $questionToStore = "Vivi, " . $question;
    $viviUsername = 'Vivi';
    $viviAvatar = 'binigua.jpg'; // Avatar Vivi

    // Mulai transaksi untuk memastikan kedua pesan tersimpan atau tidak sama sekali
    $conn->begin_transaction();

    try {
        // 1. Simpan pesan pertanyaan dari user
        $stmt1 = $conn->prepare("INSERT INTO chat_messages (username, message, avatar_url) VALUES (?, ?, ?)");
        // Asumsikan ada session untuk avatar user, jika tidak, gunakan default
        $userAvatar = 'uploads/profiles/' . ($_SESSION['profile_pic'] ?? 'default.png');
        $stmt1->bind_param("sss", $username, $questionToStore, $userAvatar);
        $stmt1->execute();
        
        // Dapatkan ID pesan pertanyaan untuk pembelajaran
        $questionId = $conn->insert_id;

        // 2. Simpan pesan jawaban dari Vivi
        $stmt2 = $conn->prepare("INSERT INTO chat_messages (username, message, avatar_url) VALUES (?, ?, ?)");
        $stmt2->bind_param("sss", $viviUsername, $response, $viviAvatar);
        $stmt2->execute();
        
        // Dapatkan ID pesan jawaban untuk pembelajaran
        $answerId = $conn->insert_id;

        // 3. Simpan log pembelajaran
        $stmt3 = $conn->prepare("INSERT INTO learning_logs (conversation_id, question, answer, created_at) VALUES (?, ?, ?, NOW())");
        $stmt3->bind_param("iss", $questionId, $question, $response);
        $stmt3->execute();

        // Jika semua berhasil, commit transaksi
        $conn->commit();
        
        // --- PEMBELAJARAN OTOMATIS ---
        // Analisis percakapan secara otomatis setiap kali ada interaksi dengan AI
        // untuk mempercepat proses pembelajaran.
        analyzeConversations();
        
        // Kirim respons sukses ke frontend
        send_json_response(true, 'Respons AI berhasil dihasilkan dan disimpan', ['response' => $response]);

    } catch (Exception $e) {
        // Jika ada error, rollback perubahan
        $conn->rollback();
        error_log("Gagal menyimpan percakapan AI: " . $e->getMessage());
        send_json_response(false, 'Terjadi kesalahan saat menyimpan percakapan.');
    }
}

// Fungsi untuk menyimpan riwayat pertanyaan
function saveQuestionHistory($question) {
    if (!isset($_SESSION['question_history'])) {
        $_SESSION['question_history'] = [];
    }
    
    array_push($_SESSION['question_history'], $question);
    if (count($_SESSION['question_history']) > 5) {
        array_shift($_SESSION['question_history']);
    }
}

// Fungsi untuk menghasilkan respons yang lebih pintar dengan penambahan query ke knowledge base
function generateSmartResponse($question) {
    $question = strtolower($question);
    
    // Cek knowledge base terlebih dahulu
    $kbResponse = searchKnowledgeBase($question);
    if ($kbResponse) {
        // Update usage count untuk pengetahuan yang digunakan
        updateKnowledgeUsage($kbResponse['id']);
        return $kbResponse['answer'];
    }
    
    // Cek apakah ini adalah sapaan
    if (preg_match('/(halo|hai|hello|hi|pagi|sore|malam)/i', $question)) {
        return getGreetingResponse();
    }
    
    // Cek apakah ini adalah pertanyaan tentang anime
    if (preg_match('/(anime)/i', $question)) {
        return getAnimeResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang donghua
    if (preg_match('/(donghua)/i', $question)) {
        return getDonghuaResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang komik
    if (preg_match('/(komik|comic|manga)/i', $question)) {
        return getComicResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang novel
    if (preg_match('/(novel|buku|cerita)/i', $question)) {
        return getNovelResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang kartun
    if (preg_match('/(kartun|cartoon)/i', $question)) {
        return getCartoonResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang cuaca
    if (preg_match('/(cuaca|hujan|panas|dingin)/i', $question)) {
        return getWeatherResponse();
    }
    
    // Cek apakah ini adalah pertanyaan tentang waktu
    if (preg_match('/(jam berapa|sekarang jam|waktu)/i', $question)) {
        return getTimeResponse();
    }
    
    // Cek apakah ini adalah pertanyaan tentang tanggal
    if (preg_match('/(tanggal|hari ini|besok)/i', $question)) {
        return getDateResponse();
    }
    
    // Cek apakah ini adalah pertanyaan tentang matematika
    if (preg_match('/(berapa|hitung|tambah|kurang|kali|bagi)/i', $question)) {
        return getMathResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang teknologi
    if (preg_match('/(komputer|laptop|smartphone|android|ios)/i', $question)) {
        return getTechResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang sains
    if (preg_match('/(sains|ilmu|fisika|kimia|biologi)/i', $question)) {
        return getScienceResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang sejarah
    if (preg_match('/(sejarah|sejarahnya|kapan|tahun)/i', $question)) {
        return getHistoryResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang geografi
    if (preg_match('/(geografi|negara|ibu kota|benua)/i', $question)) {
        return getGeographyResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang kesehatan
    if (preg_match('/(kesehatan|sehat|sakit|obat)/i', $question)) {
        return getHealthResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang olahraga
    if (preg_match('/(olahraga|sepak bola|bola basket|voli)/i', $question)) {
        return getSportsResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang musik
    if (preg_match('/(musik|lagu|penyanyi|band)/i', $question)) {
        return getMusicResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang makanan
    if (preg_match('/(makanan|kuliner|resep|masakan)/i', $question)) {
        return getFoodResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang hewan
    if (preg_match('/(hewan|binatang|fauna)/i', $question)) {
        return getAnimalResponse($question);
    }
    
    // Cek apakah ini adalah pertanyaan tentang tumbuhan
    if (preg_match('/(tumbuhan|tanaman|flora|pohon)/i', $question)) {
        return getPlantResponse($question);
    }
    
    // Pertanyaan tentang DF Scenery
    if (preg_match('/(df scenery|dfscenery|wisata ini|tempat ini)/i', $question)) {
        return getDFSceneryResponse($question);
    }
    
    // Pertanyaan tentang aktivitas
    if (preg_match('/(aktivitas|kegiatan|main|bermain)/i', $question)) {
        return getActivityResponse();
    }
    
    // Pertanyaan tentang akomodasi
    if (preg_match('/(penginapan|hotel|menginap|villa)/i', $question)) {
        return getAccommodationResponse();
    }
    
    // Pertanyaan tentang transportasi
    if (preg_match('/(transportasi|kendaraan|parkir)/i', $question)) {
        return getTransportationResponse();
    }
    
    // Pertanyaan tentang tiket
    if (preg_match('/(tiket|harga|biaya|bayar)/i', $question)) {
        return getTicketResponse();
    }
    
    // Pertanyaan tentang fasilitas
    if (preg_match('/(fasilitas|toilet|mushola)/i', $question)) {
        return getFacilityResponse();
    }
    
    // Pertanyaan tentang jam buka
    if (preg_match('/(jam buka|buka|tutup|operasional)/i', $question)) {
        return getOperatingHoursResponse();
    }
    
    // Pertanyaan tentang foto atau spot foto
    if (preg_match('/(foto|spot foto|instagramable|swafoto)/i', $question)) {
        return getPhotoSpotResponse();
    }
    
    // Pertanyaan tentang event atau acara khusus
    if (preg_match('/(event|acara|kegiatan khusus|festival)/i', $question)) {
        return getEventResponse();
    }
    
    // Pertanyaan tentang informasi kontak
    if (preg_match('/(kontak|telepon|email|alamat)/i', $question)) {
        return getContactResponse();
    }
    
    // Pertanyaan tentang identitas Vivi
    if (preg_match('/(siapa kamu|siapa vivi|kamu siapa)/i', $question)) {
        return getIdentityResponse();
    }
    
    // Pertanyaan tentang terima kasih
    if (preg_match('/(terima kasih|thanks|makasih)/i', $question)) {
        return getThankYouResponse();
    }
    
// --- PENGECEKAN INTERNET ---
// Jika tidak ada jawaban di knowledge base, coba cari di internet
 $webResult = searchWeb($question);
if ($webResult) {
    // Jika menemukan jawaban di internet, kembalikan hasilnya
    // Tambahkan prefiks untuk memberi tahu sumbernya
    return "Berdasarkan informasi yang saya temukan: " . $webResult;
}

// Jika tidak ada yang cocok (termasuk dari internet), berikan respons default
return getDefaultResponse($question);
}

// Fungsi untuk mencari di knowledge base
function searchKnowledgeBase($question) {
    global $conn;
    
    // Ekstrak kata kunci dari pertanyaan
    $keywords = extractKeywords($question);
    
    if (empty($keywords)) {
        return null;
    }
    
    // Buat query untuk mencari di knowledge base
    $placeholders = str_repeat('?,', count($keywords) - 1) . '?';
    $sql = "SELECT * FROM knowledge_base WHERE verified = TRUE AND (";
    
    // Tambahkan kondisi untuk setiap kata kunci
    $conditions = [];
    foreach ($keywords as $i => $keyword) {
        $conditions[] = "keywords LIKE ?";
        $keywords[$i] = "%$keyword%";
    }
    
    $sql .= implode(' OR ', $conditions) . ") ORDER BY confidence DESC, usage_count DESC LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($keywords)), ...$keywords);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}

// Fungsi untuk mengekstrak kata kunci dari pertanyaan
function extractKeywords($question) {
    // Hapus kata-kata umum yang tidak penting
    $stopWords = ['apa', 'siapa', 'dimana', 'kapan', 'mengapa', 'bagaimana', 'berapa', 'adalah', 'yaitu', 'yang', 'dari', 'ke', 'pada', 'di', 'dan', 'atau', 'tapi', 'juga', 'ini', 'itu', 'saya', 'kamu', 'kita', 'mereka', 'dia', 'kamu', 'kalian'];
    
    // Pisahkan kata-kata
    $words = preg_split('/[\s,?.!;:]+/', $question);
    
    // Filter kata-kata umum dan kata-kata pendek
    $keywords = [];
    foreach ($words as $word) {
        $word = strtolower(trim($word));
        if (strlen($word) > 2 && !in_array($word, $stopWords)) {
            $keywords[] = $word;
        }
    }
    
    return $keywords;
}

// Fungsi untuk mengupdate usage count knowledge
function updateKnowledgeUsage($knowledgeId) {
    global $conn;
    
    $stmt = $conn->prepare("UPDATE knowledge_base SET usage_count = usage_count + 1 WHERE id = ?");
    $stmt->bind_param("i", $knowledgeId);
    $stmt->execute();
}

// Fungsi untuk mengajari AI
function teachAI() {
    global $conn;
    
    $question = $_POST['question'] ?? '';
    $answer = $_POST['answer'] ?? '';
    $keywords = $_POST['keywords'] ?? '';
    $confidence = $_POST['confidence'] ?? 0.5;
    
    if (empty(trim($question)) || empty(trim($answer))) {
        send_json_response(false, 'Pertanyaan dan jawaban tidak boleh kosong.');
    }
    
    // Jika keywords tidak disediakan, ekstrak dari pertanyaan
    if (empty(trim($keywords))) {
        $keywords = implode(', ', extractKeywords($question));
    }
    
    $stmt = $conn->prepare("INSERT INTO knowledge_base (keywords, question, answer, confidence, source, created_at, updated_at, verified) VALUES (?, ?, ?, ?, 'manual', NOW(), NOW(), TRUE)");
    $stmt->bind_param("sssd", $keywords, $question, $answer, $confidence);
    
    if ($stmt->execute()) {
        send_json_response(true, 'Pengetahuan berhasil ditambahkan ke knowledge base.');
    } else {
        send_json_response(false, 'Gagal menambahkan pengetahuan.');
    }
}

// Fungsi untuk memverifikasi knowledge
function verifyKnowledge() {
    global $conn;
    
    $knowledgeId = $_POST['knowledge_id'] ?? 0;
    $verified = $_POST['verified'] ?? false;
    
    if (empty($knowledgeId)) {
        send_json_response(false, 'ID pengetahuan tidak valid.');
    }
    
    $stmt = $conn->prepare("UPDATE knowledge_base SET verified = ?, updated_at = NOW() WHERE id = ?");
    $stmt->bind_param("ii", $verified, $knowledgeId);
    
    if ($stmt->execute()) {
        send_json_response(true, 'Status verifikasi pengetahuan berhasil diperbarui.');
    } else {
        send_json_response(false, 'Gagal memperbarui status verifikasi pengetahuan.');
    }
}

// Fungsi untuk mendapatkan knowledge
function getKnowledge() {
    global $conn;
    
    $verified = $_GET['verified'] ?? null;
    $limit = $_GET['limit'] ?? 20;
    $offset = $_GET['offset'] ?? 0;
    
    $sql = "SELECT * FROM knowledge_base";
    $params = [];
    $types = '';
    
    if ($verified !== null) {
        $sql .= " WHERE verified = ?";
        $params[] = $verified;
        $types .= 'i';
    }
    
    $sql .= " ORDER BY created_at DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= 'ii';
    
    $stmt = $conn->prepare($sql);
    
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    $result = $stmt->get_result();
    
    $knowledge = [];
    while ($row = $result->fetch_assoc()) {
        $knowledge[] = $row;
    }
    
    send_json_response(true, 'Pengetahuan berhasil diambil', ['knowledge' => $knowledge]);
}

// PERBAIKAN: Ini adalah fungsi analyzeConversations() yang diperbaiki
function analyzeConversations() {
    global $conn;
    
    // Ambil percakapan dari learning_logs yang belum dianalisis
    $stmt = $conn->prepare("
        SELECT ll.id, ll.question, ll.answer
        FROM learning_logs ll
        LEFT JOIN knowledge_base kb ON (ll.question = kb.question)
        WHERE kb.id IS NULL AND ll.analyzed = FALSE
        ORDER BY ll.created_at DESC
        LIMIT 20
    ");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $conversations = [];
    while ($row = $result->fetch_assoc()) {
        $conversations[] = $row;
    }
    
    $newKnowledgeCount = 0;
    
    // Analisis setiap percakapan
    foreach ($conversations as $conv) {
        // Ekstrak pengetahuan dari percakapan
        $keywords = extractKeywords($conv['question']);
        
        if (!empty($keywords)) {
            // Simpan ke knowledge base dengan confidence rendah karena belum diverifikasi
            $keywordsStr = implode(', ', $keywords);
            $confidence = 0.3; // Confidence rendah
            
            $stmt = $conn->prepare("
                INSERT INTO knowledge_base (keywords, question, answer, confidence, source, created_at, updated_at, verified) 
                VALUES (?, ?, ?, ?, 'conversation', NOW(), NOW(), FALSE)
            ");
            $stmt->bind_param("sssd", $keywordsStr, $conv['question'], $conv['answer'], $confidence);
            $stmt->execute();
            
            $newKnowledgeCount++;
        }
        
        // Tandai bahwa percakapan telah dianalisis
        $stmt = $conn->prepare("
            UPDATE learning_logs 
            SET analyzed = TRUE 
            WHERE id = ?
        ");
        $stmt->bind_param("i", $conv['id']);
        $stmt->execute();
    }
    
    // Kembalikan jumlah pengetahuan baru yang ditambahkan
    return $newKnowledgeCount;
}

// Fungsi untuk memberikan feedback pada AI
function feedbackAI() {
    global $conn;
    
    $question = $_POST['question'] ?? '';
    $answer = $_POST['answer'] ?? '';
    $feedback = $_POST['feedback'] ?? null; // true untuk positif, false untuk negatif
    
    if (empty(trim($question)) || empty(trim($answer))) {
        send_json_response(false, 'Pertanyaan dan jawaban tidak boleh kosong.');
    }
    
    // Cari log pembelajaran yang sesuai
    $stmt = $conn->prepare("
        SELECT id FROM learning_logs 
        WHERE question = ? AND answer = ? 
        ORDER BY created_at DESC 
        LIMIT 1
    ");
    $stmt->bind_param("ss", $question, $answer);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $logId = $row['id'];
        
        // Update feedback
        $stmt = $conn->prepare("UPDATE learning_logs SET feedback = ? WHERE id = ?");
        $stmt->bind_param("ii", $feedback, $logId);
        
        if ($stmt->execute()) {
            // Jika feedback positif dan ini adalah default response, tambahkan ke knowledge base
            if ($feedback && isDefaultResponse($answer)) {
                // Cari pengetahuan yang cocok untuk pertanyaan ini
                $keywords = extractKeywords($question);
                
                if (!empty($keywords)) {
                    // Cek apakah sudah ada di knowledge base
                    $kbResponse = searchKnowledgeBase($question);
                    
                    if (!$kbResponse) {
                        // Tambahkan ke knowledge base dengan confidence rendah
                        $keywordsStr = implode(', ', $keywords);
                        $confidence = 0.4; // Confidence rendah karena baru dari feedback
                        
                        $stmt = $conn->prepare("
                            INSERT INTO knowledge_base (keywords, question, answer, confidence, source, created_at, updated_at, verified) 
                            VALUES (?, ?, ?, ?, 'feedback', NOW(), NOW(), FALSE)
                        ");
                        $stmt->bind_param("sssd", $keywordsStr, $question, $answer, $confidence);
                        $stmt->execute();
                    }
                }
            }
            
            send_json_response(true, 'Feedback berhasil disimpan.');
        } else {
            send_json_response(false, 'Gagal menyimpan feedback.');
        }
    } else {
        send_json_response(false, 'Log pembelajaran tidak ditemukan.');
    }
}

// Fungsi-fungsi respons khusus
function getGreetingResponse() {
    $hour = (int)date('H');
    $greeting = "Halo";
    
    if ($hour >= 5 && $hour < 12) {
        $greeting = "Selamat pagi";
    } elseif ($hour >= 12 && $hour < 15) {
        $greeting = "Selamat siang";
    } elseif ($hour >= 15 && $hour < 18) {
        $greeting = "Selamat sore";
    } else {
        $greeting = "Selamat malam";
    }
    
    $responses = [
        "$greeting! Saya Vivi, asisten virtual yang siap membantu Anda. Ada yang bisa saya bantu?",
        "$greeting! Senang berbicara dengan Anda. Apa yang ingin Anda ketahui hari ini?",
        "$greeting! Saya di sini untuk menjawab pertanyaan Anda. Tanyakan saja!"
    ];
    return $responses[array_rand($responses)];
}

function getWeatherResponse() {
    // mengambil data cuaca dari API
    $weatherConditions = ["cerah", "berawan", "mendung", "hujan ringan"];
    $currentWeather = $weatherConditions[array_rand($weatherConditions)];
    $temperature = rand(22, 32);
    
    return "Saat ini cuaca cukup $currentWeather dengan suhu sekitar $temperature°C. " .
           "Saya sarankan membawa topi dan sunscreen jika Anda berencana beraktivitas di luar ruangan. " .
           "Untuk prakiraan cuaca lebih detail, Anda bisa mengecek aplikasi cuaca terpercaya.";
}

function getTimeResponse() {
    $currentTime = date('H:i');
    $hour = (int)date('H');
    
    if ($hour >= 5 && $hour < 10) {
        $timeDesc = "pagi yang cerah";
    } elseif ($hour >= 10 && $hour < 15) {
        $timeDesc = "siang hari";
    } elseif ($hour >= 15 && $hour < 18) {
        $timeDesc = "sore hari";
    } elseif ($hour >= 18 && $hour < 21) {
        $timeDesc = "malam hari";
    } else {
        $timeDesc = "malam yang larut";
    }
    
    return "Sekarang pukul $currentTime WIB. $timeDesc, semoga harimu menyenangkan!";
}

function getDateResponse() {
    $dayIndonesia = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    $monthIndonesia = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", 
                      "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
    
    $dayName = $dayIndonesia[date('w')];
    $date = date('d');
    $monthName = $monthIndonesia[date('n') - 1];
    $year = date('Y');
    
    return "Hari ini adalah $dayName, $date $monthName $year. Semoga hari ini membawa keberuntungan untuk Anda!";
}

function getMathResponse($question) {
    // Deteksi operasi matematika sederhana
    if (preg_match('/(\d+)\s*(\+|tambah|plus)\s*(\d+)/i', $question, $matches)) {
        $result = (int)$matches[1] + (int)$matches[3];
        return "Hasil dari " . $matches[1] . " + " . $matches[3] . " adalah $result.";
    }
    
    if (preg_match('/(\d+)\s*(-|kurang|minus)\s*(\d+)/i', $question, $matches)) {
        $result = (int)$matches[1] - (int)$matches[3];
        return "Hasil dari " . $matches[1] . " - " . $matches[3] . " adalah $result.";
    }
    
    if (preg_match('/(\d+)\s*(\*|x|kali)\s*(\d+)/i', $question, $matches)) {
        $result = (int)$matches[1] * (int)$matches[3];
        return "Hasil dari " . $matches[1] . " × " . $matches[3] . " adalah $result.";
    }
    
    if (preg_match('/(\d+)\s*(\/|:|bagi)\s*(\d+)/i', $question, $matches)) {
        if ($matches[3] != 0) {
            $result = (int)$matches[1] / (int)$matches[3];
            return "Hasil dari " . $matches[1] . " ÷ " . $matches[3] . " adalah " . round($result, 2) . ".";
        } else {
            return "Maaf, pembagian dengan nol tidak bisa dilakukan.";
        }
    }
    
    return "Saya bisa membantu operasi matematika sederhana seperti penjumlahan, pengurangan, perkalian, dan pembagian. Coba tanya seperti 'berapa 5 + 3?'";
}

function getTechResponse($question) {
    $responses = [
        "Teknologi terus berkembang pesat. Saat ini kita sedang memasuki era kecerdasan buatan dan internet of things yang akan mengubah cara kita hidup dan bekerja.",
        "Dalam dunia teknologi, inovasi adalah kunci. Perusahaan besar terus berlomba untuk menciptakan produk yang lebih baik dan lebih efisien.",
        "Teknologi informasi telah merevolusi cara kita berkomunikasi, bekerja, dan mengakses informasi. Dari smartphone hingga cloud computing, teknologi membuat hidup kita lebih mudah."
    ];
    
    if (preg_match('/(android|ios)/i', $question)) {
        return "Android dan iOS adalah dua sistem operasi mobile terpopuler. Android, dikembangkan oleh Google, bersifat open source dan digunakan oleh banyak merek. iOS, dikembangkan oleh Apple, eksklusif untuk perangkat Apple dan dikenal dengan keamanan dan ekosistem yang terintegrasi.";
    }
    
    if (preg_match('/(laptop|komputer)/i', $question)) {
        return "Laptop dan komputer adalah alat penting dalam kehidupan modern. Saat ini, laptop dengan prosesor generasi terbaru menawarkan performa tinggi dengan konsumsi daya yang lebih efisien. Untuk produktivitas, disarankan menggunakan RAM minimal 8GB dan SSD untuk penyimpanan.";
    }
    
    return $responses[array_rand($responses)];
}

function getScienceResponse($question) {
    if (preg_match('/(fisika)/i', $question)) {
        return "Fisika adalah ilmu yang mempelajari materi, energi, dan interaksinya. Hukum-hukum fisika mengatur segala sesuatu dari gerak planet hingga partikel subatomik. Teori relativitas Einstein dan mekanika kuantum adalah dua pilar fisika modern.";
    }
    
    if (preg_match('/(kimia)/i', $question)) {
        return "Kimia adalah ilmu yang mempelajari sifat, komposisi, struktur, dan perubahan materi. Dari reaksi kimia dalam tubuh kita hingga pembuatan obat-obatan, kimia memainkan peran penting dalam kehidupan sehari-hari.";
    }
    
    if (preg_match('/(biologi)/i', $question)) {
        return "Biologi adalah ilmu yang mempelajari kehidupan dan organisme hidup. Dari mikroskopis bakteri hingga paus biru raksasa, biologi membantu kita memahami keanekaragaman hayati dan cara kerja kehidupan.";
    }
    
    return "Sains adalah cara sistematis untuk mempelajari dunia alam melalui observasi dan eksperimen. Sains membantu kita memahami alam semesta dari yang terkecil hingga yang terbesar.";
}

function getHistoryResponse($question) {
    if (preg_match('/(indonesia)/i', $question)) {
        return "Sejarah Indonesia dimulai dari zaman prasejarah dengan kerajaan-kerajaan Hindu-Buddha seperti Sriwijaya dan Majapahit. Indonesia kemudian dijajah selama 350 tahun sebelum akhirnya memproklamasikan kemerdekaan pada 17 Agustus 1945 di bawah pimpinan Soekarno dan Hatta.";
    }
    
    if (preg_match('/(dunia)/i', $question)) {
        return "Sejarah dunia mencakup peradaban kuno seperti Mesir, Yunani, dan Romawi, Abad Pertengahan dengan feodalisme, Renaissance yang membangkitkan kembali seni dan ilmu pengetahuan, hingga era modern dengan revolusi industri dan teknologi informasi.";
    }
    
    return "Sejarah adalah studi tentang masa lalu yang membantu kita memahami bagaimana peradaban berkembang. Dari sejarah kita belajar tentang kesuksesan, kegagalan, dan pelajaran berharga untuk masa depan.";
}

function getGeographyResponse($question) {
    if (preg_match('/(indonesia)/i', $question)) {
        return "Indonesia adalah negara kepulauan terbesar di dunia dengan lebih dari 17.000 pulau. Ibu kota Indonesia adalah Jakarta. Indonesia terletak di garis khatulistiwa dan memiliki iklim tropis dengan keanekaragaman hayati yang luar biasa.";
    }
    
    if (preg_match('/(asia)/i', $question)) {
        return "Asia adalah benua terbesar di dunia baik dalam luas wilayah maupun populasi. Asia memiliki beragam budaya, bahasa, dan geografi. Beberapa negara besar di Asia termasuk China, India, Jepang, dan Indonesia.";
    }
    
    return "Geografi adalah ilmu yang mempelajari Bumi dan permukaannya, termasuk fitur fisik, penduduk, dan fenomena. Geografi membantu kita memahami hubungan antara manusia dan lingkungan.";
}

function getHealthResponse($question) {
    return "Kesehatan adalah keadaan sempurna secara fisik, mental, dan sosial, bukan hanya bebas dari penyakit. Untuk menjaga kesehatan, penting untuk makan makanan bergizi, berolahraga secara teratur, tidur cukup, dan mengelola stres. Jika Anda memiliki masalah kesehatan, konsultasikan dengan profesional medis.";
}

function getSportsResponse($question) {
    if (preg_match('/(sepak bola)/i', $question)) {
        return "Sepak bola adalah olahraga paling populer di dunia. Dimainkan oleh dua tim dengan 11 pemain each, tujuannya adalah memasukkan bola ke gawang lawan. Piala Dunia FIFA adalah turnamen sepak bola paling prestisius yang diadakan setiap empat tahun.";
    }
    
    return "Olahraga adalah aktivitas fisik yang terstruktur untuk meningkatkan kebugaran dan keterampilan. Olahraga tidak hanya baik untuk kesehatan fisik tetapi juga untuk kesehatan mental dan interaksi sosial.";
}

function getMusicResponse($question) {
    return "Musik adalah bentuk seni yang menggunakan suara dan ketenangan sebagai medium. Musik memiliki kekuatan untuk mempengaruhi emosi, meningkatkan mood, dan bahkan membantu dalam terapi. Dari klasik hingga pop, setiap genre musik memiliki karakteristik dan daya tariknya sendiri.";
}

function getFoodResponse($question) {
    if (preg_match('/(indonesia)/i', $question)) {
        return "Masakan Indonesia sangat beragam dengan pengaruh dari berbagai budaya. Beberapa hidangan terkenal termasuk rendang, sate, nasi goreng, dan gado-gado. Setiap daerah memiliki ciri khas kulinernya sendiri seperti masakan Padang yang pedas atau masakan Manado yang kaya rempah.";
    }
    return "Makanan adalah kebutuhan dasar manusia yang juga telah berkembang menjadi seni dan budaya. Setiap negara memiliki kuliner khas yang mencerminkan sejarah, geografi, dan budaya mereka.";
}

function getAnimalResponse($question) {
    return "Hewan adalah organisme eukariotik multiseluler yang membentuk kerajaan Animalia. Dari serangga kecil hingga paus raksasa, keragaman hewan di Bumi sangat menakjubkan. Hewan memainkan peran penting dalam ekosistem dan beberapa telah dijinakkan untuk membantu manusia dalam berbagai cara.";
}

function getPlantResponse($question) {
    return "Tumbuhan adalah organisme multiseluler yang sebagian besar melakukan fotosintesis untuk menghasilkan makanan. Tumbuhan sangat penting untuk kehidupan di Bumi karena menghasilkan oksigen dan menjadi basis rantai makanan. Dari lumut kecil hingga pohon raksasa, tumbuhan menunjukkan adaptasi yang luar biasa untuk bertahan hidup di berbagai habitat.";
}

function getDFSceneryResponse($question) {
    return "DF Scenery adalah destinasi wisata alam yang menawarkan keindahan pemandangan dan berbagai aktivitas menarik. Terletak di kawasan yang sejuk dan asri, DF Scenery sempurna untuk liburan keluarga, gathering perusahaan, atau sekadar melepas penat dari rutinitas sehari-hari.";
}

function getActivityResponse() {
    return "DF Scenery menawarkan berbagai aktivitas menarik! Anda bisa berkeliling taman dengan sepeda, mencoba flying fox, berkuda, atau sekadar bersantai di gazebo sambil menikmati pemandangan. Untuk anak-anak, ada area bermain yang aman dan menyenangkan. Akhir pekan ini juga ada workshop membuat kerajinan tangan khas Sunda!";
}

function getAccommodationResponse() {
    return "Di sekitar DF Scenery terdapat beberapa pilihan penginapan, mulai dari hotel bintang 3 hingga villa tradisional Sunda. Jika Anda menginap, Anda bisa mendapatkan tiket masuk DF Scenery dengan harga khusus. Saya sarankan memesan penginapan terlebih dahulu terutama saat akhir pekan atau musim liburan.";
}

function getTransportationResponse() {
    return "Anda bisa mencapai DF Scenery dengan kendaraan pribadi atau menggunakan transportasi umum. Kami menyediakan area parkir yang luas untuk mobil dan motor. Jika menggunakan transportasi umum, Anda bisa naik angkot jurusan Lembang lalu turun di depan pintu gerbang DF Scenery. Kami juga menyediakan layanan antar-jemput dari beberapa hotel di sekitar Bandung dengan reservasi terlebih dahulu.";
}

function getTicketResponse() {
    return "Tiket DF Scenery bisa dibeli secara online melalui website kami atau langsung di loket. Harga tiket weekday Rp 50.000 untuk dewasa dan Rp 35.000 untuk anak-anak. Saat weekend, harga tiket Rp 65.000 untuk dewasa dan Rp 45.000 untuk anak-anak. Kami juga menawarkan paket keluarga untuk 2 dewasa dan 2 anak dengan harga Rp 150.000. Pembelian online mendapat diskon 10%!";
}

function getFacilityResponse() {
    return "DF Scenery dilengkapi dengan fasilitas lengkap untuk kenyamanan Anda. Kami memiliki toilet yang bersih di beberapa titik, mushola untuk beribadah, area parkir luas, dan beberapa restoran. Untuk anak-anak, ada taman bermain yang aman. Kami juga menyediakan kursi roda gratis untuk penyandang disabilitas dan lansia. WiFi gratis tersedia di area tertentu.";
}

function getOperatingHoursResponse() {
    return "DF Scenery buka setiap hari Senin-Minggu dari pukul 08.00 hingga 17.00 WIB. Untuk akhir pekan dan hari libur nasional, kami buka hingga pukul 18.00 WIB. Saya sarankan datang lebih pagi untuk menikmati udara segar dan menghindari keramaian. Terakhir, pemesanan tiket online tutup satu jam sebelum jam operasional berakhir.";
}

function getPhotoSpotResponse() {
    return "DF Scenery memiliki banyak spot foto Instagramable yang wajib Anda coba! Favorit pengunjung adalah Jembatan Pelangi dengan pemandangan lembah, Taman Bunga Sakura yang mekar di musim tertentu, dan Gazebo Terapung di tengah danau. Jangan lupa mencoba spot foto dengan latar belakang Gunung Tangkuban Perahu yang indah terutama saat cuaca cerah!";
}

function getEventResponse() {
    return "DF Scenery sering mengadakan acara menarik! Bulan ini ada Festival Kembang Api setiap Sabtu malam, Workshop Batik setiap Minggu pagi, dan Pameran Seni Lokal di area galeri. Akhir bulan ini akan ada konser musik tradisional dengan artis lokal. Untuk informasi event lebih lengkap, Anda bisa cek kalender acara di website kami atau follow media sosial kami.";
}

function getContactResponse() {
    return "Anda bisa menghubungi DF Scenery melalui telepon di (022) 1234567 atau email ke info@dfscenery.com. Alamat kami di Jalan Raya Lembang No. 123, Bandung, Jawa Barat. Kami juga aktif di Instagram @dfscenery dan Facebook DF Scenery Official. Jam operasional customer service kami Senin-Jumat pukul 09.00-17.00 WIB.";
}

function getIdentityResponse() {
    return "Saya Vivi, asisten virtual yang dirancang untuk membantu Anda dengan berbagai pertanyaan. Saya memiliki pengetahuan tentang banyak topik termasuk sains, sejarah, teknologi, anime, dan tentu saja informasi tentang DF Scenery. Saya terus belajar setiap hari untuk memberikan jawaban yang lebih baik!";
}

function getThankYouResponse() {
    $responses = [
        "Sama-sama! Senang bisa membantu Anda. Jika ada pertanyaan lain, jangan ragu untuk bertanya lagi ya!",
        "Sama-sama! Itu sudah menjadi tugas saya. Ada hal lain yang ingin Anda tanyakan?",
        "Dengan senang hati! Jika butuh bantuan lagi, saya di sini untuk Anda."
    ];
    return $responses[array_rand($responses)];
}

function getDefaultResponse($question) {
    // Coba analisis lebih lanjut untuk memberikan respons yang lebih relevan
    if (preg_match('/(siapa|apa|bagaimana|mengapa|kapan|dimana)/i', $question)) {
        return "Itu pertanyaan yang menarik! Maaf, saya belum memiliki informasi lengkap tentang itu. Tapi jangan khawatir, saya terus belajar setiap hari. Untuk pertanyaan ini, saya sarankan Anda mencari informasi dari sumber terpercaya atau bertanya kepada ahli di bidang tersebut.";
    }
    
    // Respons default yang lebih ramah
    $responses = [
        "Hmm, saya belum bisa memberikan jawaban yang spesifik untuk itu. Tapi saya terus belajar setiap hari! Ada hal lain tentang DF Scenery atau topik lain yang ingin Anda tanyakan?",
        "Pertanyaan yang bagus! Sayangnya, saya belum memiliki informasi lengkap tentang itu. Namun, saya bisa membantu Anda dengan informasi tentang DF Scenery atau topik umum lainnya. Ada yang bisa saya bantu?",
        "Saya masih dalam proses pembelajaran untuk pertanyaan seperti itu. Tapi jangan khawatir, saya akan mencatat pertanyaan Anda untuk pembelajaran di masa depan. Ada topik lain yang ingin Anda bahas?"
    ];
    
    return $responses[array_rand($responses)];
}

function isDefaultResponse($response) {
    // Cek apakah respons adalah respons default
    return strpos($response, "saya masih belajar") !== false || 
           strpos($response, "belum memiliki informasi") !== false ||
           strpos($response, "belum bisa memberikan jawaban") !== false;
}

function saveUnknownQuestion($question) {
    // Simpan pertanyaan yang tidak bisa dijawab untuk pembelajaran di masa depan
    global $conn;
    
    // Cek apakah tabel unknown_questions ada, jika tidak buat
    $stmt = $conn->prepare("CREATE TABLE IF NOT EXISTS unknown_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        question TEXT NOT NULL,
        date_asked DATETIME NOT NULL,
        answered BOOLEAN DEFAULT FALSE,
        answer TEXT NULL
    )");
    $stmt->execute();
    
    $stmt = $conn->prepare("INSERT INTO unknown_questions (question, date_asked) VALUES (?, NOW())");
    $stmt->bind_param("s", $question);
    $stmt->execute();
}

// Tambahkan fungsi-fungsi respons yang hilang
function getAnimeResponse($question) {
    return "Anime adalah animasi dari Jepang yang telah menjadi populer di seluruh dunia. Ada berbagai genre anime, dari aksi, romantis, hingga fantasi. Beberapa anime terkenal termasuk Naruto, One Piece, dan Attack on Titan.";
}

function getDonghuaResponse($question) {
    return "Donghua adalah animasi dari Tiongkok yang kualitasnya semakin membaik dan mulai mendapatkan pengakuan internasional. Beberapa donghua populer termasuk The King's Avatar, Mo Dao Zu Shi, dan Scissor Seven.";
}

function getComicResponse($question) {
    return "Komik adalah medium visual yang menceritakan sebuah kisah melalui gambar. Ada berbagai jenis komik, dari superhero Amerika, manga Jepang, hingga manhwa Korea. Setiap jenis memiliki gaya dan cerita yang khas.";
}

function getNovelResponse($question) {
    return "Novel adalah karya fiksi prosa yang panjang dan kompleks, biasanya dibagi menjadi beberapa bab. Novel menawarkan eksplorasi mendalam tentang karakter, plot, dan tema. Ada banyak genre novel, mulai dari fiksi ilmiah hingga roman sejarah.";
}

function getCartoonResponse($question) {
    return "Kartun adalah bentuk seni visual two-dimensional. Istilah ini sering digunakan untuk mengacu pada animasi yang ditujukan untuk anak-anak, tetapi ada juga kartun untuk dewasa dengan tema yang lebih kompleks.";
}

/**
 * Mencari jawaban untuk sebuah pertanyaan menggunakan DuckDuckGo API
 * @param string $query Pertanyaan yang akan dicari
 * @return string|null Jawaban dari internet, atau null jika tidak ditemukan
 */
function searchWeb($query) {
    // Menggunakan DuckDuckGo Instant Answer API
    // Dokumentasi: https://duckduckgo.com/api
    $url = "https://api.duckduckgo.com/?q=" . urlencode($query) . "&format=json&no_html=1&skip_disambig=1";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Vivi-AI-DFScenery/1.0'); // Memberi tahu siapa yang mengakses
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Batas waktu 10 detik

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Periksa jika permintaan gagal
    if ($http_code !== 200 || $response === false) {
        error_log("Pencarian web gagal. Kode HTTP: " . $http_code);
        return null;
    }

    $data = json_decode($response, true);

    // Ambil jawaban dari 'AbstractText' atau 'RelatedTopics' pertama
    if (!empty($data['AbstractText'])) {
        return $data['AbstractText'];
    } elseif (!empty($data['RelatedTopics'][0]['Text'])) {
        return $data['RelatedTopics'][0]['Text'];
    }

    return null; // Tidak ditemukan jawaban yang relevan
}

// Jangan ada tag penutup ?> di akhir file