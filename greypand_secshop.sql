-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 06, 2019 at 09:28 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `order_description` text CHARACTER SET utf8mb4 NOT NULL,
  `cust_address` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1: Pending, 2: Completed',
  `order_session` text CHARACTER SET utf8mb4 NOT NULL,
  `order_datetime` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(8) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `code` varchar(255) CHARACTER SET latin1 NOT NULL,
  `image` text CHARACTER SET latin1 NOT NULL,
  `price` double(10,2) NOT NULL,
  `quantity` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `code`, `image`, `price`, `quantity`) VALUES
(1, 'LENOVO Thinkpad x230', 'LPX230U', 'img3.png', 1500.00, 1),
(2, 'MacBook Air Pro 2019', 'MBP2019U', 'img3.png', 800.00, 1),
(3, 'HP Pavilion HQ-12XYZ', 'HPP12U', 'img3.png', 300.00, 1),
(4, 'XP 1155 Intel Core Laptop', 'LPN45', 'img3.png', 800.00, 1),
(5, 'Acer Aspire 1 A114-31', 'AA114', 'img3.png', 289.00, 1),
(6, 'Dell XPS 15 9570', 'DX9570', 'img3.png', 3199.00, 1),
(7, 'Asus X540UB-DM192T', 'AX540D', 'img3.png', 599.00, 1),
(8, 'HP Envy x360 13-ag0001nv', 'HPEX13', 'img3.png', 649.00, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_code` (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
