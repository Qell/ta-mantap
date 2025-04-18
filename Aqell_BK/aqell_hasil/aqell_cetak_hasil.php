<?php
session_start();
require_once('../aqell_config/aqell_db.php');

// Check if user is logged in and is a teacher
if(!isset($_SESSION['aqell_id_pengguna']) || $_SESSION['aqell_peran'] != 'guru') {
    header("Location: ../aqell_login.php");
    exit();
}

// Get all classes for filter
$aqell_query_kelas = "SELECT DISTINCT aqell_kelas FROM aqell_siswa ORDER BY aqell_kelas";
$aqell_result_kelas = mysqli_query($aqell_conn, $aqell_query_kelas);

// Get all jurusan for filter
$aqell_query_jurusan = "SELECT DISTINCT aqell_jurusan FROM aqell_siswa ORDER BY aqell_jurusan";
$aqell_result_jurusan = mysqli_query($aqell_conn, $aqell_query_jurusan);

// Default query to get all results
$aqell_query_hasil = "SELECT h.*, s.aqell_nama_siswa, s.aqell_kelas, s.aqell_jurusan
                     FROM aqell_hasil h
                     JOIN aqell_siswa s ON h.aqell_id_siswa = s.aqell_id_siswa
                     ORDER BY h.aqell_id_hasil DESC";

// Apply filters if requested
if(isset($_GET['filter'])) {
    $aqell_filter_kelas = isset($_GET['kelas']) ? $_GET['kelas'] : '';
    $aqell_filter_jurusan = isset($_GET['jurusan']) ? $_GET['jurusan'] : '';
    $aqell_filter_dominan = isset($_GET['dominan']) ? $_GET['dominan'] : '';
    
    $aqell_where_clauses = [];
    
    if(!empty($aqell_filter_kelas)) {
        $aqell_where_clauses[] = "s.aqell_kelas = '$aqell_filter_kelas'";
    }
    
    if(!empty($aqell_filter_jurusan)) {
        $aqell_where_clauses[] = "s.aqell_jurusan = '$aqell_filter_jurusan'";
    }
    
    if(!empty($aqell_filter_dominan)) {
        $aqell_where_clauses[] = "h.aqell_dominan = '$aqell_filter_dominan'";
    }
    
    if(count($aqell_where_clauses) > 0) {
        $aqell_query_hasil = "SELECT h.*, s.aqell_nama_siswa, s.aqell_kelas, s.aqell_jurusan
                            FROM aqell_hasil h
                            JOIN aqell_siswa s ON h.aqell_id_siswa = s.aqell_id_siswa
                            WHERE " . implode(' AND ', $aqell_where_clauses) . "
                            ORDER BY h.aqell_id_hasil DESC";
    }
}

$aqell_result_hasil = mysqli_query($aqell_conn, $aqell_query_hasil);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Aqell BK - Cetak Laporan Hasil</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../aqell_assets/aqell_css/aqell_style.css">
    <style>
        @media print {
            .aqell_header, .aqell_nav, .aqell_sidebar, .aqell_footer, .aqell_no_print {
                display: none;
            }
            .aqell_container {
                width: 100%;
                margin: 0;
                padding: 0;
            }
            .aqell_content {
                width: 100%;
                box-shadow: none;
                padding: 0;
            }
            .aqell_card {
                box-shadow: none;
                border: 1px solid #ddd;
            }
            body {
                background: white;
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
        <a href="../aqell_guru/aqell_dashboard_guru.php">Dashboard</a>
        <a href="../aqell_kategori/aqell_list_kategori.php">Kategori</a>
        <a href="../aqell_soal/aqell_manage_soal.php">Soal</a>
        <a href="../aqell_paket/aqell_list_paket.php">Paket Ujian</a>
        <a href="../aqell_jadwal/aqell_list_jadwal.php">Jadwal</a>
        <a href="aqell_hasil_all.php">Hasil</a>
        <a href="../aqell_auth/aqell_logout.php">Logout</a>
    </div>
    
    <div class="aqell_container">
        <!-- Sidebar -->
        <div class="aqell_sidebar">
            <h3>Menu Hasil</h3>
            <ul>
                <li><a href="aqell_hasil_all.php">Semua Hasil</a></li>
                <li><a href="aqell_cetak_hasil.php">Cetak Laporan</a></li>
                <li><a href="../aqell_guru/aqell_dashboard_guru.php">Kembali ke Dashboard</a></li>
            </ul>
        </div>
        
        <!-- Main Content -->
        <div class="aqell_content">
            <h2>Laporan Hasil Tes Gaya Belajar</h2>
            
            <div class="aqell_card aqell_no_print">
                <h3>Filter Hasil</h3>
                <form method="get" action="">
                    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                        <div style="flex: 1;">
                            <label for="kelas">Kelas:</label>
                            <select id="kelas" name="kelas" style="width: 100%;">
                                <option value="">Semua Kelas</option>
                                <?php while($kelas = mysqli_fetch_assoc($aqell_result_kelas)): ?>
                                    <option value="<?php echo $kelas['aqell_kelas']; ?>" <?php echo (isset($_GET['kelas']) && $_GET['kelas'] == $kelas['aqell_kelas']) ? 'selected' : ''; ?>>
                                        <?php echo $kelas['aqell_kelas']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div style="flex: 1;">
                            <label for="jurusan">Jurusan:</label>
                            <select id="jurusan" name="jurusan" style="width: 100%;">
                                <option value="">Semua Jurusan</option>
                                <?php while($jurusan = mysqli_fetch_assoc($aqell_result_jurusan)): ?>
                                    <option value="<?php echo $jurusan['aqell_jurusan']; ?>" <?php echo (isset($_GET['jurusan']) && $_GET['jurusan'] == $jurusan['aqell_jurusan']) ? 'selected' : ''; ?>>
                                        <?php echo $jurusan['aqell_jurusan']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div style="flex: 1;">
                            <label for="dominan">Gaya Belajar Dominan:</label>
                            <select id="dominan" name="dominan" style="width: 100%;">
                                <option value="">Semua</option>
                                <option value="visual" <?php echo (isset($_GET['dominan']) && $_GET['dominan'] == 'visual') ? 'selected' : ''; ?>>Visual</option>
                                <option value="auditori" <?php echo (isset($_GET['dominan']) && $_GET['dominan'] == 'auditori') ? 'selected' : ''; ?>>Auditori</option>
                                <option value="kinestetik" <?php echo (isset($_GET['dominan']) && $_GET['dominan'] == 'kinestetik') ? 'selected' : ''; ?>>Kinestetik</option>
                            </select>
                        </div>
                    </div>
                    
                    <div style="margin-top: 15px;">
                        <button type="submit" name="filter" class="aqell_btn">Filter</button>
                        <button type="button" onclick="window.print();" class="aqell_btn">Cetak Laporan</button>
                        <a href="aqell_cetak_hasil.php" class="aqell_btn aqell_btn_secondary">Reset Filter</a>
                    </div>
                </form>
            </div>
            
            <div class="aqell_card">
                <!-- Print Header (only visible when printing) -->
                <div style="display: none;" class="print_header">
                    <h2 style="text-align: center;">LAPORAN HASIL TES GAYA BELAJAR SISWA</h2>
                    <h3 style="text-align: center;">Sistem Bimbingan Konseling</h3>
                    <p style="text-align: center;">Tanggal Cetak: <?php echo date('d-m-Y'); ?></p>
                    <hr>
                </div>
                
                <?php if(mysqli_num_rows($aqell_result_hasil) > 0): ?>
                    <table class="aqell_table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Visual (%)</th>
                                <th>Auditori (%)</th>
                                <th>Kinestetik (%)</th>
                                <th>Dominan</th>
                                <th class="aqell_no_print">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($row = mysqli_fetch_assoc($aqell_result_hasil)): 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['aqell_nama_siswa']; ?></td>
                                <td><?php echo $row['aqell_kelas']; ?></td>
                                <td><?php echo $row['aqell_jurusan']; ?></td>
                                <td><?php echo $row['aqell_visual']; ?></td>
                                <td><?php echo $row['aqell_auditori']; ?></td>
                                <td><?php echo $row['aqell_kinestetik']; ?></td>
                                <td><strong><?php echo ucfirst($row['aqell_dominan']); ?></strong></td>
                                <td class="aqell_no_print">
                                    <a href="aqell_detail_hasil.php?id=<?php echo $row['aqell_id_hasil']; ?>" class="aqell_btn">Detail</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    
                    <div style="margin-top: 30px; display: none;" class="print_footer">
                        <div style="display: flex; justify-content: space-between;">
                            <div style="width: 40%;">
                                <p>Catatan:</p>
                                <ul>
                                    <li>Visual: Belajar dengan melihat</li>
                                    <li>Auditori: Belajar dengan mendengar</li>
                                    <li>Kinestetik: Belajar dengan praktik langsung</li>
                                </ul>
                            </div>
                            <div style="width: 30%; text-align: center;">
                                <p>Mengetahui,</p>
                                <p>Kepala Sekolah</p>
                                <br><br><br>
                                <p>(_________________)</p>
                            </div>
                            <div style="width: 30%; text-align: center;">
                                <p>Guru BK</p>
                                <br><br><br><br>
                                <p>(_________________)</p>
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <p>Tidak ada data hasil yang ditemukan dengan filter yang dipilih.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="aqell_footer">
        <p>&copy; <?php echo date('Y'); ?> Aqell BK - Sistem Identifikasi Gaya Belajar. All rights reserved.</p>
    </div>
</body>
</html>