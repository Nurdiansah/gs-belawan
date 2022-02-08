-- phpMyAdmin SQL Dump
-- version 5.1.1deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 08, 2022 at 10:17 AM
-- Server version: 10.5.12-MariaDB-1
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
-- Table structure for table `bkm`
--

CREATE TABLE `bkm` (
  `id_bkm` int(11) NOT NULL,
  `nomor` varchar(3) DEFAULT NULL,
  `no_bkm` varchar(50) DEFAULT NULL,
  `tgl_bkm` datetime DEFAULT NULL,
  `id_divisi` int(11) NOT NULL,
  `id_anggaran` int(11) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `nominal` int(11) NOT NULL,
  `nilai_ppn` int(11) DEFAULT NULL,
  `grand_total` bigint(20) NOT NULL,
  `doc_bkm` text DEFAULT NULL,
  `waktu_dibuat_bkm` datetime DEFAULT NULL,
  `v_kasir` datetime DEFAULT NULL,
  `komentar_kasir` text DEFAULT NULL,
  `app_mgr_fin` datetime DEFAULT NULL,
  `komentar_mgr_fin` text DEFAULT NULL,
  `app_direktur` datetime DEFAULT NULL,
  `komentar_direktur` text DEFAULT NULL,
  `status_bkm` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bkm`
--

INSERT INTO `bkm` (`id_bkm`, `nomor`, `no_bkm`, `tgl_bkm`, `id_divisi`, `id_anggaran`, `keterangan`, `nominal`, `nilai_ppn`, `grand_total`, `doc_bkm`, `waktu_dibuat_bkm`, `v_kasir`, `komentar_kasir`, `app_mgr_fin`, `komentar_mgr_fin`, `app_direktur`, `komentar_direktur`, `status_bkm`) VALUES
(2, '002', '002/GS-GM/I/2022', '2022-01-24 15:10:12', 9, 694, 'nyoba pendapatan BOSSS', 890000000, NULL, 890000000, '1643011812-bkm.pdf', '2022-01-24 15:10:12', '2022-02-08 10:14:26', NULL, '2022-01-26 13:51:02', NULL, '2022-01-26 16:06:42', NULL, 5),
(4, '001', '001/GS-GM/II/2022', '2022-02-04 08:53:00', 3, 694, 'nyoba siii yaelahhh', 987654321, NULL, 987654321, '1643939580-bkm.pdf', '2022-02-04 08:53:00', '2022-02-08 10:15:47', NULL, '2022-02-07 11:22:28', NULL, '2022-02-07 14:38:39', NULL, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bkm`
--
ALTER TABLE `bkm`
  ADD PRIMARY KEY (`id_bkm`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bkm`
--
ALTER TABLE `bkm`
  MODIFY `id_bkm` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
