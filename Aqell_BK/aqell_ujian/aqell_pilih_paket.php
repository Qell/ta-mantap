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

// Get all available categories
$aqell_query_kategori = "SELECT * FROM aqell_kategori ORDER BY aqell_nama_kategori";
$aqell_result_kategori = mysqli_query($aqell_conn, $aqell_query_kategori);

// Selected category (if any)
$aqell_selected_kategori = isset($_GET['kategori']) ? $_GET['kategori'] : null;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Pilih Paket Ujian</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../aqell_assets/aqell_css/aqell_style.css">
    <style>
        .aqell_package_grid {
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
        
        .aqell_filter_bar {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .aqell_filter_item {
            padding: 8px 15px;
            background: #f0f0f0;
            border-radius: 20px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .aqell_filter_item:hover, .aqell_filter_active {
            background: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <div class="aqell_header">
        <h1>Aqell BK</h1>
        <p>Sistem Identifikasi Gaya Belajar Siswa</p>
    </div>
    
    <div class="aqell_nav">
        <a href="../aqell_siswa/aqell_dashboard_siswa.php">Dashboard</a>
        <a href="aqell_list_aktif.php">Ujian Aktif</a>
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
                <li><a href="../aqell_siswa/aqell_dashboard_siswa.php">Dashboard</a></li>
                <li><a href="aqell_list_aktif.php">Ujian Aktif</a></li>
                <li><a href="../aqell_hasil/aqell_hasil_siswa.php">Hasil Saya</a></li>
                <li><a href="../aqell_siswa/aqell_edit_profile.php">Edit Profil</a></li>
                <li><a href="../aqell_auth/aqell_logout.php">Logout</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="aqell_content">
            <h2>Pilih Paket Ujian</h2>
            <p>Silakan pilih paket ujian yang ingin dikerjakan dari daftar berikut ini.</p>
            
            <!-- Category Filter -->
            <div class="aqell_card">
                <h3>Filter Berdasarkan Kategori</h3>
                <div class="aqell_filter_bar">
                    <a href="aqell_pilih_paket.php" class="aqell_filter_item <?php echo !$aqell_selected_kategori ? 'aqell_filter_active' : ''; ?>">
                        Semua
                    </a>
                    <?php 
                    mysqli_data_seek($aqell_result_kategori, 0);
                    while($kategori = mysqli_fetch_assoc($aqell_result_kategori)): 
                    ?>
                    <a href="aqell_pilih_paket.php?kategori=<?php echo $kategori['aqell_id_kategori']; ?>" 
                       class="aqell_filter_item <?php echo $aqell_selected_kategori == $kategori['aqell_id_kategori'] ? 'aqell_filter_active' : ''; ?>">
                        <?php echo $kategori['aqell_nama_kategori']; ?>
                    </a>
                    <?php endwhile; ?>
                </div>
            </div>
            
            <!-- Packages Grid -->
            <div class="aqell_package_grid">
                <?php
                // Build the query based on filter
                $aqell_query_paket = "SELECT p.*, k.aqell_nama_kategori, 
                                      (SELECT COUNT(*) FROM aqell_soal s WHERE s.aqell_id_paket = p.aqell_id_paket) as jumlah_soal
                                      FROM aqell_paket_ujian p 
                                      JOIN aqell_kategori k ON p.aqell_id_kategori = k.aqell_id_kategori";
                
                if($aqell_selected_kategori) {
                    $aqell_query_paket .= " WHERE p.aqell_id_kategori = '$aqell_selected_kategori'";
                }
                
                $aqell_query_paket .= " ORDER BY p.aqell_nama_paket";
                $aqell_result_paket = mysqli_query($aqell_conn, $aqell_query_paket);
                
                if(mysqli_num_rows($aqell_result_paket) > 0) {
                    while($paket = mysqli_fetch_assoc($aqell_result_paket)) {
                        ?>
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
                                <a href="aqell_ikuti_ujian.php?paket=<?php echo $paket['aqell_id_paket']; ?>" class="aqell_btn">Mulai Ujian</a>
                            <?php else: ?>
                                <button class="aqell_btn" disabled style="background-color: #ccc;">Belum Tersedia</button>
                            <?php endif; ?>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p>Tidak ada paket ujian yang tersedia saat ini.</p>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
</body>
</html>