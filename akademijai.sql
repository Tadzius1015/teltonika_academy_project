-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2019 at 04:19 PM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 5.6.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `akademijai`
--

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `area` bigint(20) NOT NULL,
  `inhabitants_count` bigint(20) NOT NULL,
  `postal_code` int(8) NOT NULL,
  `date_created` date NOT NULL,
  `fk_countryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cities`
--

INSERT INTO `cities` (`id`, `name`, `area`, `inhabitants_count`, `postal_code`, `date_created`, `fk_countryId`) VALUES
(6, 'Postdam', 4511, 2122, 12345, '2019-06-27', 2),
(9, 'FSDA', 4545, 45454, 12214, '2019-06-29', 2),
(10, 'HHG', 44, 55, 45541, '2019-06-29', 2),
(11, 'BBV', 78, 78, 45268, '2019-06-27', 2),
(12, 'NG', 45, 12, 12222, '2019-06-27', 2),
(14, 'Essen', 300, 500000, 12345, '2019-06-27', 2),
(15, 'Test', 1234, 1234, 1234, '2019-06-27', 2),
(17, 'TRDSF', 4552, 1223, 1444, '2019-06-27', 2),
(19, 'SDAD', 45, 45, 45554, '2019-06-27', 11),
(20, 'VCV', 45, 12, 36666, '2019-06-27', 2),
(22, 'lo', 44, 44, 45122, '2019-06-27', 2),
(23, 'dsdsdvvvvv', 4454, 1112, 44511, '2019-06-28', 2),
(24, 'ddd', 44, 44, 44444, '2019-06-30', 2),
(25, 'ddd', 44, 44, 44444, '2019-06-29', 2),
(26, 'dddc', 4412, 225, 245, '2019-06-28', 2),
(27, 'pa', 45, 45, 45, '2019-06-27', 2),
(28, 'rr', 44, 451, 255, '2019-06-27', 2),
(29, 'ss', 12, 20, 20, '2019-06-30', 2),
(30, 'SD', 12, 55, 51, '2019-06-30', 2),
(31, 'TT', 12, 12, 12, '2019-06-30', 2),
(32, 'FFF', 454, 1221, 22221, '2019-06-30', 2),
(33, 'Siaip', 1, 45, 45, '2019-06-30', 18),
(34, 'Siaip', 1, 45, 45, '2019-06-30', 18),
(42, 'Testt', 12345, 12345, 12345, '2019-06-30', 12),
(43, 'dasdasd', 444, 4444, 4444, '2019-06-30', 2);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `area` bigint(20) NOT NULL,
  `inhabitants_count` bigint(20) NOT NULL,
  `phone_code` varchar(255) NOT NULL,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `name`, `area`, `inhabitants_count`, `phone_code`, `date_created`) VALUES
(2, 'Germany', 3573861, 83000000, '49', '2019-06-27'),
(4, 'Tadass', 454, 122, '89', '2019-06-29'),
(6, 'VVV', 4541, 12122, '25', '2019-06-28'),
(8, 'TRR', 45123, 44446, '74', '2019-06-28'),
(9, 'DDDRA', 4541, 4554, '894', '2019-06-28'),
(11, 'FFFCZASA', 4554, 1111, '851', '2019-06-28'),
(12, 'JJJGDA', 451, 555, '5555', '2019-06-30'),
(18, 'fasdsdkaj', 44, 44, '955', '2019-06-28'),
(20, 'WW', 45, 454, '541', '2019-06-29'),
(21, 'fff-', 445, 555, '45', '2019-06-30'),
(23, 'Testavimui', 4512, 4444, '12235', '2019-06-29'),
(26, 'dsd', 41, 1111, '11145', '2019-06-27'),
(27, 'ff', 41, 1111, '1111', '2019-06-28'),
(28, 'sadasc', 45, 0, '1', '2019-06-27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_country` (`fk_countryId`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cities`
--
ALTER TABLE `cities`
  ADD CONSTRAINT `fk_country` FOREIGN KEY (`fk_countryId`) REFERENCES `countries` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
