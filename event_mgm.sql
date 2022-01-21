-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 21, 2022 at 11:02 PM
-- Server version: 10.4.18-MariaDB
-- PHP Version: 7.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `event_mgm`
--
CREATE DATABASE IF NOT EXISTS `event_mgm` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `event_mgm`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_employee`
--

DROP TABLE IF EXISTS `tbl_employee`;
CREATE TABLE `tbl_employee` (
  `id` int(11) NOT NULL,
  `emp_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_email` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `emp_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1:Active,2:Inactive,5:Deleted',
  `emp_sdt` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'record added datetime',
  `emp_udt` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'record update datetime',
  `emp_edt` datetime DEFAULT NULL COMMENT 'record delete datetime'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='table to manage employee info';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event`
--

DROP TABLE IF EXISTS `tbl_event`;
CREATE TABLE `tbl_event` (
  `id` int(11) NOT NULL,
  `ev_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'event title',
  `ev_fee` double(7,3) NOT NULL DEFAULT 0.000 COMMENT 'event participation fee',
  `ev_date` date NOT NULL COMMENT 'event date must be in mysql format',
  `ev_status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1:Active,2:Inactive,5:Deleted',
  `ev_sdt` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'record added datetime',
  `ev_udt` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'record updated datetime',
  `ev_edt` datetime DEFAULT NULL COMMENT 'record deleted datetime'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='table to manage event basic details';

-- --------------------------------------------------------

--
-- Table structure for table `tbl_participant`
--

DROP TABLE IF EXISTS `tbl_participant`;
CREATE TABLE `tbl_participant` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `emp_id` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1:Active,2:Inactive,5:Deleted',
  `sdt` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'record added datetime',
  `udt` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'record updated datetime',
  `edt` datetime DEFAULT NULL COMMENT 'record deleted datetime'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='table to manage event particpants';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_email` (`emp_email`),
  ADD KEY `emp_status` (`emp_status`);

--
-- Indexes for table `tbl_event`
--
ALTER TABLE `tbl_event`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ev_date` (`ev_date`),
  ADD KEY `ev_status` (`ev_status`);

--
-- Indexes for table `tbl_participant`
--
ALTER TABLE `tbl_participant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `participant_id` (`emp_id`),
  ADD KEY `status` (`status`),
  ADD KEY `emp_id` (`emp_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_employee`
--
ALTER TABLE `tbl_employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_event`
--
ALTER TABLE `tbl_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_participant`
--
ALTER TABLE `tbl_participant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
