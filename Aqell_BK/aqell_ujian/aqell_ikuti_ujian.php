<?php
session_start();
require_once('../aqell_config/aqell_db.php');

// Check if user is logged in and is a student
if(!isset($_SESSION['aqell_id_pengguna']) || $_SESSION['aqell_peran'] != 'siswa') {
    header("Location: ../aqell_login.php");
    exit();
}

// Check if package ID is provided
if(!isset($_GET['paket'])) {
    header("Location: aqell_pilih_paket.php");
    exit();
}

$aqell_id_paket = $_GET['paket'];

// Get package data
$aqell_query_paket = "SELECT p.*, k.aqell_nama_kategori, k.aqell_id_kategori
                      FROM aqell_paket_ujian p 
                      JOIN aqell_kategori k ON p.aqell_id_kategori = k.aqell_id_kategori 
                      WHERE p.aqell_id_paket = $aqell_id_paket";
$aqell_result_paket = mysqli_query($aqell_conn, $aqell_query_paket);

if(mysqli_num_rows($aqell_result_paket) == 0) {
    header("Location: aqell_pilih_paket.php");
    exit();
}

$aqell_paket = mysqli_fetch_assoc($aqell_result_paket);
$aqell_id_kategori = $aqell_paket['aqell_id_kategori'];

// Get student data
$aqell_id_siswa = $_SESSION['aqell_id_siswa'];
$aqell_query_siswa = "SELECT * FROM aqell_siswa WHERE aqell_id_siswa = $aqell_id_siswa";
$aqell_result_siswa = mysqli_query($aqell_conn, $aqell_query_siswa);
$aqell_siswa = mysqli_fetch_assoc($aqell_result_siswa);

// Get questions for this package
$aqell_query_soal = "SELECT * FROM aqell_soal WHERE aqell_id_paket = $aqell_id_paket ORDER BY aqell_id_soal";
$aqell_result_soal = mysqli_query($aqell_conn, $aqell_query_soal);
$aqell_total_soal = mysqli_num_rows($aqell_result_soal);

// Process form submission
if(isset($_POST['submit'])) {
    $aqell_visual = 0;
    $aqell_auditori = 0;
    $aqell_kinestetik = 0;
    $aqell_total_questions = 0;
    $aqell_answers = array();
    
    foreach($_POST as $key => $value) {
        if(strpos($key, 'jawaban_') === 0) {
            $aqell_total_questions++;
            $soal_id = str_replace('jawaban_', '', $key);
            $selected_answers = is_array($value) ? $value : [$value];
            
            $aqell_answers[$soal_id] = $selected_answers;
            
            foreach($selected_answers as $answer) {
                if($answer == 'a') {
                    $aqell_visual++;
                } else if($answer == 'b') {
                    $aqell_auditori++;
                } else if($answer == 'c') {
                    $aqell_kinestetik++;
                }
            }
        }
    }
    
    // Calculate percentages
    if($aqell_total_questions > 0) {
        $aqell_visual_percent = round(($aqell_visual / $aqell_total_questions) * 100);
        $aqell_auditori_percent = round(($aqell_auditori / $aqell_total_questions) * 100);
        $aqell_kinestetik_percent = round(($aqell_kinestetik / $aqell_total_questions) * 100);
        
        // Determine dominant learning style
        $aqell_dominan = 'visual';
        $aqell_max = $aqell_visual_percent;
        
        if($aqell_auditori_percent > $aqell_max) {
            $aqell_dominan = 'auditori';
            $aqell_max = $aqell_auditori_percent;
        }
        
        if($aqell_kinestetik_percent > $aqell_max) {
            $aqell_dominan = 'kinestetik';
        }
        
        // Prepare result data
        $aqell_hasil_data = array(
            'visual' => $aqell_visual_percent,
            'auditori' => $aqell_auditori_percent,
            'kinestetik' => $aqell_kinestetik_percent,
            'dominan' => $aqell_dominan,
            'tanggal' => date('Y-m-d H:i:s'),
            'jawaban' => $aqell_answers
        );
        
        // Convert to JSON
        $aqell_hasil_json = json_encode($aqell_hasil_data);
        
        // Save result to database
        $aqell_insert_result = "INSERT INTO aqell_hasil (aqell_id_siswa, aqell_hasil, aqell_id_kategori, aqell_id_paket) 
                              VALUES ($aqell_id_siswa, '$aqell_hasil_json', $aqell_id_kategori, $aqell_id_paket)";
        
        if(mysqli_query($aqell_conn, $aqell_insert_result)) {
            $aqell_id_hasil = mysqli_insert_id($aqell_conn);
            header("Location: ../aqell_hasil/aqell_detail_hasil.php?id=" . $aqell_id_hasil);
            exit();
        } else {
            $aqell_error = "Gagal menyimpan hasil: " . mysqli_error($aqell_conn);
        }
    } else {
        $aqell_error = "Tidak ada jawaban yang diberikan!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Ikuti Ujian</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../aqell_assets/aqell_css/aqell_style.css">
    <style>
        .aqell_question {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 30px;
            background-color: #f9f9f9;
        }
        
        .aqell_question_number {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
            color: #333;
        }
        
        .aqell_question_text {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.5;
        }
        
        .aqell_options {
            margin-left: 20px;
        }
        
        .aqell_option {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: white;
            transition: background-color 0.2s;
        }
        
        .aqell_option:hover {
            background-color: #f0f0f0;
        }
        
        .aqell_option input[type="checkbox"],
        .aqell_option input[type="radio"] {
            margin-right: 10px;
        }
        
        .aqell_timer {
            position: fixed;
            top: 150px;
            right: 20px;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            z-index: 100;
        }
        
        .aqell_time_remaining {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .aqell_timer {
                position: static;
                margin-bottom: 20px;
            }
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
        <!-- Timer (if needed) -->
        <!--
        <div class="aqell_timer">
            <p>Waktu Tersisa</p>
            <div class="aqell_time_remaining" id="timer">60:00</div>
        </div>
        -->
        
        <!-- Main Content -->
        <div class="aqell_content" style="width: 100%;">
            <div class="aqell_card">
                <h2><?php echo $aqell_paket['aqell_nama_paket']; ?></h2>
                <p><strong>Kategori:</strong> <?php echo $aqell_paket['aqell_nama_kategori']; ?></p>
                <p><strong>Jumlah Soal:</strong> <?php echo $aqell_total_soal; ?></p>
                
                <?php if(isset($aqell_error)): ?>
                    <div class="aqell_alert aqell_alert_error"><?php echo $aqell_error; ?></div>
                <?php endif; ?>
                
                <hr>
                
                <h3>Petunjuk Pengerjaan</h3>
                <p>1. Baca setiap pertanyaan dengan teliti.</p>
                <p>2. Untuk setiap pertanyaan, Anda dapat memilih satu atau lebih jawaban yang sesuai dengan diri Anda.</p>
                <p>3. Tidak ada jawaban yang benar atau salah, pilih jawaban yang paling menggambarkan diri Anda.</p>
                <p>4. Setelah selesai menjawab semua pertanyaan, klik tombol "Selesai & Simpan" di bagian bawah halaman.</p>
                
                <form method="post" action="" id="ujianForm">
                    <?php 
                    $no = 1;
                    mysqli_data_seek($aqell_result_soal, 0);
                    while($soal = mysqli_fetch_assoc($aqell_result_soal)): 
                    ?>
                        <div class="aqell_question">
                            <div class="aqell_question_number">Soal <?php echo $no; ?></div>
                            <div class="aqell_question_text"><?php echo $soal['aqell_isi_soal']; ?></div>
                            
                            <div class="aqell_options">
                                <div class="aqell_option">
                                    <input type="checkbox" id="jawaban_<?php echo $soal['aqell_id_soal']; ?>_a" name="jawaban_<?php echo $soal['aqell_id_soal']; ?>[]" value="a">
                                    <label for="jawaban_<?php echo $soal['aqell_id_soal']; ?>_a"><?php echo $soal['aqell_opsi_a']; ?></label>
                                </div>
                                
                                <div class="aqell_option">
                                    <input type="checkbox" id="jawaban_<?php echo $soal['aqell_id_soal']; ?>_b" name="jawaban_<?php echo $soal['aqell_id_soal']; ?>[]" value="b">
                                    <label for="jawaban_<?php echo $soal['aqell_id_soal']; ?>_b"><?php echo $soal['aqell_opsi_b']; ?></label>
                                </div>
                                
                                <div class="aqell_option">
                                    <input type="checkbox" id="jawaban_<?php echo $soal['aqell_id_soal']; ?>_c" name="jawaban_<?php echo $soal['aqell_id_soal']; ?>[]" value="c">
                                    <label for="jawaban_<?php echo $soal['aqell_id_soal']; ?>_c"><?php echo $soal['aqell_opsi_c']; ?></label>
                                </div>
                            </div>
                        </div>
                    <?php 
                    $no++;
                    endwhile; 
                    ?>
                    
                    <div style="margin-top: 30px; text-align: center;">
                        <button type="submit" name="submit" class="aqell_btn">Selesai & Simpan</button>
                        <a href="aqell_pilih_paket.php" class="aqell_btn aqell_btn_secondary" onclick="return confirm('Apakah Anda yakin ingin membatalkan ujian ini?')">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
    
    <!-- Timer Script (if needed) -->
    <!--
    <script>
        // Set timer for 60 minutes
        let timeLeft = 60 * 60; // in seconds
        const timerElement = document.getElementById('timer');
        
        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            
            timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                document.getElementById('ujianForm').submit();
            }
            
            timeLeft--;
        }
        
        // Update timer every second
        updateTimer();
        const timerInterval = setInterval(updateTimer, 1000);
    </script>
    -->
</body>
</html>