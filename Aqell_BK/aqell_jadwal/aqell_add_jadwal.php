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

// Get available classes
$aqell_query_kelas = "SELECT * FROM aqell_kelas ORDER BY aqell_nama_kelas";
$aqell_result_kelas = mysqli_query($aqell_conn, $aqell_query_kelas);

// Process form submission
if(isset($_POST['submit'])) {
    $aqell_jam_jadwal = $_POST['aqell_jam_jadwal'];
    $aqell_hari = $_POST['aqell_hari'];
    $aqell_durasi = $_POST['aqell_durasi'];
    $aqell_id_paket = $_POST['aqell_id_paket'];
    $aqell_id_kelas = $_POST['aqell_id_kelas'];
    
    // Validate input
    if(empty($aqell_jam_jadwal) || empty($aqell_hari) || empty($aqell_durasi) || empty($aqell_id_paket) || empty($aqell_id_kelas)) {
        $aqell_error = "Semua field harus diisi!";
    } else {
        // Insert new schedule
        $aqell_insert_query = "INSERT INTO aqell_jadwal (aqell_jam_jadwal, aqell_hari, aqell_durasi, aqell_id_paket, aqell_id_kelas) 
                             VALUES ('$aqell_jam_jadwal', '$aqell_hari', '$aqell_durasi', '$aqell_id_paket', '$aqell_id_kelas')";
        
        if(mysqli_query($aqell_conn, $aqell_insert_query)) {
            $aqell_success = "Jadwal ujian berhasil ditambahkan!";
            // Clear form after successful submission
            $aqell_jam_jadwal = "";
            $aqell_hari = "";
            $aqell_durasi = "";
            $aqell_id_paket = "";
            $aqell_id_kelas = "";
        } else {
            $aqell_error = "Gagal menambahkan jadwal ujian: " . mysqli_error($aqell_conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Tambah Jadwal Ujian</title>
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
        <a href="../aqell_soal/aqell_manage_soal.php">Soal</a>
        <a href="../aqell_paket/aqell_list_paket.php">Paket Ujian</a>
        <a href="aqell_list_jadwal.php">Jadwal</a>
        <a href="../aqell_hasil/aqell_hasil_all.php">Hasil</a>
        <a href="../aqell_auth/aqell_logout.php">Logout</a>
    </div>
    
    <div class="aqell_container">
        <!-- Sidebar -->
        <div class="aqell_sidebar">
            <h3>Menu Jadwal</h3>
            <ul>
                <li><a href="aqell_list_jadwal.php">Daftar Jadwal</a></li>
                <li><a href="aqell_add_jadwal.php">Tambah Jadwal</a></li>
                <li><a href="../aqell_guru/aqell_dashboard_guru.php">Kembali ke Dashboard</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="aqell_content">
            <h2>Tambah Jadwal Ujian Baru</h2>
            <p>Silakan isi form berikut untuk menjadwalkan ujian baru.</p>
            
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
                            <?php while($paket = mysqli_fetch_assoc($aqell_result_paket)): ?>
                                <option value="<?php echo $paket['aqell_id_paket']; ?>" <?php echo (isset($aqell_id_paket) && $aqell_id_paket == $paket['aqell_id_paket']) ? 'selected' : ''; ?>>
                                    <?php echo $paket['aqell_nama_paket']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_id_kelas">Kelas:</label>
                        <select id="aqell_id_kelas" name="aqell_id_kelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            <?php while($kelas = mysqli_fetch_assoc($aqell_result_kelas)): ?>
                                <option value="<?php echo $kelas['aqll_id_kelas']; ?>" <?php echo (isset($aqell_id_kelas) && $aqell_id_kelas == $kelas['aqll_id_kelas']) ? 'selected' : ''; ?>>
                                    <?php echo $kelas['aqell_nama_kelas']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_jam_jadwal">Jam Ujian:</label>
                        <input type="number" id="aqell_jam_jadwal" name="aqell_jam_jadwal" min="0" max="23" value="<?php echo isset($aqell_jam_jadwal) ? $aqell_jam_jadwal : ''; ?>" required>
                        <small>Format 24 jam, contoh: 13 (untuk jam 13:00)</small>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_hari">Hari/Tanggal:</label>
                        <input type="time" id="aqell_hari" name="aqell_hari" value="<?php echo isset($aqell_hari) ? $aqell_hari : ''; ?>" required>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_durasi">Durasi (menit):</label>
                        <input type="number" id="aqell_durasi" name="aqell_durasi" min="5" max="180" value="<?php echo isset($aqell_durasi) ? $aqell_durasi : '60'; ?>" required>
                    </div>
                    
                    <div class="aqell_form_group">
                        <button type="submit" name="submit" class="aqell_btn">Simpan Jadwal</button>
                        <a href="aqell_list_jadwal.php" class="aqell_btn aqell_btn_secondary">Batal</a>
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