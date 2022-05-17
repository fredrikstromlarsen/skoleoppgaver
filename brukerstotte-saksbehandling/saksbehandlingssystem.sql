-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 17, 2022 at 11:15 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `saksbehandlingssystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` int(11) NOT NULL,
  `type` varchar(3) NOT NULL,
  `impact` int(1) NOT NULL,
  `urgency` int(1) NOT NULL,
  `description` text NOT NULL,
  `category` text NOT NULL,
  `page` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `registered` datetime NOT NULL,
  `started` datetime DEFAULT NULL,
  `finished` datetime DEFAULT NULL,
  `email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `type`, `impact`, `urgency`, `description`, `category`, `page`, `status`, `registered`, `started`, `finished`, `email`) VALUES
(21, 'INC', 3, 2, '', '', '', 2, '2022-05-17 22:41:17', '2022-05-17 22:52:48', '2022-05-17 23:10:08', ''),
(22, 'INC', 3, 2, '', '', '', 2, '2022-05-17 22:47:37', '2022-05-17 22:53:00', '2022-05-17 23:10:09', ''),
(23, 'INC', 3, 2, '', '', '', 1, '2022-05-17 22:54:41', '2022-05-17 23:09:57', NULL, ''),
(24, 'INC', 2, 1, '', '', '', 2, '2022-05-17 22:55:49', '2022-05-17 23:09:58', '2022-05-17 23:11:54', ''),
(25, 'INC', 3, 1, '', '', '', 2, '2022-05-17 23:02:57', '2022-05-17 23:12:03', '2022-05-17 23:13:01', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
