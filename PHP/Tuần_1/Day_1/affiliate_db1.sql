-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 07, 2025 at 06:37 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `affiliate_db1`
--

-- --------------------------------------------------------

--
-- Table structure for table `chiendich`
--

CREATE TABLE `chiendich` (
  `id` int NOT NULL,
  `ten_chien_dich` varchar(255) NOT NULL,
  `gia_san_pham` float NOT NULL,
  `so_luong_don` int NOT NULL,
  `loai_san_pham` varchar(255) NOT NULL,
  `trang_thai` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chiendich`
--

INSERT INTO `chiendich` (`id`, `ten_chien_dich`, `gia_san_pham`, `so_luong_don`, `loai_san_pham`, `trang_thai`) VALUES
(1, 'Spring Sale 2025', 99.99, 150, 'Thời trang', 1);

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int NOT NULL,
  `id_chien_dich` int NOT NULL,
  `ma_don` varchar(50) NOT NULL,
  `gia_tri` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `don_hang`
--

INSERT INTO `don_hang` (`id`, `id_chien_dich`, `ma_don`, `gia_tri`) VALUES
(1, 1, 'ID001', 99.99),
(2, 1, 'ID002', 49.99),
(3, 1, 'ID003', 149.99),
(4, 1, 'ID004', 89.99),
(5, 1, 'ID005', 129.99);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chiendich`
--
ALTER TABLE `chiendich`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chiendich`
--
ALTER TABLE `chiendich`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
