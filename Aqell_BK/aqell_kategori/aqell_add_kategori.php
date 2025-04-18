<?php
session_start();
require_once('../aqell_config/aqell_db.php');

// Check if user is logged in and is a teacher
if(!isset($_SESSION['aqell_id_pengguna']) || $_SESSION['aqell_peran'] != 'guru') {
    header("Location: ../aqell_login.php");
    exit();
}

// Process form submission
if(isset($_POST['submit'])) {
    $aqell_nama_kategori = $_POST['aqell_nama_kategori'];
    
    // Validate input
    if(empty($aqell_nama_kategori)) {
        $aqell_error = "Nama kategori tidak boleh kosong!";
    } else {
        // Check if category already exists
        $aqell_check_query = "SELECT * FROM aqell_kategori WHERE aqell_nama_kategori = '$aqell_nama_kategori'";
        $aqell_check_result = mysqli_query($aqell_conn, $aqell_check_query);
        
        if(mysqli_num_rows($aqell_check_result) > 0) {
            $aqell_error = "Kategori dengan nama tersebut sudah ada!";
        } else {
            // Insert new category
            $aqell_insert_query = "INSERT INTO aqell_kategori (aqell_nama_kategori) VALUES ('$aqell_nama_kategori')";
            
            if(mysqli_query($aqell_conn, $aqell_insert_query)) {
                $aqell_success = "Kategori berhasil ditambahkan!";
                // Clear form after successful submission
                $aqell_nama_kategori = "";
            } else {
                $aqell_error = "Gagal menambahkan kategori: " . mysqli_error($aqell_conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Tambah Kategori</title>
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
        <a href="aqell_list_kategori.php">Kategori</a>
        <a href="../aqell_soal/aqell_manage_soal.php">Soal</a>
        <a href="../aqell_paket/aqell_list_paket.php">Paket Ujian</a>
        <a href="../aqell_jadwal/aqell_list_jadwal.php">Jadwal</a>
        <a href="../aqell_hasil/aqell_hasil_all.php">Hasil</a>
        <a href="../aqell_auth/aqell_logout.php">Logout</a>
    </div>
    
    <div class="aqell_container">
        <!-- Sidebar -->
        <div class="aqell_sidebar">
            <h3>Menu Kategori</h3>
            <ul>
                <li><a href="aqell_list_kategori.php">Daftar Kategori</a></li>
                <li><a href="aqell_add_kategori.php">Tambah Kategori</a></li>
                <li><a href="../aqell_guru/aqell_dashboard_guru.php">Kembali ke Dashboard</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="aqell_content">
            <h2>Tambah Kategori Baru</h2>
            <p>Silakan isi form berikut untuk menambahkan kategori tes baru.</p>
            
            <?php if(isset($aqell_success)): ?>
                <div class="aqell_alert aqell_alert_success"><?php echo $aqell_success; ?></div>
            <?php endif; ?>
            
            <?php if(isset($aqell_error)): ?>
                <div class="aqell_alert aqell_alert_error"><?php echo $aqell_error; ?></div>
            <?php endif; ?>
            
            <div class="aqell_card">
                <form method="post" action="">
                    <div class="aqell_form_group">
                        <label for="aqell_nama_kategori">Nama Kategori:</label>
                        <input type="text" id="aqell_nama_kategori" name="aqell_nama_kategori" value="<?php echo isset($aqell_nama_kategori) ? $aqell_nama_kategori : ''; ?>" required>
                        <small>Contoh: Tes Gaya Belajar, Tes Minat Bakat, dll.</small>
                    </div>
                    
                    <div class="aqell_form_group">
                        <button type="submit" name="submit" class="aqell_btn">Simpan Kategori</button>
                        <a href="aqell_list_kategori.php" class="aqell_btn aqell_btn_secondary">Batal</a>
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