<?php
// Initialize session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
require_once($_SERVER['DOCUMENT_ROOT'] . '/Aqell_BK/aqell_config/aqell_db.php');

// Function to check if user is logged in
function aqell_check_login() {
    if (!isset($_SESSION['aqell_id_pengguna'])) {
        header("Location: /Aqell_BK/aqell_login.php");
        exit();
    }
}

// Function to check role access
function aqell_check_role($allowed_role) {
    if ($_SESSION['aqell_peran'] != $allowed_role) {
        header("Location: /Aqell_BK/aqell_login.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Sistem Identifikasi Gaya Belajar</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Aqell_BK/aqell_assets/aqell_css/aqell_style.css">
</head>
<body>
    <div class="aqell_header">
        <h1>Aqell BK</h1>
        <p>Sistem Identifikasi Gaya Belajar Siswa</p>
    </div>
    
    <div class="aqell_nav">
        <?php if(isset($_SESSION['aqell_id_pengguna'])): ?>
            <?php if($_SESSION['aqell_peran'] == 'guru'): ?>
                <a href="/Aqell_BK/aqell_guru/aqell_dashboard_guru.php">Dashboard</a>
                <a href="/Aqell_BK/aqell_kategori/aqell_list_kategori.php">Kategori</a>
                <a href="/Aqell_BK/aqell_soal/aqell_manage_soal.php">Soal</a>
                <a href="/Aqell_BK/aqell_paket/aqell_list_paket.php">Paket Ujian</a>
                <a href="/Aqell_BK/aqell_jadwal/aqell_list_jadwal.php">Jadwal</a>
                <a href="/Aqell_BK/aqell_hasil/aqell_hasil_all.php">Hasil</a>
            <?php else: ?>
                <a href="/Aqell_BK/aqell_siswa/aqell_dashboard_siswa.php">Dashboard</a>
                <a href="/Aqell_BK/aqell_ujian/aqell_list_aktif.php">Ujian Aktif</a>
                <a href="/Aqell_BK/aqell_hasil/aqell_hasil_siswa.php">Hasil Saya</a>
            <?php endif; ?>
            <a href="/Aqell_BK/aqell_auth/aqell_logout.php">Logout</a>
        <?php else: ?>
            <a href="/Aqell_BK/index.php">Beranda</a>
            <a href="/Aqell_BK/aqell_login.php">Login</a>
            <a href="/Aqell_BK/index.php#tentang">Tentang</a>
            <a href="/Aqell_BK/index.php#kontak">Kontak</a>
        <?php endif; ?>
    </div>
    
    <div class="aqell_container">
        <!-- Content will be inserted here -->