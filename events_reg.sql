-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 24, 2021 at 08:48 PM
-- Server version: 5.7.32-35-log
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kyokus97_db2`
--

-- --------------------------------------------------------

--
-- Table structure for table `events_reg`
--

CREATE TABLE `events_reg` (
  `id` int(4) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `username` varchar(30) DEFAULT NULL,
  `birth_date` varchar(20) DEFAULT NULL,
  `email` varchar(40) DEFAULT NULL,
  `grade` varchar(10) DEFAULT NULL,
  `cell` varchar(20) DEFAULT NULL,
  `club` int(11) DEFAULT NULL,
  `accepted` int(11) NOT NULL DEFAULT '0',
  `instructor` int(11) NOT NULL DEFAULT '0',
  `event_id` int(11) DEFAULT NULL,
  `accomodation` varchar(255) DEFAULT NULL,
  `extraInfo` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `eventreg_events_reg`
--
ALTER TABLE `eventreg_events_reg`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `eventreg_events_reg`
--
ALTER TABLE `eventreg_events_reg`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
