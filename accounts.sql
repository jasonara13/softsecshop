-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 07, 2019 at 11:36 AM
-- Server version: 10.3.15-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `greypand_secshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `username`, `password`, `email`) VALUES
(1, 'root', '$2y$10$iGzwzih9pMNBoYJ/HZq9penZ3JlwNsaowQgDQGlzkf58zcEGc5xNW', 'root@secshop.gr'),
(2, 'gbk', '$2y$10$k5ms5yxKerYAlQPBnZE2D.M7M5NUCQTykP6uDtKWJJ6yj09DfWM.G', 'demo@demo.demo'),
(3, 'mixalis', '$2y$10$k5ms5yxKerYAlQPBnZE2D.M7M5NUCQTykP6uDtKWJJ6yj09DfWM.G', 'demo@demo.demo'),
(4, 'kostas', '$2y$10$k5ms5yxKerYAlQPBnZE2D.M7M5NUCQTykP6uDtKWJJ6yj09DfWM.G', 'demo@demo.demo'),
(5, 'demo_user', '$2y$10$Ah0VDOqwvXQcf2G.9FvHx.IaJJllMuBfptx0dodrzVlXCGFcbL2r6', 'demo@software_security.course'),
(6, 'demo_victim', '$2y$10$xZ9OSzMrmF5vsH.TwsfW6O2Yv0v.BLUsTXTfBv8yAfR01r/Mkfpku', 'victim@software_security.course');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
