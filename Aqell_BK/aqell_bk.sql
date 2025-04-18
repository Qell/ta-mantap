-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 19 Apr 2025 pada 00.37
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aqell_bk`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_guru`
--

CREATE TABLE `aqell_guru` (
  `aqell_id_guru` int(11) NOT NULL,
  `aqell_id_pengguna` int(11) NOT NULL,
  `aqell_nama_guru` varchar(50) NOT NULL,
  `aqell_level` enum('Guru BK','Kepala Sekolah') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_guru`
--

INSERT INTO `aqell_guru` (`aqell_id_guru`, `aqell_id_pengguna`, `aqell_nama_guru`, `aqell_level`) VALUES
(1, 8, 'Asep Setiawan', 'Guru BK');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_hasil`
--

CREATE TABLE `aqell_hasil` (
  `aqell_id_siswa` int(11) DEFAULT NULL,
  `aqell_hasil` text NOT NULL,
  `aqell_id_kategori` int(11) NOT NULL,
  `aqell_id_paket` int(11) NOT NULL,
  `aqell_id_hasil` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_hasil`
--

INSERT INTO `aqell_hasil` (`aqell_id_siswa`, `aqell_hasil`, `aqell_id_kategori`, `aqell_id_paket`, `aqell_id_hasil`) VALUES
(6, '{\"visual\":0,\"auditori\":100,\"kinestetik\":0,\"dominan\":\"auditori\",\"tanggal\":\"2025-04-19 00:34:09\",\"jawaban\":{\"38\":[\"b\"],\"39\":[\"b\"]}}', 6, 12, 16),
(6, '{\"visual\":0,\"auditori\":100,\"kinestetik\":0,\"dominan\":\"auditori\",\"tanggal\":\"2025-04-19 00:34:41\",\"jawaban\":{\"38\":[\"b\"],\"39\":[\"b\"]}}', 6, 12, 17);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_jadwal`
--

CREATE TABLE `aqell_jadwal` (
  `aqll_id_jadwal` int(11) NOT NULL,
  `aqell_jam_jadwal` int(11) NOT NULL,
  `aqell_hari` time NOT NULL,
  `aqell_durasi` int(11) NOT NULL,
  `aqell_id_paket` int(11) NOT NULL,
  `aqell_id_kelas` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_jadwal`
--

INSERT INTO `aqell_jadwal` (`aqll_id_jadwal`, `aqell_jam_jadwal`, `aqell_hari`, `aqell_durasi`, `aqell_id_paket`, `aqell_id_kelas`) VALUES
(1, 8, '00:20:25', 60, 2, 5);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_kategori`
--

CREATE TABLE `aqell_kategori` (
  `aqell_id_kategori` int(11) NOT NULL,
  `aqell_nama_kategori` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_kategori`
--

INSERT INTO `aqell_kategori` (`aqell_id_kategori`, `aqell_nama_kategori`) VALUES
(3, 'Tes Bakat'),
(4, 'Tes Prestasi'),
(5, 'Inventaris Minat'),
(6, 'Tes Kecerdasan'),
(7, 'Tes Kepribadian'),
(8, 'Inventori Tugas Perkembangan');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_kelas`
--

CREATE TABLE `aqell_kelas` (
  `aqll_id_kelas` int(11) NOT NULL,
  `aqell_nama_kelas` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_kelas`
--

INSERT INTO `aqell_kelas` (`aqll_id_kelas`, `aqell_nama_kelas`) VALUES
(1, 'X RPL'),
(2, 'X Kimia'),
(3, 'X Mekatronika'),
(4, 'X Mesin'),
(5, 'X Animasi'),
(6, 'X DKV'),
(7, 'XI RPL'),
(8, 'XI Kimia'),
(9, 'XI Mekatronika'),
(10, 'XI Mesin'),
(11, 'XI Animasi'),
(12, 'XI DKV'),
(13, 'XII RPL'),
(14, 'XII Kimia'),
(15, 'XII Mekatronika'),
(16, 'XII Mesin'),
(17, 'XII Animasi'),
(18, 'XII DKV');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_paket_ujian`
--

CREATE TABLE `aqell_paket_ujian` (
  `aqell_id_paket` int(11) NOT NULL,
  `aqell_nama_paket` varchar(30) NOT NULL,
  `aqell_id_kategori` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_paket_ujian`
--

INSERT INTO `aqell_paket_ujian` (`aqell_id_paket`, `aqell_nama_paket`, `aqell_id_kategori`) VALUES
(5, 'Tes Bakat Akademik', 3),
(6, 'Tes Bakat Khusus', 3),
(7, 'Tes Prestasi Belajar IPA', 4),
(8, 'Tes Prestasi Belajar Matematik', 4),
(9, 'Inventaris Minat Karir', 5),
(10, 'Inventaris Minat Hobi', 5),
(11, 'Tes IQ Dasar', 6),
(12, 'Tes Logika Analitik', 6),
(13, 'Tes Kepribadian MBTI', 7),
(14, 'Tes Kepribadian Big Five', 7),
(15, 'Inventori Tugas Perkembangan S', 8),
(16, 'Inventori Tugas Perkembangan S', 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_pengguna`
--

CREATE TABLE `aqell_pengguna` (
  `aqell_id_pengguna` int(11) NOT NULL,
  `aqell_username` varchar(30) NOT NULL,
  `aqell_password` varchar(100) NOT NULL,
  `peran` enum('siswa','guru') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_pengguna`
--

INSERT INTO `aqell_pengguna` (`aqell_id_pengguna`, `aqell_username`, `aqell_password`, `peran`) VALUES
(7, 'agus', '123', 'siswa'),
(8, 'asep', '123', 'guru');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_siswa`
--

CREATE TABLE `aqell_siswa` (
  `aqell_id_siswa` int(11) NOT NULL,
  `aqell_id_pengguna` int(11) NOT NULL,
  `aqell_nama_siswa` varchar(50) NOT NULL,
  `aqell_kelas` varchar(30) NOT NULL,
  `aqell_jurusan` enum('Rekayasa Perangkat Lunak','Kimia','Mekatronika','Mesin','Animasi','Desain Komunikasi Visual') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_siswa`
--

INSERT INTO `aqell_siswa` (`aqell_id_siswa`, `aqell_id_pengguna`, `aqell_nama_siswa`, `aqell_kelas`, `aqell_jurusan`) VALUES
(6, 7, 'Agus Setiawan', 'XI A', 'Rekayasa Perangkat Lunak');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_soal`
--

CREATE TABLE `aqell_soal` (
  `aqell_id_soal` int(11) NOT NULL,
  `aqell_id_paket` int(11) NOT NULL,
  `aqell_isi_soal` text NOT NULL,
  `aqell_jawaban` set('a','b','c') NOT NULL,
  `aqell_opsi_a` text NOT NULL,
  `aqell_opsi_b` text NOT NULL,
  `aqell_opsi_c` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_soal`
--

INSERT INTO `aqell_soal` (`aqell_id_soal`, `aqell_id_paket`, `aqell_isi_soal`, `aqell_jawaban`, `aqell_opsi_a`, `aqell_opsi_b`, `aqell_opsi_c`) VALUES
(24, 5, 'Manakah dari berikut ini yang merupakan hasil perkalian bilangan prima?', 'a', '7 × 11 = 77', '8 × 10 = 80', '5 × 6 = 30'),
(25, 5, 'Jika 4x = 12, berapa nilai x?', 'b', '2', '3', '4'),
(26, 6, 'Alat musik yang dimainkan dengan cara ditiup adalah...', 'b', 'Gitar', 'Saksophone', 'Piano'),
(27, 6, 'Warna dasar pada sistem pencampuran cahaya adalah...', 'c', 'Merah, Hijau, Biru', 'Merah, Kuning, Biru', 'Hijau, Kuning, Biru'),
(28, 7, 'Satuan panjang dalam sistem metrik adalah...', 'a', 'Meter', 'Kilogram', 'Liter'),
(29, 7, 'Proses perubahan air menjadi uap disebut...', 'c', 'Mendidih', 'Membeku', 'Mencair'),
(30, 8, 'Hasil dari 8 × 7 - 10 adalah...', 'b', '50', '46', '56'),
(31, 8, 'Bangun datar dengan dua pasang sisi yang sama panjang adalah...', 'a', 'Persegi panjang', 'Segitiga', 'Lingkaran'),
(32, 9, 'Apakah kamu lebih tertarik pada pekerjaan yang berhubungan dengan...', 'b', 'Kehidupan alam', 'Komputer dan teknologi', 'Hukum dan politik'),
(33, 9, 'Pekerjaan yang lebih menarik bagimu adalah...', 'a', 'Mengajar', 'Mengecat', 'Berkebun'),
(34, 10, 'Apakah kamu menikmati kegiatan di luar ruangan seperti...', 'a', 'Berjalan-jalan', 'Bermain catur', 'Membaca buku'),
(35, 10, 'Kegiatan yang lebih kamu nikmati adalah...', 'c', 'Berolahraga', 'Berbicara dengan orang banyak', 'Menggambar'),
(36, 11, 'Hasil dari 20 ÷ 4 + 3 adalah...', 'a', '8', '7', '9'),
(37, 11, 'Jika dua angka berturut-turut memiliki selisih 4, maka angka yang lebih kecil adalah...', 'b', '5', '6', '7'),
(38, 12, 'Jika semua A adalah B dan semua B adalah C, maka...', 'c', 'Semua A adalah C', 'Beberapa A adalah C', 'Beberapa A adalah B'),
(39, 12, 'Jika dua angka bertambah 5 dan hasilnya 13, maka angka pertama adalah...', 'a', '8', '9', '7'),
(40, 13, 'Apakah kamu lebih suka bekerja secara...', 'b', 'Sendiri', 'Berdua', 'Kelompok besar'),
(41, 13, 'Jika ada pilihan antara berbicara dengan banyak orang atau sedikit orang, mana yang lebih kamu pilih?', 'a', 'Sedikit orang', 'Banyak orang', 'Tidak masalah'),
(42, 14, 'Seberapa sering kamu merasa cemas atau khawatir?', 'b', 'Sering', 'Jarang', 'Tidak pernah'),
(43, 14, 'Seberapa terbuka kamu terhadap ide-ide baru?', 'a', 'Sangat terbuka', 'Terkadang', 'Tidak terbuka'),
(44, 15, 'Apakah kamu merasa siap untuk tugas-tugas yang akan datang?', 'a', 'Ya', 'Tidak', 'Ragu-ragu'),
(45, 15, 'Apa yang lebih kamu pilih untuk dikerjakan setelah sekolah?', 'b', 'Pekerjaan rumah', 'Bermain dengan teman', 'Tidur'),
(46, 16, 'Apakah kamu merasa tugas sekolah memberikan tantangan yang sesuai dengan kemampuanmu?', 'c', 'Terkadang', 'Selalu', 'Tidak pernah'),
(47, 16, 'Bagaimana perasaanmu saat mendapat tugas kelompok?', 'a', 'Menyenankan', 'Bosan', 'Tidak suka');

-- --------------------------------------------------------

--
-- Struktur dari tabel `aqell_ujian`
--

CREATE TABLE `aqell_ujian` (
  `aqell_id_ujian` int(11) NOT NULL,
  `aqell_id_siswa` int(11) NOT NULL,
  `aqell_id_jadwal` int(11) NOT NULL,
  `aqell_status` enum('belum selesai','selesai') NOT NULL,
  `aqell_jawaban` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `aqell_ujian`
--

INSERT INTO `aqell_ujian` (`aqell_id_ujian`, `aqell_id_siswa`, `aqell_id_jadwal`, `aqell_status`, `aqell_jawaban`) VALUES
(1, 6, 1, 'selesai', 1);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `aqell_guru`
--
ALTER TABLE `aqell_guru`
  ADD PRIMARY KEY (`aqell_id_guru`),
  ADD KEY `fk_pengguna_guru` (`aqell_id_pengguna`);

--
-- Indeks untuk tabel `aqell_hasil`
--
ALTER TABLE `aqell_hasil`
  ADD PRIMARY KEY (`aqell_id_hasil`),
  ADD KEY `aqell_id_siswa` (`aqell_id_siswa`),
  ADD KEY `fk_paket_hasil` (`aqell_id_paket`),
  ADD KEY `fk_kategori_hasil` (`aqell_id_kategori`);

--
-- Indeks untuk tabel `aqell_jadwal`
--
ALTER TABLE `aqell_jadwal`
  ADD PRIMARY KEY (`aqll_id_jadwal`),
  ADD KEY `aqell_id_kelas` (`aqell_id_kelas`),
  ADD KEY `aqell_id_paket` (`aqell_id_paket`);

--
-- Indeks untuk tabel `aqell_kategori`
--
ALTER TABLE `aqell_kategori`
  ADD PRIMARY KEY (`aqell_id_kategori`);

--
-- Indeks untuk tabel `aqell_kelas`
--
ALTER TABLE `aqell_kelas`
  ADD PRIMARY KEY (`aqll_id_kelas`);

--
-- Indeks untuk tabel `aqell_paket_ujian`
--
ALTER TABLE `aqell_paket_ujian`
  ADD PRIMARY KEY (`aqell_id_paket`),
  ADD KEY `aqell_id_kategori` (`aqell_id_kategori`);

--
-- Indeks untuk tabel `aqell_pengguna`
--
ALTER TABLE `aqell_pengguna`
  ADD PRIMARY KEY (`aqell_id_pengguna`),
  ADD UNIQUE KEY `aqell_username` (`aqell_username`);

--
-- Indeks untuk tabel `aqell_siswa`
--
ALTER TABLE `aqell_siswa`
  ADD PRIMARY KEY (`aqell_id_siswa`),
  ADD KEY `fk_pengguna_siswa` (`aqell_id_pengguna`);

--
-- Indeks untuk tabel `aqell_soal`
--
ALTER TABLE `aqell_soal`
  ADD PRIMARY KEY (`aqell_id_soal`),
  ADD KEY `aqell_id_paket` (`aqell_id_paket`);

--
-- Indeks untuk tabel `aqell_ujian`
--
ALTER TABLE `aqell_ujian`
  ADD PRIMARY KEY (`aqell_id_ujian`),
  ADD KEY `aqell_id_jadwal` (`aqell_id_jadwal`),
  ADD KEY `aqell_id_siswa` (`aqell_id_siswa`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `aqell_guru`
--
ALTER TABLE `aqell_guru`
  MODIFY `aqell_id_guru` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `aqell_hasil`
--
ALTER TABLE `aqell_hasil`
  MODIFY `aqell_id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT untuk tabel `aqell_jadwal`
--
ALTER TABLE `aqell_jadwal`
  MODIFY `aqll_id_jadwal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `aqell_kategori`
--
ALTER TABLE `aqell_kategori`
  MODIFY `aqell_id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `aqell_kelas`
--
ALTER TABLE `aqell_kelas`
  MODIFY `aqll_id_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT untuk tabel `aqell_paket_ujian`
--
ALTER TABLE `aqell_paket_ujian`
  MODIFY `aqell_id_paket` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT untuk tabel `aqell_pengguna`
--
ALTER TABLE `aqell_pengguna`
  MODIFY `aqell_id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `aqell_siswa`
--
ALTER TABLE `aqell_siswa`
  MODIFY `aqell_id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `aqell_soal`
--
ALTER TABLE `aqell_soal`
  MODIFY `aqell_id_soal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT untuk tabel `aqell_ujian`
--
ALTER TABLE `aqell_ujian`
  MODIFY `aqell_id_ujian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `aqell_guru`
--
ALTER TABLE `aqell_guru`
  ADD CONSTRAINT `fk_pengguna_guru` FOREIGN KEY (`aqell_id_pengguna`) REFERENCES `aqell_pengguna` (`aqell_id_pengguna`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `aqell_hasil`
--
ALTER TABLE `aqell_hasil`
  ADD CONSTRAINT `aqell_hasil_ibfk_1` FOREIGN KEY (`aqell_id_siswa`) REFERENCES `aqell_siswa` (`aqell_id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_kategori_hasil` FOREIGN KEY (`aqell_id_kategori`) REFERENCES `aqell_kategori` (`aqell_id_kategori`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_paket_hasil` FOREIGN KEY (`aqell_id_paket`) REFERENCES `aqell_paket_ujian` (`aqell_id_paket`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `aqell_paket_ujian`
--
ALTER TABLE `aqell_paket_ujian`
  ADD CONSTRAINT `aqell_id_kategori` FOREIGN KEY (`aqell_id_kategori`) REFERENCES `aqell_kategori` (`aqell_id_kategori`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `aqell_siswa`
--
ALTER TABLE `aqell_siswa`
  ADD CONSTRAINT `fk_pengguna_siswa` FOREIGN KEY (`aqell_id_pengguna`) REFERENCES `aqell_pengguna` (`aqell_id_pengguna`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `aqell_soal`
--
ALTER TABLE `aqell_soal`
  ADD CONSTRAINT `aqell_id_paket` FOREIGN KEY (`aqell_id_paket`) REFERENCES `aqell_paket_ujian` (`aqell_id_paket`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `aqell_ujian`
--
ALTER TABLE `aqell_ujian`
  ADD CONSTRAINT `aqell_ujian_ibfk_1` FOREIGN KEY (`aqell_id_jadwal`) REFERENCES `aqell_jadwal` (`aqll_id_jadwal`) ON DELETE CASCADE,
  ADD CONSTRAINT `aqell_ujian_ibfk_2` FOREIGN KEY (`aqell_id_siswa`) REFERENCES `aqell_siswa` (`aqell_id_siswa`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
