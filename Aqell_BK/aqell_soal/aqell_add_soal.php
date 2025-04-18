<?php
session_start();
require_once('../aqell_config/aqell_db.php');

// Check if user is logged in and is a teacher
if(!isset($_SESSION['aqell_id_pengguna']) || $_SESSION['aqell_peran'] != 'guru') {
    header("Location: ../aqell_login.php");
    exit();
}

// Get available test packages
$aqell_query_paket = "SELECT * FROM aqell_paket_ujian ORDER BY aqell_nama_paket";
$aqell_result_paket = mysqli_query($aqell_conn, $aqell_query_paket);

// Check if a specific package ID is provided in the URL
$aqell_selected_paket = '';
if(isset($_GET['paket'])) {
    $aqell_selected_paket = $_GET['paket'];
}

// Process form submission
if(isset($_POST['submit'])) {
    $aqell_isi_soal = $_POST['aqell_isi_soal'];
    $aqell_opsi_a = $_POST['aqell_opsi_a'];
    $aqell_opsi_b = $_POST['aqell_opsi_b'];
    $aqell_opsi_c = $_POST['aqell_opsi_c'];
    $aqell_jawaban = isset($_POST['aqell_jawaban']) ? implode(',', $_POST['aqell_jawaban']) : '';
    $aqell_id_paket = $_POST['aqell_id_paket'];
    
    // Validate input
    if(empty($aqell_isi_soal) || empty($aqell_opsi_a) || empty($aqell_opsi_b) || empty($aqell_opsi_c) || empty($aqell_jawaban) || empty($aqell_id_paket)) {
        $aqell_error = "Semua field harus diisi!";
    } else {
        // Insert new question
        $aqell_insert_query = "INSERT INTO aqell_soal (aqell_id_paket, aqell_isi_soal, aqell_opsi_a, aqell_opsi_b, aqell_opsi_c, aqell_jawaban) 
                             VALUES ('$aqell_id_paket', '$aqell_isi_soal', '$aqell_opsi_a', '$aqell_opsi_b', '$aqell_opsi_c', '$aqell_jawaban')";
        
        if(mysqli_query($aqell_conn, $aqell_insert_query)) {
            $aqell_success = "Soal berhasil ditambahkan ke paket ujian!";
            // Clear form after successful submission
            $aqell_isi_soal = "";
            $aqell_opsi_a = "";
            $aqell_opsi_b = "";
            $aqell_opsi_c = "";
            $aqell_jawaban = "";
            // Keep the selected package
            $aqell_selected_paket = $aqell_id_paket;
        } else {
            $aqell_error = "Gagal menambahkan soal: " . mysqli_error($aqell_conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Tambah Soal</title>
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
        <a href="../aqell_guru/aqell_dashboard_guru.php">Dashboard</a>
        <a href="../aqell_kategori/aqell_list_kategori.php">Kategori</a>
        <a href="aqell_manage_soal.php">Soal</a>
        <a href="../aqell_paket/aqell_list_paket.php">Paket Ujian</a>
        <a href="../aqell_jadwal/aqell_list_jadwal.php">Jadwal</a>
        <a href="../aqell_hasil/aqell_hasil_all.php">Hasil</a>
        <a href="../aqell_auth/aqell_logout.php">Logout</a>
    </div>
    
    <div class="aqell_container">
        <!-- Sidebar -->
        <div class="aqell_sidebar">
            <h3>Menu Soal</h3>
            <ul>
                <li><a href="aqell_manage_soal.php">Daftar Soal</a></li>
                <li><a href="aqell_add_soal.php">Tambah Soal</a></li>
                <li><a href="../aqell_guru/aqell_dashboard_guru.php">Kembali ke Dashboard</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="aqell_content">
            <h2>Tambah Soal Baru</h2>
            <p>Silakan isi form berikut untuk menambahkan soal tes baru ke dalam paket ujian.</p>
            
            <?php if(isset($aqell_success)): ?>
                <div class="aqell_alert aqell_alert_success"><?php echo $aqell_success; ?></div>
            <?php endif; ?>
            
            <?php if(isset($aqell_error)): ?>
                <div class="aqell_alert aqell_alert_error"><?php echo $aqell_error; ?></div>
            <?php endif; ?>
            
            <div class="aqell_card">
                <form method="post" action="">
                    <div class="aqell_form_group">
                        <label for="aqell_id_paket">Paket Ujian:</label>
                        <select id="aqell_id_paket" name="aqell_id_paket" required>
                            <option value="">-- Pilih Paket Ujian --</option>
                            <?php 
                            // Reset the pointer to the beginning
                            mysqli_data_seek($aqell_result_paket, 0);
                            while($paket = mysqli_fetch_assoc($aqell_result_paket)): 
                            ?>
                                <option value="<?php echo $paket['aqell_id_paket']; ?>" <?php echo ($aqell_selected_paket == $paket['aqell_id_paket']) ? 'selected' : ''; ?>>
                                    <?php echo $paket['aqell_nama_paket']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_isi_soal">Pertanyaan:</label>
                        <textarea id="aqell_isi_soal" name="aqell_isi_soal" required><?php echo isset($aqell_isi_soal) ? $aqell_isi_soal : ''; ?></textarea>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_opsi_a">Opsi A (Visual):</label>
                        <textarea id="aqell_opsi_a" name="aqell_opsi_a" required><?php echo isset($aqell_opsi_a) ? $aqell_opsi_a : ''; ?></textarea>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_opsi_b">Opsi B (Auditori):</label>
                        <textarea id="aqell_opsi_b" name="aqell_opsi_b" required><?php echo isset($aqell_opsi_b) ? $aqell_opsi_b : ''; ?></textarea>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_opsi_c">Opsi C (Kinestetik):</label>
                        <textarea id="aqell_opsi_c" name="aqell_opsi_c" required><?php echo isset($aqell_opsi_c) ? $aqell_opsi_c : ''; ?></textarea>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label>Jawaban yang Benar:</label>
                        <div style="margin-top: 10px;">
                            <input type="checkbox" id="jawaban_a" name="aqell_jawaban[]" value="a" <?php echo (isset($aqell_jawaban) && strpos($aqell_jawaban, 'a') !== false) ? 'checked' : ''; ?>>
                            <label for="jawaban_a" style="display: inline;">Opsi A (Visual)</label>
                        </div>
                        <div style="margin-top: 5px;">
                            <input type="checkbox" id="jawaban_b" name="aqell_jawaban[]" value="b" <?php echo (isset($aqell_jawaban) && strpos($aqell_jawaban, 'b') !== false) ? 'checked' : ''; ?>>
                            <label for="jawaban_b" style="display: inline;">Opsi B (Auditori)</label>
                        </div>
                        <div style="margin-top: 5px;">
                            <input type="checkbox" id="jawaban_c" name="aqell_jawaban[]" value="c" <?php echo (isset($aqell_jawaban) && strpos($aqell_jawaban, 'c') !== false) ? 'checked' : ''; ?>>
                            <label for="jawaban_c" style="display: inline;">Opsi C (Kinestetik)</label>
                        </div>
                        <small>Catatan: Untuk tes gaya belajar, semua jawaban bisa benar dan mewakili kecenderungan gaya belajar siswa.</small>
                    </div>
                    
                    <div class="aqell_form_group">
                        <button type="submit" name="submit" class="aqell_btn">Simpan Soal</button>
                        <a href="aqell_manage_soal.php" class="aqell_btn aqell_btn_secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
</body>
</html>