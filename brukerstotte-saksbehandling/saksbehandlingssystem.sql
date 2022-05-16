-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 15, 2022 at 05:20 PM
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
-- Table structure for table `cases`
--

CREATE TABLE `cases` (
  `id` int(11) NOT NULL,
  `type` varchar(3) NOT NULL,
  `impact` int(1) NOT NULL,
  `urgency` int(1) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(16) NOT NULL,
  `page` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0,
  `registered` datetime NOT NULL,
  `started` datetime DEFAULT NULL,
  `finished` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `cases`
--

INSERT INTO `cases` (`id`, `type`, `impact`, `urgency`, `description`, `category`, `page`, `status`, `registered`, `started`, `finished`) VALUES
(5, 'CHG', 1, 2, 'If there are more than zero rows returned, the function fetch_assoc() puts all the results into an associative array that we can loop through. The while() loop loops through the result set and outputs the data from the id, firstname and lastname columns.\r\n\r\nThe following example shows the same as the example above, in the MySQLi procedural way:', 'Rettigheter', 'https://www.w3schools.com/php/php_mysql_select.asp', 2, '2022-05-15 17:11:04', '2022-05-15 17:17:03', '2022-05-15 17:17:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cases`
--
ALTER TABLE `cases`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cases`
--
ALTER TABLE `cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
