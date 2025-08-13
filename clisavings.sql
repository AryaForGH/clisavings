-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Agu 2025 pada 18.01
-- Versi server: 10.1.38-MariaDB
-- Versi PHP: 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `clisavings`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `celengan`
--

CREATE TABLE `celengan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `nama_tabungan` varchar(100) NOT NULL,
  `target_tabungan` decimal(15,2) NOT NULL,
  `terkumpul` decimal(15,2) NOT NULL DEFAULT '0.00',
  `rencana_pengisian` enum('Harian','Mingguan','Bulanan') NOT NULL,
  `nominal_pengisian` decimal(15,2) NOT NULL,
  `lama_pencapaian` int(11) NOT NULL,
  `tanggal_dibuat` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `histori` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `celengan`
--

INSERT INTO `celengan` (`id`, `user_id`, `foto`, `nama_tabungan`, `target_tabungan`, `terkumpul`, `rencana_pengisian`, `nominal_pengisian`, `lama_pencapaian`, `tanggal_dibuat`, `histori`) VALUES
(1, 1, '1755095863_Infinix-GT-30-Pro.jpg', 'Infinix GT 30 Pro', '4000000.00', '550000.00', 'Bulanan', '100000.00', 1200, '2025-08-13 14:37:43', '[{\"tanggal\":\"2025-08-13 17:21:03\",\"aksi\":\"tambah\",\"nominal\":500000},{\"tanggal\":\"2025-08-13 17:21:38\",\"aksi\":\"kurangi\",\"nominal\":500000},{\"tanggal\":\"2025-08-13 17:21:59\",\"aksi\":\"tambah\",\"nominal\":500000},{\"tanggal\":\"2025-08-13 17:28:28\",\"aksi\":\"tambah\",\"nominal\":100000},{\"tanggal\":\"2025-08-13 17:30:11\",\"aksi\":\"kurangi\",\"nominal\":50000}]');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `nomor_hp` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `nama_lengkap`, `nomor_hp`, `email`, `password`, `created_at`) VALUES
(1, 'cliarym', 'Cliarym', '083857803520', NULL, '$2y$10$NzmpL61cNsw2.9C2rj9G8OF.S4dstWwrBSfQzxpbvzb7w.7cu2PI6', '2025-08-13 14:05:34');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `celengan`
--
ALTER TABLE `celengan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `celengan`
--
ALTER TABLE `celengan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `celengan`
--
ALTER TABLE `celengan`
  ADD CONSTRAINT `celengan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
