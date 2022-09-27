-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 14, 2022 at 05:25 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gs_belawan`
--

-- --------------------------------------------------------

--
-- Table structure for table `verifikasi_pajak`
--

CREATE TABLE `verifikasi_pajak` (
  `id_verifikasi` int(11) NOT NULL,
  `jenispengajuan_id` tinyint(4) NOT NULL,
  `permohonan_id` varchar(20) NOT NULL,
  `nilai_barang` decimal(12,2) NOT NULL DEFAULT 0.00,
  `nilai_jasa` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_harga` decimal(12,2) NOT NULL DEFAULT 0.00,
  `nilai_dpp` decimal(12,2) NOT NULL DEFAULT 0.00,
  `ppn_nilai` decimal(10,2) NOT NULL DEFAULT 0.00,
  `id_pph` tinyint(4) NOT NULL,
  `pph_persen` tinyint(4) NOT NULL DEFAULT 0,
  `pph_nilai` decimal(10,2) NOT NULL DEFAULT 0.00,
  `biaya_lain` decimal(10,2) NOT NULL DEFAULT 0.00,
  `potongan` decimal(10,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `with_ppn` tinyint(1) DEFAULT NULL,
  `ppn_of` varchar(10) DEFAULT NULL,
  `rounding` varchar(10) DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `updated_by` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `verifikasi_pajak`
--
ALTER TABLE `verifikasi_pajak`
  ADD PRIMARY KEY (`id_verifikasi`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `verifikasi_pajak`
--
ALTER TABLE `verifikasi_pajak`
  MODIFY `id_verifikasi` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
