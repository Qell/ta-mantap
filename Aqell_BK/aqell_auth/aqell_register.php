<?php
session_start();
require_once('../aqell_config/aqell_db.php');

// Check if user is already logged in
if(isset($_SESSION['aqell_id_pengguna'])) {
    if($_SESSION['aqell_peran'] == 'siswa') {
        header("Location: ../aqell_siswa/aqell_dashboard_siswa.php");
        exit();
    } else if($_SESSION['aqell_peran'] == 'guru') {
        header("Location: ../aqell_guru/aqell_dashboard_guru.php");
        exit();
    }
}

// Process registration form
if(isset($_POST['register'])) {
    // Get form data
    $aqell_username = $_POST['aqell_username'];
    $aqell_password = $_POST['aqell_password'];
    $aqell_nama = $_POST['aqell_nama'];
    $aqell_kelas = $_POST['aqell_kelas'];
    $aqell_jurusan = $_POST['aqell_jurusan'];
    
    // Check if username already exists
    $aqell_check_query = "SELECT * FROM aqell_pengguna WHERE aqell_username = '$aqell_username'";
    $aqell_check_result = mysqli_query($aqell_conn, $aqell_check_query);
    
    if(mysqli_num_rows($aqell_check_result) > 0) {
        $aqell_error = "Username sudah digunakan. Silakan gunakan username lain.";
    } else {
        // Create user account (role 'siswa')
        $aqell_insert_user = "INSERT INTO aqell_pengguna (aqell_username, aqell_password, peran) VALUES ('$aqell_username', '$aqell_password', 'siswa')";
        
        if(mysqli_query($aqell_conn, $aqell_insert_user)) {
            $aqell_id_pengguna = mysqli_insert_id($aqell_conn);
            
            // Create student profile
            $aqell_insert_siswa = "INSERT INTO aqell_siswa (aqell_id_pengguna, aqell_nama_siswa, aqell_kelas, aqell_jurusan) VALUES ('$aqell_id_pengguna', '$aqell_nama', '$aqell_kelas', '$aqell_jurusan')";
            
            if(mysqli_query($aqell_conn, $aqell_insert_siswa)) {
                $aqell_success = "Registrasi berhasil. Silakan login.";
                
                // Redirect to login page after 2 seconds
                header("refresh:2;url=../aqell_login.php");
            } else {
                $aqell_error = "Gagal membuat profil siswa: " . mysqli_error($aqell_conn);
                
                // Delete user account if student profile creation fails
                $aqell_delete_user = "DELETE FROM aqell_pengguna WHERE aqell_id_pengguna = '$aqell_id_pengguna'";
                mysqli_query($aqell_conn, $aqell_delete_user);
            }
        } else {
            $aqell_error = "Gagal membuat akun: " . mysqli_error($aqell_conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Registrasi</title>
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
        <a href="../index.php">Beranda</a>
        <a href="../aqell_login.php">Login</a>
        <a href="../index.php#tentang">Tentang</a>
        <a href="../index.php#kontak">Kontak</a>
    </div>
    
    <div class="aqell_container">
        <div class="aqell_content" style="max-width: 600px; margin: 0 auto;">
            <div class="aqell_card">
                <h2 style="text-align: center;">Registrasi Akun Siswa</h2>
                <p style="text-align: center;">Silakan isi form berikut untuk mendaftar sebagai siswa</p>
                
                <?php if(isset($aqell_error)): ?>
                    <div class="aqell_alert aqell_alert_error"><?php echo $aqell_error; ?></div>
                <?php endif; ?>
                
                <?php if(isset($aqell_success)): ?>
                    <div class="aqell_alert aqell_alert_success"><?php echo $aqell_success; ?></div>
                <?php endif; ?>
                
                <form method="post" action="">
                    <div class="aqell_form_group">
                        <label for="aqell_username">Username:</label>
                        <input type="text" id="aqell_username" name="aqell_username" required>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_password">Password:</label>
                        <input type="password" id="aqell_password" name="aqell_password" required>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_nama">Nama Lengkap:</label>
                        <input type="text" id="aqell_nama" name="aqell_nama" required>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_kelas">Kelas:</label>
                        <input type="text" id="aqell_kelas" name="aqell_kelas" placeholder="Contoh: X, XI, XII" required>
                    </div>
                    
                    <div class="aqell_form_group">
                        <label for="aqell_jurusan">Jurusan:</label>
                        <select id="aqell_jurusan" name="aqell_jurusan" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                            <option value="Kimia">Kimia</option>
                            <option value="Mekatronika">Mekatronika</option>
                            <option value="Mesin">Mesin</option>
                            <option value="Animasi">Animasi</option>
                            <option value="Desain Komunikasi Visual">Desain Komunikasi Visual</option>
                        </select>
                    </div>
                    
                    <div class="aqell_form_group" style="text-align: center;">
                        <button type="submit" name="register" class="aqell_btn">Daftar</button>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 20px;">
                    <p>Sudah memiliki akun? <a href="../aqell_login.php">Login Disini</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
</body>
</html>