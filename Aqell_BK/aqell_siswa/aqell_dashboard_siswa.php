<?php
session_start();
require_once('../aqell_config/aqell_db.php');
// Check if user is logged in and is a student
if(!isset($_SESSION['aqell_id_pengguna']) || $_SESSION['aqell_peran'] != 'siswa') {
    header("Location: ../aqell_login.php");
    exit();
}

// Get student data
$aqell_id_siswa = $_SESSION['aqell_id_siswa'];
$aqell_query_siswa = "SELECT * FROM aqell_siswa WHERE aqell_id_siswa = $aqell_id_siswa";
$aqell_result_siswa = mysqli_query($aqell_conn, $aqell_query_siswa);
$aqell_siswa = mysqli_fetch_assoc($aqell_result_siswa);

// Get available packages for this student's class
$aqell_query_paket = "SELECT p.*, k.aqell_nama_kategori, 
                       (SELECT COUNT(*) FROM aqell_soal s WHERE s.aqell_id_paket = p.aqell_id_paket) as jumlah_soal
                       FROM aqell_paket_ujian p 
                       JOIN aqell_kategori k ON p.aqell_id_kategori = k.aqell_id_kategori
                       ORDER BY p.aqell_id_paket DESC LIMIT 5";
$aqell_result_paket = mysqli_query($aqell_conn, $aqell_query_paket);

// Get test results
$aqell_query_hasil = "SELECT * FROM aqell_hasil WHERE aqell_id_siswa = $aqell_id_siswa";
$aqell_result_hasil = mysqli_query($aqell_conn, $aqell_query_hasil);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Dashboard Siswa</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../aqell_assets/aqell_css/aqell_style.css">
    <style>
        .aqell_card_grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .aqell_package_card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20px;
            transition: transform 0.3s ease;
            display: flex;
            flex-direction: column;
        }
        
        .aqell_package_card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        
        .aqell_package_title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .aqell_package_category {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }
        
        .aqell_package_info {
            font-size: 14px;
            margin-bottom: 20px;
            color: #555;
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <div class="aqell_header">
        <h1>Aqell BK</h1>
        <p>Sistem Identifikasi Gaya Belajar Siswa</p>
    </div>
    
    <div class="aqell_nav">
        <a href="aqell_dashboard_siswa.php">Dashboard</a>
        <a href="../aqell_ujian/aqell_pilih_paket.php">Pilih Paket Ujian</a>
        <a href="../aqell_hasil/aqell_hasil_siswa.php">Hasil Saya</a>
        <a href="../aqell_auth/aqell_logout.php">Logout</a>
    </div>
    
    <div class="aqell_container">
        <!-- Sidebar -->
        <div class="aqell_sidebar">
            <h3>Profil Siswa</h3>
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="../aqell_assets/aqell_images/avatar.png" alt="avatar" style="width: 100px; height: 100px; border-radius: 50%;">
            </div>
            <p><strong>Nama:</strong> <?php echo $aqell_siswa['aqell_nama_siswa']; ?></p>
            <p><strong>Username:</strong> <?php echo $_SESSION['aqell_username']; ?></p>
            <p><strong>Kelas:</strong> <?php echo $aqell_siswa['aqell_kelas']; ?></p>
            <p><strong>Jurusan:</strong> <?php echo $aqell_siswa['aqell_jurusan']; ?></p>
            <hr>
            <h3>Menu Navigasi</h3>
            <ul>
                <li><a href="aqell_dashboard_siswa.php">Dashboard</a></li>
                <li><a href="../aqell_ujian/aqell_pilih_paket.php">Pilih Paket Ujian</a></li>
                <li><a href="../aqell_hasil/aqell_hasil_siswa.php">Hasil Saya</a></li>
                <li><a href="aqell_edit_profile.php">Edit Profil</a></li>
                <li><a href="../aqell_auth/aqell_logout.php">Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="aqell_content">
            <h2>Selamat Datang, <?php echo $aqell_siswa['aqell_nama_siswa']; ?>!</h2>
            <p>Ini adalah dashboard Sistem Identifikasi Gaya Belajar. Disini kamu dapat mengakses ujian yang tersedia dan melihat hasil ujian yang telah kamu ikuti.</p>
            
            <div class="aqell_card">
                <h3>Paket Ujian Tersedia</h3>
                <p>Berikut adalah beberapa paket ujian yang tersedia untuk Anda kerjakan:</p>
                
                <?php if(mysqli_num_rows($aqell_result_paket) > 0): ?>
                    <div class="aqell_card_grid">
                        <?php while($paket = mysqli_fetch_assoc($aqell_result_paket)): ?>
                            <div class="aqell_package_card">
                                <div class="aqell_package_title"><?php echo $paket['aqell_nama_paket']; ?></div>
                                <div class="aqell_package_category">Kategori: <?php echo $paket['aqell_nama_kategori']; ?></div>
                                <div class="aqell_package_info">
                                    <p>Jumlah Soal: <?php echo $paket['jumlah_soal']; ?></p>
                                    <?php if($paket['jumlah_soal'] == 0): ?>
                                        <p><em>Belum ada soal dalam paket ini.</em></p>
                                    <?php endif; ?>
                                </div>
                                <?php if($paket['jumlah_soal'] > 0): ?>
                                    <a href="../aqell_ujian/aqell_ikuti_ujian.php?paket=<?php echo $paket['aqell_id_paket']; ?>" class="aqell_btn">Mulai Ujian</a>
                                <?php else: ?>
                                    <button class="aqell_btn" disabled style="background-color: #ccc;">Belum Tersedia</button>
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div style="margin-top: 20px; text-align: center;">
                        <a href="../aqell_ujian/aqell_pilih_paket.php" class="aqell_btn">Lihat Semua Paket Ujian</a>
                    </div>
                <?php else: ?>
                    <p>Tidak ada paket ujian yang tersedia untuk saat ini.</p>
                <?php endif; ?>
            </div>
            
            <div class="aqell_card">
                <h3>Hasil Ujian Terbaru</h3>
                <?php if(mysqli_num_rows($aqell_result_hasil) > 0): ?>
                    <table class="aqell_table">
                        <thead>
                            <tr>
                                <th>No</th>
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
                            while($row = mysqli_fetch_assoc($aqell_result_hasil)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
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
                    <p>Anda belum memiliki hasil ujian. Silakan ikuti ujian yang tersedia.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
</body>
</html>