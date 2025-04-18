<?php
session_start();
require_once('aqell_config/aqell_db.php');

// Check if user is already logged in
if(isset($_SESSION['aqell_id_pengguna'])) {
    if($_SESSION['aqell_peran'] == 'siswa') {
        header("Location: aqell_siswa/aqell_dashboard_siswa.php");
        exit();
    } else if($_SESSION['aqell_peran'] == 'guru') {
        header("Location: aqell_guru/aqell_dashboard_guru.php");
        exit();
    }
}

// Process login form
if(isset($_POST['login'])) {
    $aqell_username = $_POST['aqell_username'];
    $aqell_password = $_POST['aqell_password'];
    
    // Get user data - simple direct comparison
    $aqell_query = "SELECT * FROM aqell_pengguna WHERE aqell_username = '$aqell_username' AND aqell_password = '$aqell_password'";
    $aqell_result = mysqli_query($aqell_conn, $aqell_query);
    
    if(mysqli_num_rows($aqell_result) == 1) {
        $aqell_user = mysqli_fetch_assoc($aqell_result);
        
        // Save user data to session
        $_SESSION['aqell_id_pengguna'] = $aqell_user['aqell_id_pengguna'];
        $_SESSION['aqell_username'] = $aqell_user['aqell_username'];
        $_SESSION['aqell_peran'] = $aqell_user['peran'];
        
        // Get additional data based on role
        if($aqell_user['peran'] == 'siswa') {
            $aqell_query_siswa = "SELECT * FROM aqell_siswa WHERE aqell_id_pengguna = " . $aqell_user['aqell_id_pengguna'];
            $aqell_result_siswa = mysqli_query($aqell_conn, $aqell_query_siswa);
            
            if(mysqli_num_rows($aqell_result_siswa) > 0) {
                $aqell_siswa = mysqli_fetch_assoc($aqell_result_siswa);
                $_SESSION['aqell_id_siswa'] = $aqell_siswa['aqell_id_siswa'];
            }
            
            header("Location: aqell_siswa/aqell_dashboard_siswa.php");
            exit();
        } else if($aqell_user['peran'] == 'guru') {
            $aqell_query_guru = "SELECT * FROM aqell_guru WHERE aqell_id_pengguna = " . $aqell_user['aqell_id_pengguna'];
            $aqell_result_guru = mysqli_query($aqell_conn, $aqell_query_guru);
            
            if(mysqli_num_rows($aqell_result_guru) > 0) {
                $aqell_guru = mysqli_fetch_assoc($aqell_result_guru);
                $_SESSION['aqell_id_guru'] = $aqell_guru['aqell_id_guru'];
            }
            
            header("Location: aqell_guru/aqell_dashboard_guru.php");
            exit();
        }
    } else {
        $aqell_error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="aqell_assets/aqell_css/aqell_style.css">
</head>
<body>
    <div class="aqell_header">
        <h1>Aqell BK</h1>
        <p>Sistem Identifikasi Gaya Belajar Siswa</p>
    </div>
    
    <div class="aqell_nav">
        <a href="index.php">Beranda</a>
        <a href="aqell_login.php">Login</a>
        <a href="index.php#tentang">Tentang</a>
        <a href="index.php#kontak">Kontak</a>
    </div>
    
    <div class="aqell_container">
        <div class="aqell_content" style="max-width: 500px; margin: 0 auto;">
            <div class="aqell_card">
                <h2 style="text-align: center;">Login Sistem</h2>
                <p style="text-align: center;">Masukkan username dan password Anda</p>
                
                <?php if(isset($aqell_error)): ?>
                    <div class="aqell_alert aqell_alert_error"><?php echo $aqell_error; ?></div>
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
                    
                    <div class="aqell_form_group" style="text-align: center;">
                        <button type="submit" name="login" class="aqell_btn">Login</button>
                    </div>
                </form>
                
                <div style="text-align: center; margin-top: 20px;">
                    <p>Belum memiliki akun? <a href="aqell_auth/aqell_register.php">Daftar Disini</a></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
</body>
</html>