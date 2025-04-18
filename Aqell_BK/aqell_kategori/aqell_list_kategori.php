<?php
session_start();
require_once('../aqell_config/aqell_db.php');

// Check if user is logged in and is a teacher
if(!isset($_SESSION['aqell_id_pengguna']) || $_SESSION['aqell_peran'] != 'guru') {
    header("Location: ../aqell_login.php");
    exit();
}

// Delete category if requested
if(isset($_GET['delete'])) {
    $aqell_id_kategori = $_GET['delete'];
    $aqell_query_delete = "DELETE FROM aqell_kategori WHERE aqell_id_kategori = $aqell_id_kategori";
    if(mysqli_query($aqell_conn, $aqell_query_delete)) {
        $aqell_success = "Kategori berhasil dihapus!";
    } else {
        $aqell_error = "Gagal menghapus kategori: " . mysqli_error($aqell_conn);
    }
}

// Get all categories
$aqell_query_kategori = "SELECT * FROM aqell_kategori ORDER BY aqell_id_kategori DESC";
$aqell_result_kategori = mysqli_query($aqell_conn, $aqell_query_kategori);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Daftar Kategori</title>
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
            <h2>Daftar Kategori Tes</h2>
            <p>Berikut ini adalah daftar kategori tes yang tersedia dalam sistem.</p>
            
            <?php if(isset($aqell_success)): ?>
                <div class="aqell_alert aqell_alert_success"><?php echo $aqell_success; ?></div>
            <?php endif; ?>
            
            <?php if(isset($aqell_error)): ?>
                <div class="aqell_alert aqell_alert_error"><?php echo $aqell_error; ?></div>
            <?php endif; ?>
            
            <div class="aqell_card">
                <div style="margin-bottom: 20px;">
                    <a href="aqell_add_kategori.php" class="aqell_btn">+ Tambah Kategori Baru</a>
                </div>
                
                <?php if(mysqli_num_rows($aqell_result_kategori) > 0): ?>
                    <table class="aqell_table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($aqell_result_kategori)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['aqell_nama_kategori']; ?></td>
                                <td>
                                    <a href="aqell_edit_kategori.php?id=<?php echo $row['aqell_id_kategori']; ?>" class="aqell_btn aqell_btn_secondary">Edit</a>
                                    <a href="aqell_list_kategori.php?delete=<?php echo $row['aqell_id_kategori']; ?>" class="aqell_btn aqell_btn_danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Belum ada kategori yang dibuat.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
</body>
</html>