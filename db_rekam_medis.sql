-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 24, 2026 at 07:16 AM
-- Server version: 10.11.11-MariaDB
-- PHP Version: 8.2.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_rekam_medis`
--

-- --------------------------------------------------------

--
-- Table structure for table `dokter`
--

CREATE TABLE `dokter` (
  `id_dokter` int(11) NOT NULL,
  `nama_dokter` varchar(100) NOT NULL,
  `spesialisasi` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `dokter`
--

INSERT INTO `dokter` (`id_dokter`, `nama_dokter`, `spesialisasi`) VALUES
(1, 'dr. Heniq kusuma', 'Umum'),
(2, 'dr. Andi', 'Gigi'),
(4, 'dr. Susi Handayani', 'Anak'),
(5, 'dr. Azamudin', 'Umum');

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `id_obat` int(11) NOT NULL,
  `nama_obat` varchar(100) NOT NULL,
  `satuan` varchar(30) NOT NULL,
  `stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`id_obat`, `nama_obat`, `satuan`, `stok`) VALUES
(1, 'Paracetamol', 'Sirup', 250),
(2, 'Promag', 'Tablet', 210),
(3, 'Betadine', 'Cair', 123),
(5, 'Polysilane Junior', 'Sirup', 120);

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `id_pasien` int(11) NOT NULL,
  `no_rm` varchar(20) NOT NULL,
  `nama_pasien` varchar(100) NOT NULL,
  `jenis_kelamin` enum('L','P') NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `alamat` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`id_pasien`, `no_rm`, `nama_pasien`, `jenis_kelamin`, `tanggal_lahir`, `alamat`) VALUES
(1, '001', 'Kusnul', 'P', '1997-12-03', 'desa temandang'),
(2, '002', 'Rajiman', 'L', '1980-01-21', 'Ds. Kasiman Kec.Kerek Kab.Tuban'),
(3, '003', 'Ananda Zamrud', 'L', '2017-03-04', 'Kel. Latsari Kec. Tuban Kab. Tuban'),
(4, '004', 'Surya Aminin', 'L', '2002-02-03', 'Ds. Temandang Kec. Merakurak Kab.Tuban');

-- --------------------------------------------------------

--
-- Table structure for table `rekam_medis`
--

CREATE TABLE `rekam_medis` (
  `id_rekam` int(11) NOT NULL,
  `id_pasien` int(11) NOT NULL,
  `id_dokter` int(11) NOT NULL,
  `id_obat` int(11) NOT NULL,
  `tanggal_periksa` date NOT NULL,
  `keluhan` text NOT NULL,
  `diagnosa` text NOT NULL,
  `tindakan` text NOT NULL,
  `resep` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekam_medis`
--

INSERT INTO `rekam_medis` (`id_rekam`, `id_pasien`, `id_dokter`, `id_obat`, `tanggal_periksa`, `keluhan`, `diagnosa`, `tindakan`, `resep`) VALUES
(2, 3, 4, 5, '2026-11-17', 'Sakit Perut', 'Diare', 'Pemeriksaan fisik abdomen', 'Polysilane 3 x 1 '),
(3, 2, 1, 3, '2026-11-17', 'Terkena Clurit', 'Luka Terbuka', 'Penanganan Luka', 'Ganti Perban  1-3  hari sekali');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dokter`
--
ALTER TABLE `dokter`
  ADD PRIMARY KEY (`id_dokter`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`id_obat`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`id_pasien`);

--
-- Indexes for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD PRIMARY KEY (`id_rekam`),
  ADD KEY `fk_rekam_pasien` (`id_pasien`),
  ADD KEY `fk_rekam_dokter` (`id_dokter`),
  ADD KEY `fk_rekam_obat` (`id_obat`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dokter`
--
ALTER TABLE `dokter`
  MODIFY `id_dokter` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `obat`
--
ALTER TABLE `obat`
  MODIFY `id_obat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pasien`
--
ALTER TABLE `pasien`
  MODIFY `id_pasien` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  MODIFY `id_rekam` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rekam_medis`
--
ALTER TABLE `rekam_medis`
  ADD CONSTRAINT `fk_rekam_dokter` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`),
  ADD CONSTRAINT `fk_rekam_obat` FOREIGN KEY (`id_obat`) REFERENCES `obat` (`id_obat`),
  ADD CONSTRAINT `fk_rekam_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
