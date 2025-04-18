<?php
session_start();
require_once('../aqell_config/aqell_db.php');

// Check if user is logged in
if(!isset($_SESSION['aqell_id_pengguna'])) {
    header("Location: ../aqell_login.php");
    exit();
}

// Check if result ID is provided
if(!isset($_GET['id'])) {
    if($_SESSION['aqell_peran'] == 'siswa') {
        header("Location: aqell_hasil_siswa.php");
    } else {
        header("Location: aqell_hasil_all.php");
    }
    exit();
}

$aqell_id_hasil = $_GET['id'];

// Get result data with package and student info
$aqell_query_hasil = "SELECT h.*, s.aqell_nama_siswa, s.aqell_kelas, s.aqell_jurusan, 
                      p.aqell_nama_paket, k.aqell_nama_kategori 
                      FROM aqell_hasil h
                      JOIN aqell_siswa s ON h.aqell_id_siswa = s.aqell_id_siswa
                      JOIN aqell_paket_ujian p ON h.aqell_id_paket = p.aqell_id_paket
                      JOIN aqell_kategori k ON h.aqell_id_kategori = k.aqell_id_kategori
                      WHERE h.aqell_id_hasil = $aqell_id_hasil";
$aqell_result_hasil = mysqli_query($aqell_conn, $aqell_query_hasil);

// Check if result exists
if(mysqli_num_rows($aqell_result_hasil) == 0) {
    if($_SESSION['aqell_peran'] == 'siswa') {
        header("Location: aqell_hasil_siswa.php");
    } else {
        header("Location: aqell_hasil_all.php");
    }
    exit();
}

$aqell_hasil = mysqli_fetch_assoc($aqell_result_hasil);

// Parse the JSON result data
$aqell_hasil_data = json_decode($aqell_hasil['aqell_hasil'], true);

// If the user is a student, check if it's their own result
if($_SESSION['aqell_peran'] == 'siswa' && $_SESSION['aqell_id_siswa'] != $aqell_hasil['aqell_id_siswa']) {
    header("Location: aqell_hasil_siswa.php");
    exit();
}

// Get recommendations based on learning style
$aqell_recommendations = array(
    'visual' => array(
        'title' => 'Rekomendasi untuk Gaya Belajar Visual',
        'description' => 'Kamu memiliki kecenderungan gaya belajar visual. Ini berarti kamu lebih mudah memahami informasi melalui penglihatan seperti gambar, diagram, dan video.',
        'tips' => array(
            'Gunakan diagram, peta konsep, dan bagan saat belajar atau membuat catatan',
            'Manfaatkan warna dan penanda visual untuk menandai poin-poin penting',
            'Gunakan video pembelajaran dan presentasi visual',
            'Posisikan diri di bagian depan kelas untuk dapat melihat dengan jelas',
            'Gunakan flashcard atau kartu bergambar untuk mengingat informasi'
        )
    ),
    'auditori' => array(
        'title' => 'Rekomendasi untuk Gaya Belajar Auditori',
        'description' => 'Kamu memiliki kecenderungan gaya belajar auditori. Ini berarti kamu lebih mudah memahami informasi melalui pendengaran seperti diskusi, ceramah, dan audio.',
        'tips' => array(
            'Rekam dan dengarkan kembali materi pelajaran',
            'Diskusikan materi dengan teman atau guru',
            'Baca dengan suara keras saat belajar',
            'Gunakan lagu atau rima untuk mengingat informasi',
            'Ikuti kelompok belajar untuk mendiskusikan materi'
        )
    ),
    'kinestetik' => array(
        'title' => 'Rekomendasi untuk Gaya Belajar Kinestetik',
        'description' => 'Kamu memiliki kecenderungan gaya belajar kinestetik. Ini berarti kamu lebih mudah memahami informasi melalui pengalaman fisik seperti praktik, gerakan, dan aktivitas.',
        'tips' => array(
            'Lakukan eksperimen dan aktivitas praktik',
            'Belajar sambil bergerak atau berjalan',
            'Gunakan model 3D atau objek nyata saat belajar',
            'Ambil jeda pendek dan lakukan peregangan saat belajar',
            'Catat informasi sambil berdiri atau dengan posisi yang nyaman'
        )
    )
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Detail Hasil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../aqell_assets/aqell_css/aqell_style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .aqell_result_section {
            margin-bottom: 30px;
        }
        
        .aqell_chart_container {
            width: 100%;
            max-width: 500px;
            margin: 20px auto;
        }
        
        .aqell_recommendation {
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        
        .aqell_recommendation h4 {
            color: #333;
            margin-top: 0;
        }
        
        .aqell_recommendation ul {
            padding-left: 20px;
        }
        
        .aqell_recommendation li {
            margin-bottom: 8px;
        }
        
        .aqell_dominant {
            background-color: #e7f5e7;
            border-left: 4px solid #2e8b57;
            font-weight: bold;
        }
        
        .aqell_result_info {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .aqell_result_info_item {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .aqell_result_info_label {
            font-weight: bold;
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        
        .aqell_result_info_value {
            font-size: 16px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="aqell_header">
        <h1>Aqell BK</h1>
        <p>Sistem Identifikasi Gaya Belajar Siswa</p>
    </div>
    
    <div class="aqell_nav">
        <?php if($_SESSION['aqell_peran'] == 'siswa'): ?>
            <a href="../aqell_siswa/aqell_dashboard_siswa.php">Dashboard</a>
            <a href="../aqell_ujian/aqell_pilih_paket.php">Pilih Paket Ujian</a>
            <a href="aqell_hasil_siswa.php">Hasil Saya</a>
        <?php else: ?>
            <a href="../aqell_guru/aqell_dashboard_guru.php">Dashboard</a>
            <a href="../aqell_kategori/aqell_list_kategori.php">Kategori</a>
            <a href="../aqell_soal/aqell_manage_soal.php">Soal</a>
            <a href="../aqell_paket/aqell_list_paket.php">Paket Ujian</a>
            <a href="../aqell_jadwal/aqell_list_jadwal.php">Jadwal</a>
            <a href="aqell_hasil_all.php">Hasil</a>
        <?php endif; ?>
        <a href="../aqell_auth/aqell_logout.php">Logout</a>
    </div>
    
    <div class="aqell_container">
        <!-- Sidebar -->
        <div class="aqell_sidebar">
            <h3>Menu Hasil</h3>
            <ul>
                <?php if($_SESSION['aqell_peran'] == 'siswa'): ?>
                    <li><a href="aqell_hasil_siswa.php">Kembali ke Hasil Saya</a></li>
                    <li><a href="../aqell_siswa/aqell_dashboard_siswa.php">Dashboard Siswa</a></li>
                <?php else: ?>
                    <li><a href="aqell_hasil_all.php">Semua Hasil</a></li>
                    <li><a href="../aqell_guru/aqell_dashboard_guru.php">Dashboard Guru</a></li>
                <?php endif; ?>
            </ul>
            
            <?php if($_SESSION['aqell_peran'] == 'guru'): ?>
                <hr>
                <h3>Info Siswa</h3>
                <p><strong>Nama:</strong> <?php echo $aqell_hasil['aqell_nama_siswa']; ?></p>
                <p><strong>Kelas:</strong> <?php echo $aqell_hasil['aqell_kelas']; ?></p>
                <p><strong>Jurusan:</strong> <?php echo $aqell_hasil['aqell_jurusan']; ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Main Content -->
        <div class="aqell_content">
            <h2>Detail Hasil Tes Gaya Belajar</h2>
            
            <!-- Basic Info -->
            <div class="aqell_result_info">
                <div class="aqell_result_info_item">
                    <div class="aqell_result_info_label">Nama Paket</div>
                    <div class="aqell_result_info_value"><?php echo $aqell_hasil['aqell_nama_paket']; ?></div>
                </div>
                
                <div class="aqell_result_info_item">
                    <div class="aqell_result_info_label">Kategori</div>
                    <div class="aqell_result_info_value"><?php echo $aqell_hasil['aqell_nama_kategori']; ?></div>
                </div>
                
                <div class="aqell_result_info_item">
                    <div class="aqell_result_info_label">Tanggal Tes</div>
                    <div class="aqell_result_info_value"><?php echo date('d M Y H:i', strtotime($aqell_hasil_data['tanggal'])); ?></div>
                </div>
                
                <div class="aqell_result_info_item">
                    <div class="aqell_result_info_label">Gaya Belajar Dominan</div>
                    <div class="aqell_result_info_value"><?php echo ucfirst($aqell_hasil_data['dominan']); ?></div>
                </div>
            </div>
            
            <!-- Chart -->
            <div class="aqell_result_section">
                <div class="aqell_card">
                    <h3>Grafik Kecenderungan Gaya Belajar</h3>
                    <div class="aqell_chart_container">
                        <canvas id="learningStyleChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Recommendations -->
            <div class="aqell_result_section">
                <div class="aqell_card">
                    <h3>Rekomendasi Berdasarkan Gaya Belajar</h3>
                    
                    <?php foreach(['visual', 'auditori', 'kinestetik'] as $style): ?>
                        <div class="aqell_recommendation <?php echo ($aqell_hasil_data['dominan'] == $style) ? 'aqell_dominant' : ''; ?>">
                            <h4><?php echo $aqell_recommendations[$style]['title']; ?> (<?php echo $aqell_hasil_data[$style]; ?>%)</h4>
                            <p><?php echo $aqell_recommendations[$style]['description']; ?></p>
                            
                            <?php if($aqell_hasil_data['dominan'] == $style || $aqell_hasil_data[$style] >= 30): ?>
                                <h5>Tips Belajar:</h5>
                                <ul>
                                    <?php foreach($aqell_recommendations[$style]['tips'] as $tip): ?>
                                        <li><?php echo $tip; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Summary -->
            <div class="aqell_result_section">
                <div class="aqell_card">
                    <h3>Kesimpulan</h3>
                    <p>Berdasarkan hasil tes, kamu memiliki kecenderungan gaya belajar <strong><?php echo ucfirst($aqell_hasil_data['dominan']); ?></strong>.</p>
                    <p>Ini berarti kamu dapat belajar dengan lebih efektif jika menggunakan metode pembelajaran yang sesuai dengan gaya belajar <?php echo $aqell_hasil_data['dominan']; ?>.</p>
                    <p>Namun, penting untuk diingat bahwa setiap orang memiliki kombinasi gaya belajar yang berbeda-beda. Kamu juga memiliki kecenderungan gaya belajar lainnya, meskipun tidak dominan.</p>
                    <p>Konsultasikan hasil tes ini dengan guru BK untuk mendapatkan bimbingan lebih lanjut.</p>
                </div>
            </div>
            
            <!-- Print/Back Buttons -->
            <div style="text-align: center; margin-top: 20px;">
                <button class="aqell_btn" onclick="window.print()">Cetak Hasil</button>
                <?php if($_SESSION['aqell_peran'] == 'siswa'): ?>
                    <a href="aqell_hasil_siswa.php" class="aqell_btn aqell_btn_secondary">Kembali ke Hasil Saya</a>
                <?php else: ?>
                    <a href="aqell_hasil_all.php" class="aqell_btn aqell_btn_secondary">Kembali ke Semua Hasil</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
    
    <!-- Chart Script -->
    <script>
        const ctx = document.getElementById('learningStyleChart').getContext('2d');
        const learningStyleChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Visual', 'Auditori', 'Kinestetik'],
                datasets: [{
                    label: 'Persentase (%)',
                    data: [
                        <?php echo $aqell_hasil_data['visual']; ?>,
                        <?php echo $aqell_hasil_data['auditori']; ?>,
                        <?php echo $aqell_hasil_data['kinestetik']; ?>
                    ],
                    backgroundColor: [
                        '<?php echo ($aqell_hasil_data['dominan'] == 'visual') ? 'rgba(54, 162, 235, 0.8)' : 'rgba(54, 162, 235, 0.5)'; ?>',
                        '<?php echo ($aqell_hasil_data['dominan'] == 'auditori') ? 'rgba(255, 99, 132, 0.8)' : 'rgba(255, 99, 132, 0.5)'; ?>',
                        '<?php echo ($aqell_hasil_data['dominan'] == 'kinestetik') ? 'rgba(75, 192, 192, 0.8)' : 'rgba(75, 192, 192, 0.5)'; ?>'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>