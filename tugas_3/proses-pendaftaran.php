<?php
include("db.php");

if (isset($_POST['daftar'])) {

    $nama     = htmlspecialchars($_POST['nama']);
    $alamat   = htmlspecialchars($_POST['alamat']);
    $jk       = htmlspecialchars($_POST['jenis_kelamin']);
    $agama    = htmlspecialchars($_POST['agama']);
    $sekolah  = htmlspecialchars($_POST['sekolah_asal']);

    if (empty($nama) || empty($alamat) || empty($jk) || empty($agama) || empty($sekolah)) {

        header('Location: formulir-pendaftaran.html?error=empty_fields');
        exit;
    }

    $sql = "INSERT INTO calon_siswa (nama, alamat, jenis_kelamin, agama, sekolah_asal) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($db, $sql);

    if ($stmt === false) {
        die("Error: Gagal mempersiapkan query. " . mysqli_error($db));
    }

    mysqli_stmt_bind_param($stmt, "sssss", $nama, $alamat, $jk, $agama, $sekolah);

    $execute = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    if ($execute) {
        header('Location: selamat-datang.html');
        exit; 
    } else {
        header('Location: formulir-pendaftaran.html?error=database_failed');
        exit;
    }

} else {
    die("Akses langsung ke file ini tidak diperbolehkan.");
}
?>