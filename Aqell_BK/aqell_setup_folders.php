<?php
// Script to create the folder structure for Aqell BK System
$aqell_folders = [
    'aqell_assets',
    'aqell_assets/aqell_css',
    'aqell_assets/aqell_js',
    'aqell_assets/aqell_images',
    'aqell_config',
    'aqell_auth',
    'aqell_guru',
    'aqell_siswa',
    'aqell_includes',
    'aqell_ujian',
    'aqell_kategori',
    'aqell_paket',
    'aqell_jadwal',
    'aqell_hasil',
    'aqell_soal'
];

// Create folders if they don't exist
foreach ($aqell_folders as $aqell_folder) {
    $aqell_path = __DIR__ . '/' . $aqell_folder;
    if (!file_exists($aqell_path)) {
        mkdir($aqell_path, 0777, true);
        echo "Created folder: $aqell_folder<br>";
    } else {
        echo "Folder already exists: $aqell_folder<br>";
    }
}

echo "Folder structure setup complete!";
?>