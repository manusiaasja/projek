-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Nov 2024 pada 13.45
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
-- Database: `tabungan_siswa`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `kelas`
--

CREATE TABLE `kelas` (
  `kelas` varchar(50) NOT NULL,
  `jurusan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `kelas`
--

INSERT INTO `kelas` (`kelas`, `jurusan`) VALUES
('10', 'DKV1'),
('10', 'DKV2'),
('10', 'PPLG1'),
('10', 'PPLG2'),
('10', 'PPLG3'),
('11', 'DKV1'),
('11', 'DKV2'),
('11', 'PPLG1'),
('11', 'PPLG2'),
('12', 'MM1'),
('12', 'MM2'),
('12', 'RPL1'),
('12', 'RPL2');

-- --------------------------------------------------------

--
-- Struktur dari tabel `siswa`
--

CREATE TABLE `siswa` (
  `id_siswa` int(11) NOT NULL,
  `nama_siswa` varchar(250) NOT NULL,
  `kelas` varchar(10) NOT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `jurusan` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `siswa`
--

INSERT INTO `siswa` (`id_siswa`, `nama_siswa`, `kelas`, `nisn`, `password`, `jurusan`) VALUES
(4, 'Nurfitri Ullfiah Ramadhani Awo', '12', '92885167', '$2y$10$lmG5t7suEgsic3CFF.tkbOaqSRk5rf87pMcIJNXf9rJnkHhDUZKNa', 'RPL1'),
(6, 'Citrafitri Yulyanti Rahimun ', '11', '3073459281', '$2y$10$.16Ha71VpWyXRFMjrm/Cjuny705AAMRm7fR/eIX7gL4WEPrHP5W2O', 'RPL1'),
(7, 'Intan Rahmawati', '12', '3079205963', '$2y$10$marxyQlNZdmswJC7FRrr..NXIcf.tW1GM1gSne0oFnMAW9X6rPOf6', 'RPL1'),
(9, 'Izzah Faradhilah', '12', '72554352', '', 'RPL1'),
(10, 'Serina Nur Aszaidah', '12', '55597049', '', 'RPL1'),
(11, 'Fivel Marah', '12', '3074750931', '', 'RPL1'),
(12, 'Sausan Syafiqah', '12', '76407426', '', 'RPL1'),
(16, 'Iramazatil Imadah', '10', '73842010', '', 'RPL1'),
(23, 'alma', '12', '0028932', '', 'RPL1'),
(24, 'Salsa', '12', '002823281', '', 'RPL1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi_tabungan`
--

CREATE TABLE `transaksi_tabungan` (
  `id_transaksi` int(20) NOT NULL,
  `id_siswa` int(20) NOT NULL,
  `jenis_transaksi` enum('setoran','penarikan') NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `tanggal_transaksi` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nomor_referensi` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi_tabungan`
--

INSERT INTO `transaksi_tabungan` (`id_transaksi`, `id_siswa`, `jenis_transaksi`, `jumlah`, `tanggal_transaksi`, `nomor_referensi`) VALUES
(69, 6, 'setoran', 2000000.00, '2024-10-22 05:54:38', 'ST1215905'),
(70, 7, 'setoran', 300000.00, '2024-10-22 05:55:02', 'ST1247609'),
(86, 7, 'penarikan', 250000.00, '2024-10-25 04:44:35', 'PN1298276'),
(87, 23, 'setoran', 2000000.00, '2024-10-25 08:12:36', 'ST1284763'),
(88, 23, 'penarikan', 1000000.00, '2024-10-25 08:13:08', 'PN1287688'),
(89, 11, 'setoran', 10000000.00, '2024-11-06 06:18:25', 'ST1237961'),
(90, 11, 'setoran', 1000000.00, '2024-11-06 06:18:43', 'ST1275464'),
(91, 16, 'setoran', 100000.00, '2024-11-06 06:20:03', 'ST1108570'),
(92, 11, 'penarikan', 2000000.00, '2024-11-06 07:34:46', 'PN1267513'),
(93, 11, 'penarikan', 8000000.00, '2024-11-06 07:35:20', 'PN1260092'),
(94, 7, 'penarikan', 25000.00, '2024-11-06 07:36:08', 'PN1201811'),
(95, 24, 'setoran', 1000000.00, '2024-11-08 07:29:39', 'ST1298812'),
(96, 24, 'penarikan', 500000.00, '2024-11-08 07:29:53', 'PN1266296'),
(97, 6, 'setoran', 100000.00, '2024-11-08 07:40:04', 'ST1106343'),
(98, 6, 'penarikan', 2000000.00, '2024-11-08 07:40:30', 'PN1165669'),
(99, 9, 'setoran', 20000.00, '2024-11-11 07:10:01', 'ST1252060'),
(100, 9, 'penarikan', 15000.00, '2024-11-11 07:10:18', 'PN1259044'),
(101, 10, 'setoran', 10000.00, '2024-11-11 14:45:15', 'ST1214965'),
(102, 10, 'penarikan', 10000.00, '2024-11-11 14:45:31', 'PN1275216'),
(103, 7, 'setoran', 5000.00, '2024-11-20 04:40:20', 'ST1265692'),
(104, 7, 'penarikan', 5000.00, '2024-11-20 04:40:37', 'PN1217213');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(250) NOT NULL,
  `password` varchar(250) NOT NULL,
  `role` enum('admin','siswa') NOT NULL,
  `id_siswa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `role`, `id_siswa`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', NULL),
(13, 'intan', 'b1098cab9c2db3eb9f576eb66c33449c', 'siswa', 7),
(19, 'alma', 'ebbc3c26a34b609dc46f5c3378f96e08', 'siswa', 23),
(20, 'salsa', '0143c1e8e97da861c623ff508a441c54', 'siswa', 24),
(21, 'citra', 'e260eab6a7c45d139631f72b55d8506b', 'siswa', 6),
(22, 'izzah', '39a4daec4efa9cd25a9915331e3f7b00', 'siswa', 9),
(23, 'fivel', '6476af5c668a79e4af74e2f16432329f', 'siswa', 11);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`kelas`,`jurusan`);

--
-- Indeks untuk tabel `siswa`
--
ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id_siswa`);

--
-- Indeks untuk tabel `transaksi_tabungan`
--
ALTER TABLE `transaksi_tabungan`
  ADD PRIMARY KEY (`id_transaksi`),
  ADD KEY `id_siswa` (`id_siswa`);

--
-- Indeks untuk tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_user_siswa` (`id_siswa`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `siswa`
--
ALTER TABLE `siswa`
  MODIFY `id_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `transaksi_tabungan`
--
ALTER TABLE `transaksi_tabungan`
  MODIFY `id_transaksi` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT untuk tabel `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transaksi_tabungan`
--
ALTER TABLE `transaksi_tabungan`
  ADD CONSTRAINT `fk_transaksi_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaksi_tabungan_ibfk_1` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `fk_user_siswa` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_siswa_new` FOREIGN KEY (`id_siswa`) REFERENCES `siswa` (`id_siswa`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
