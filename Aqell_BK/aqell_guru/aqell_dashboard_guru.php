<?php
session_start();
require_once('../aqell_config/aqell_db.php');

// Check if user is logged in and is a teacher
if(!isset($_SESSION['aqell_id_pengguna']) || $_SESSION['aqell_peran'] != 'guru') {
    header("Location: ../aqell_login.php");
    exit();
}

// Get teacher data
$aqell_id_guru = $_SESSION['aqell_id_guru'];
$aqell_query_guru = "SELECT * FROM aqell_guru WHERE aqell_id_guru = $aqell_id_guru";
$aqell_result_guru = mysqli_query($aqell_conn, $aqell_query_guru);
$aqell_guru = mysqli_fetch_assoc($aqell_result_guru);

// Get counts for dashboard
// Total students
$aqell_query_siswa = "SELECT COUNT(*) as total_siswa FROM aqell_siswa";
$aqell_result_siswa = mysqli_query($aqell_conn, $aqell_query_siswa);
$aqell_total_siswa = mysqli_fetch_assoc($aqell_result_siswa)['total_siswa'];

// Total test packages
$aqell_query_paket = "SELECT COUNT(*) as total_paket FROM aqell_paket_ujian";
$aqell_result_paket = mysqli_query($aqell_conn, $aqell_query_paket);
$aqell_total_paket = mysqli_fetch_assoc($aqell_result_paket)['total_paket'];

// Total test results
$aqell_query_hasil = "SELECT COUNT(*) as total_hasil FROM aqell_hasil";
$aqell_result_hasil = mysqli_query($aqell_conn, $aqell_query_hasil);
$aqell_total_hasil = mysqli_fetch_assoc($aqell_result_hasil)['total_hasil'];

// Recent results
$aqell_query_recent = "SELECT h.*, s.aqell_nama_siswa FROM aqell_hasil h
                      JOIN aqell_siswa s ON h.aqell_id_siswa = s.aqell_id_siswa
                      ORDER BY h.aqell_id_hasil DESC LIMIT 5";
$aqell_result_recent = mysqli_query($aqell_conn, $aqell_query_recent);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Dashboard Guru</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../aqell_assets/aqell_css/aqell_style.css">
</head>
<body>
    <div class="aqell_header">
        <h1>Aqell BK</h1>
        <p>Sistem Identifikasi Gaya Belajar Siswa</p>
    </div>
    
    <div class="aqell_nav">
        <a href="aqell_dashboard_guru.php">Dashboard</a>
        <a href="../aqell_kategori/aqell_list_kategori.php">Kategori</a>
        <a href="../aqell_soal/aqell_manage_soal.php">Soal</a>
        <a href="../aqell_paket/aqell_list_paket.php">Paket Ujian</a>
        <a href="../aqell_jadwal/aqell_list_jadwal.php">Jadwal</a>
        <a href="../aqell_hasil/aqell_hasil_all.php">Hasil</a>
        <a href="../aqell_auth/aqell_logout.php">Logout</a>
    </div>
    
    <div class="aqell_container">
        <!-- Sidebar -->
        <div class="aqell_sidebar">
            <h3>Profil Guru</h3>
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="../aqell_assets/aqell_images/avatar.png" alt="avatar" style="width: 100px; height: 100px; border-radius: 50%;">
            </div>
            <p><strong>Nama:</strong> <?php echo $aqell_guru['aqell_nama_guru']; ?></p>
            <p><strong>Username:</strong> <?php echo $_SESSION['aqell_username']; ?></p>
            <p><strong>Level:</strong> <?php echo $aqell_guru['aqell_level']; ?></p>
            <hr>
            <h3>Menu Navigasi</h3>
            <ul>
                <li><a href="aqell_dashboard_guru.php">Dashboard</a></li>
                <li><a href="../aqell_kategori/aqell_list_kategori.php">Kategori</a></li>
                <li><a href="../aqell_soal/aqell_manage_soal.php">Soal</a></li>
                <li><a href="../aqell_paket/aqell_list_paket.php">Paket Ujian</a></li>
                <li><a href="../aqell_jadwal/aqell_list_jadwal.php">Jadwal</a></li>
                <li><a href="../aqell_hasil/aqell_hasil_all.php">Hasil</a></li>
                <li><a href="aqell_edit_profile.php">Edit Profil</a></li>
                <li><a href="../aqell_auth/aqell_logout.php">Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="aqell_content">
            <h2>Selamat Datang, <?php echo $aqell_guru['aqell_nama_guru']; ?>!</h2>
            <p>Ini adalah dashboard Sistem Identifikasi Gaya Belajar. Disini Anda dapat mengelola soal, jadwal ujian, dan melihat hasil tes siswa.</p>
            
            <!-- Dashboard Stats -->
            <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                <div class="aqell_card" style="flex: 1; margin-right: 10px; text-align: center;">
                    <h3>Total Siswa</h3>
                    <p style="font-size: 24px; font-weight: bold;"><?php echo $aqell_total_siswa; ?></p>
                    <a href="../aqell_siswa/aqell_list_siswa.php" class="aqell_btn">Lihat Siswa</a>
                </div>
                
                <div class="aqell_card" style="flex: 1; margin-right: 10px; text-align: center;">
                    <h3>Paket Ujian</h3>
                    <p style="font-size: 24px; font-weight: bold;"><?php echo $aqell_total_paket; ?></p>
                    <a href="../aqell_paket/aqell_list_paket.php" class="aqell_btn">Kelola Paket</a>
                </div>
                
                <div class="aqell_card" style="flex: 1; text-align: center;">
                    <h3>Hasil Tes</h3>
                    <p style="font-size: 24px; font-weight: bold;"><?php echo $aqell_total_hasil; ?></p>
                    <a href="../aqell_hasil/aqell_hasil_all.php" class="aqell_btn">Lihat Hasil</a>
                </div>
            </div>
            
            <!-- Recent Results -->
            <div class="aqell_card">
                <h3>Hasil Tes Terbaru</h3>
                <?php if(mysqli_num_rows($aqell_result_recent) > 0): ?>
                    <table class="aqell_table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Visual</th>
                                <th>Auditori</th>
                                <th>Kinestetik</th>
                                <th>Dominan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($aqell_result_recent)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['aqell_nama_siswa']; ?></td>
                                <td><?php echo $row['aqell_visual']; ?>%</td>
                                <td><?php echo $row['aqell_auditori']; ?>%</td>
                                <td><?php echo $row['aqell_kinestetik']; ?>%</td>
                                <td><strong><?php echo ucfirst($row['aqell_dominan']); ?></strong></td>
                                <td>
                                    <a href="../aqell_hasil/aqell_detail_hasil.php?id=<?php echo $row['aqell_id_hasil']; ?>" class="aqell_btn">Detail</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Belum ada hasil tes siswa.</p>
                <?php endif; ?>
            </div>
            
            <!-- Quick Actions -->
            <div class="aqell_card">
                <h3>Aksi Cepat</h3>
                <div style="display: flex; flex-wrap: wrap;">
                    <a href="../aqell_soal/aqell_add_soal.php" class="aqell_btn" style="margin-right: 10px; margin-bottom: 10px;">Tambah Soal</a>
                    <a href="../aqell_paket/aqell_add_paket.php" class="aqell_btn" style="margin-right: 10px; margin-bottom: 10px;">Tambah Paket Ujian</a>
                    <a href="../aqell_jadwal/aqell_add_jadwal.php" class="aqell_btn" style="margin-right: 10px; margin-bottom: 10px;">Tambah Jadwal</a>
                    <a href="../aqell_hasil/aqell_cetak_hasil.php" class="aqell_btn" style="margin-bottom: 10px;">Cetak Laporan</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
</body>
</html>