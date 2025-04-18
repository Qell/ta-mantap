<?php
session_start();
include 'aqell_config.php';

if(isset($_POST['register'])) {
    $aqell_username = $_POST['aqell_username'];
    $aqell_password = $_POST['aqell_password'];
    $aqell_peran = $_POST['aqell_peran'];
    
    // Check if username already exists
    $aqell_check_query = "SELECT * FROM aqell_pengguna WHERE aqell_username='$aqell_username'";
    $aqell_check_result = mysqli_query($aqell_conn, $aqell_check_query);
    
    if(mysqli_num_rows($aqell_check_result) > 0) {
        $aqell_error = "Username sudah digunakan!";
    } else {
        // Insert user into pengguna table
        $aqell_query = "INSERT INTO aqell_pengguna (aqell_username, aqell_password, peran) VALUES ('$aqell_username', '$aqell_password', '$aqell_peran')";
        
        if(mysqli_query($aqell_conn, $aqell_query)) {
            $aqell_id_pengguna = mysqli_insert_id($aqell_conn);
            
            if($aqell_peran == 'siswa') {
                $aqell_nama_siswa = $_POST['aqell_nama'];
                $aqell_kelas = $_POST['aqell_kelas'];
                $aqell_jurusan = $_POST['aqell_jurusan'];
                
                $aqell_query_siswa = "INSERT INTO aqell_siswa (aqell_id_pengguna, aqell_nama_siswa, aqell_kelas, aqell_jurusan) 
                                      VALUES ($aqell_id_pengguna, '$aqell_nama_siswa', '$aqell_kelas', '$aqell_jurusan')";
                
                if(mysqli_query($aqell_conn, $aqell_query_siswa)) {
                    header("Location: index.php");
                    exit();
                } else {
                    $aqell_error = "Error: " . mysqli_error($aqell_conn);
                }
            } else {
                $aqell_nama_guru = $_POST['aqell_nama'];
                $aqell_level = $_POST['aqell_level'];
                
                $aqell_query_guru = "INSERT INTO aqell_guru (aqell_id_pengguna, aqell_nama_guru, aqell_level) 
                                     VALUES ($aqell_id_pengguna, '$aqell_nama_guru', '$aqell_level')";
                
                if(mysqli_query($aqell_conn, $aqell_query_guru)) {
                    header("Location: index.php");
                    exit();
                } else {
                    $aqell_error = "Error: " . mysqli_error($aqell_conn);
                }
            }
        } else {
            $aqell_error = "Error: " . mysqli_error($aqell_conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .register-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .hidden {
            display: none;
        }
    </style>
    <script>
        function toggleFields() {
            var peran = document.getElementById('aqell_peran').value;
            var siswaFields = document.getElementById('siswaFields');
            var guruFields = document.getElementById('guruFields');
            
            if(peran == 'siswa') {
                siswaFields.classList.remove('hidden');
                guruFields.classList.add('hidden');
            } else {
                siswaFields.classList.add('hidden');
                guruFields.classList.remove('hidden');
            }
        }
        
        window.onload = function() {
            toggleFields();
        };
    </script>
</head>
<body>
    <div class="register-container">
        <h2>Aqell BK - Register</h2>
        
        <?php if(isset($aqell_error)): ?>
            <div class="error"><?php echo $aqell_error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label>Username:</label>
                <input type="text" name="aqell_username" required>
            </div>
            
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="aqell_password" required>
            </div>
            
            <div class="form-group">
                <label>Peran:</label>
                <select name="aqell_peran" id="aqell_peran" onchange="toggleFields()" required>
                    <option value="siswa">Siswa</option>
                    <option value="guru">Guru BK</option>
                </select>
            </div>
            
            <div class="form-group">
                <label>Nama:</label>
                <input type="text" name="aqell_nama" required>
            </div>
            
            <div id="siswaFields">
                <div class="form-group">
                    <label>Kelas:</label>
                    <input type="text" name="aqell_kelas">
                </div>
                
                <div class="form-group">
                    <label>Jurusan:</label>
                    <select name="aqell_jurusan">
                        <option value="Rekayasa Perangkat Lunak">Rekayasa Perangkat Lunak</option>
                        <option value="Kimia">Kimia</option>
                        <option value="Mekatronika">Mekatronika</option>
                        <option value="Mesin">Mesin</option>
                        <option value="Animasi">Animasi</option>
                        <option value="Desain Komunikasi Visual">Desain Komunikasi Visual</option>
                    </select>
                </div>
            </div>
            
            <div id="guruFields" class="hidden">
                <div class="form-group">
                    <label>Level:</label>
                    <select name="aqell_level">
                        <option value="Guru BK">Guru BK</option>
                        <option value="Kepala Sekolah">Kepala Sekolah</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" name="register">Register</button>
        </form>
        
        <p>Sudah punya akun? <a href="index.php">Login di sini</a></p>
    </div>
</body>
</html>