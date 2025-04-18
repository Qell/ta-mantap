<?php
session_start();
require_once('../aqell_config/aqell_db.php');

// Check if user is logged in and is a student
if(!isset($_SESSION['aqell_id_pengguna']) || $_SESSION['aqell_peran'] != 'siswa') {
    header("Location: ../aqell_login.php");
    exit();
}

$aqell_id_siswa = $_SESSION['aqell_id_siswa'];

// Get student's test results
$aqell_query_hasil = "SELECT h.*, p.aqell_nama_paket, k.aqell_nama_kategori 
                      FROM aqell_hasil h
                      JOIN aqell_paket_ujian p ON h.aqell_id_paket = p.aqell_id_paket
                      JOIN aqell_kategori k ON h.aqell_id_kategori = k.aqell_id_kategori
                      WHERE h.aqell_id_siswa = $aqell_id_siswa
                      ORDER BY h.aqell_id_hasil DESC";
$aqell_result_hasil = mysqli_query($aqell_conn, $aqell_query_hasil);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Hasil Saya</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../aqell_assets/aqell_css/aqell_style.css">
    <style>
        .aqell_result_card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .aqell_result_info {
            flex: 1;
        }
        
        .aqell_result_info h3 {
            margin-top: 0;
            color: #333;
        }
        
        .aqell_result_meta {
            color: #666;
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .aqell_result_stats {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }
        
        .aqell_stat {
            background-color: #f5f5f5;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 14px;
        }
        
        .aqell_stat_visual {
            border-left: 3px solid #36A2EB;
        }
        
        .aqell_stat_auditori {
            border-left: 3px solid #FF6384;
        }
        
        .aqell_stat_kinestetik {
            border-left: 3px solid #4BC0C0;
        }
        
        .aqell_stat_dominant {
            font-weight: bold;
            background-color: #e7f5e7;
        }
        
        .aqell_result_actions {
            margin-left: 20px;
        }
        
        .aqell_no_results {
            background-color: #f9f9f9;
            padding: 30px;
            text-align: center;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .aqell_no_results h3 {
            color: #666;
        }
        
        .aqell_no_results p {
            margin-bottom: 20px;
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
        <a href="../aqell_ujian/aqell_pilih_paket.php">Pilih Paket Ujian</a>
        <a href="aqell_hasil_siswa.php" class="aqell_active">Hasil Saya</a>
        <a href="../aqell_auth/aqell_logout.php">Logout</a>
    </div>
    
    <div class="aqell_container">
        <!-- Sidebar -->
        <div class="aqell_sidebar">
            <h3>Menu Hasil</h3>
            <ul>
                <li><a href="aqell_hasil_siswa.php" class="aqell_active">Semua Hasil</a></li>
                <li><a href="../aqell_ujian/aqell_pilih_paket.php">Ambil Tes Baru</a></li>
                <li><a href="../aqell_siswa/aqell_dashboard_siswa.php">Dashboard</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="aqell_content">
            <h2>Hasil Tes Gaya Belajar Saya</h2>
            
            <?php if(mysqli_num_rows($aqell_result_hasil) > 0): ?>
                <?php while($aqell_hasil = mysqli_fetch_assoc($aqell_result_hasil)): 
                    // Parse the JSON result data
                    $aqell_hasil_data = json_decode($aqell_hasil['aqell_hasil'], true);
                    $aqell_tanggal = isset($aqell_hasil_data['tanggal']) ? date('d M Y H:i', strtotime($aqell_hasil_data['tanggal'])) : 'N/A';
                ?>
                    <div class="aqell_result_card">
                        <div class="aqell_result_info">
                            <h3><?php echo $aqell_hasil['aqell_nama_paket']; ?></h3>
                            <div class="aqell_result_meta">
                                <span>Kategori: <?php echo $aqell_hasil['aqell_nama_kategori']; ?></span> &bull; 
                                <span>Tanggal: <?php echo $aqell_tanggal; ?></span>
                            </div>
                            <div>Gaya Belajar Dominan: <strong><?php echo ucfirst($aqell_hasil_data['dominan']); ?></strong></div>
                            
                            <div class="aqell_result_stats">
                                <div class="aqell_stat aqell_stat_visual <?php echo ($aqell_hasil_data['dominan'] == 'visual') ? 'aqell_stat_dominant' : ''; ?>">
                                    Visual: <?php echo $aqell_hasil_data['visual']; ?>%
                                </div>
                                <div class="aqell_stat aqell_stat_auditori <?php echo ($aqell_hasil_data['dominan'] == 'auditori') ? 'aqell_stat_dominant' : ''; ?>">
                                    Auditori: <?php echo $aqell_hasil_data['auditori']; ?>%
                                </div>
                                <div class="aqell_stat aqell_stat_kinestetik <?php echo ($aqell_hasil_data['dominan'] == 'kinestetik') ? 'aqell_stat_dominant' : ''; ?>">
                                    Kinestetik: <?php echo $aqell_hasil_data['kinestetik']; ?>%
                                </div>
                            </div>
                        </div>
                        
                        <div class="aqell_result_actions">
                            <a href="aqell_detail_hasil.php?id=<?php echo $aqell_hasil['aqell_id_hasil']; ?>" class="aqell_btn">Lihat Detail</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="aqell_no_results">
                    <h3>Belum Ada Hasil Tes</h3>
                    <p>Kamu belum pernah mengikuti tes gaya belajar. Ambil tes untuk mengetahui gaya belajar kamu.</p>
                    <a href="../aqell_ujian/aqell_pilih_paket.php" class="aqell_btn">Ambil Tes Sekarang</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
</body>
</html>