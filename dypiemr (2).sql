-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 16, 2025 at 08:31 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dypiemr`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(4, 'raj', '$2y$10$ZHJRsYwg7vAEBXehCMex/O7k4U8O0Cp0rMFsC0l.Dr3gHDIwLkFXe', '2025-09-29 08:13:31'),
(5, 'pavan', '$2y$10$dheBrL2pOsHyVb/8jg/v.O12hMdnSKp/uDxVn8G2SsJPjrZd07cMG', '2025-10-11 09:22:28');

-- --------------------------------------------------------

--
-- Table structure for table `prelim_results`
--

CREATE TABLE `prelim_results` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `dbms` int(11) NOT NULL,
  `cn` int(11) NOT NULL,
  `dc` int(11) NOT NULL,
  `mc` int(11) NOT NULL,
  `eft` int(11) NOT NULL,
  `overall_score` int(11) GENERATED ALWAYS AS (`dbms` + `cn` + `dc` + `mc` + `eft`) STORED,
  `average` decimal(5,2) GENERATED ALWAYS AS ((`dbms` + `cn` + `dc` + `mc` + `eft`) / 5) STORED,
  `percentage` decimal(5,2) GENERATED ALWAYS AS ((`dbms` + `cn` + `dc` + `mc` + `eft`) / 350 * 100) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prelim_results`
--

INSERT INTO `prelim_results` (`id`, `name`, `roll_no`, `dbms`, `cn`, `dc`, `mc`, `eft`) VALUES
(1, 'Neha Verma', 'T2204', 65, 68, 55, 61, 59),
(2, 'Sanjay Yadav', 'T2205', 52, 45, 60, 58, 64),
(3, 'Anjali Reddy', 'T2206', 68, 62, 65, 67, 50);

--
-- Triggers `prelim_results`
--
DELIMITER $$
CREATE TRIGGER `check_prelim_marks_before_insert` BEFORE INSERT ON `prelim_results` FOR EACH ROW BEGIN
  IF (NEW.dbms < 0 OR NEW.dbms > 70
      OR NEW.cn < 0 OR NEW.cn > 70
      OR NEW.dc < 0 OR NEW.dc > 70
      OR NEW.mc < 0 OR NEW.mc > 70
      OR NEW.eft < 0 OR NEW.eft > 70) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: Prelim test marks must be between 0 and 70';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_prelim_marks_before_update` BEFORE UPDATE ON `prelim_results` FOR EACH ROW BEGIN
  IF (NEW.dbms < 0 OR NEW.dbms > 70
      OR NEW.cn < 0 OR NEW.cn > 70
      OR NEW.dc < 0 OR NEW.dc > 70
      OR NEW.mc < 0 OR NEW.mc > 70
      OR NEW.eft < 0 OR NEW.eft > 70) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: Prelim test marks must be between 0 and 70';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `regular_results`
--

CREATE TABLE `regular_results` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `dbms` int(11) NOT NULL,
  `cn` int(11) NOT NULL,
  `dc` int(11) NOT NULL,
  `mc` int(11) NOT NULL,
  `eft` int(11) NOT NULL,
  `overall_score` int(11) GENERATED ALWAYS AS (`dbms` + `cn` + `dc` + `mc` + `eft`) STORED,
  `average` decimal(5,2) GENERATED ALWAYS AS ((`dbms` + `cn` + `dc` + `mc` + `eft`) / 5) STORED,
  `percentage` decimal(5,2) GENERATED ALWAYS AS ((`dbms` + `cn` + `dc` + `mc` + `eft`) / 500 * 100) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `regular_results`
--

INSERT INTO `regular_results` (`id`, `name`, `roll_no`, `dbms`, `cn`, `dc`, `mc`, `eft`) VALUES
(1, 'Vikram Joshi', 'T2207', 90, 85, 78, 92, 88),
(2, 'Shreya Patil', 'T2208', 88, 95, 91, 79, 87),
(3, 'Rohan Gupta', 'T2209', 75, 80, 83, 70, 78);

--
-- Triggers `regular_results`
--
DELIMITER $$
CREATE TRIGGER `check_regular_marks_before_insert` BEFORE INSERT ON `regular_results` FOR EACH ROW BEGIN
  IF (NEW.dbms < 0 OR NEW.dbms > 100
      OR NEW.cn < 0 OR NEW.cn > 100
      OR NEW.dc < 0 OR NEW.dc > 100
      OR NEW.mc < 0 OR NEW.mc > 100
      OR NEW.eft < 0 OR NEW.eft > 100) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: Regular test marks must be between 0 and 100';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_regular_marks_before_update` BEFORE UPDATE ON `regular_results` FOR EACH ROW BEGIN
  IF (NEW.dbms < 0 OR NEW.dbms > 100
      OR NEW.cn < 0 OR NEW.cn > 100
      OR NEW.dc < 0 OR NEW.dc > 100
      OR NEW.mc < 0 OR NEW.mc > 100
      OR NEW.eft < 0 OR NEW.eft > 100) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: Regular test marks must be between 0 and 100';
  END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `username`, `password`, `created_at`) VALUES
(4, 'pravin', '$2y$10$wNiI2pQF0WEDXro.0BblZu7YHt0k6jR0FZrFhFb/SfJI7hN4xogsK', '2025-09-29 08:27:10'),
(5, 'raj', '$2y$10$IzvR3OQsIBipbEKJh/HwqebpkoH1hLAmikkyQWq8.EXH/2/yanI6y', '2025-09-29 08:29:37'),
(6, 'pavan', '$2y$10$cAPZbfprdXZZOhKVPS1/5O2AHY7owe2HXJFyfyZ6yORTXR2XSYR8q', '2025-10-11 08:32:53');

-- --------------------------------------------------------

--
-- Table structure for table `unit_results`
--

CREATE TABLE `unit_results` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `roll_no` varchar(50) NOT NULL,
  `dbms` int(11) NOT NULL,
  `cn` int(11) NOT NULL,
  `dc` int(11) NOT NULL,
  `mc` int(11) NOT NULL,
  `eft` int(11) NOT NULL,
  `overall_score` int(11) GENERATED ALWAYS AS (`dbms` + `cn` + `dc` + `mc` + `eft`) STORED,
  `average` decimal(5,2) GENERATED ALWAYS AS ((`dbms` + `cn` + `dc` + `mc` + `eft`) / 5) STORED,
  `percentage` decimal(5,2) GENERATED ALWAYS AS ((`dbms` + `cn` + `dc` + `mc` + `eft`) / 150 * 100) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit_results`
--

INSERT INTO `unit_results` (`id`, `name`, `roll_no`, `dbms`, `cn`, `dc`, `mc`, `eft`) VALUES
(3, 'Amit Kumar', 'T2203', 18, 20, 24, 15, 21),
(5, 'pavan', '27', 20, 30, 25, 26, 28),
(6, 'prvi', '269', 23, 22, 23, 22, 22),
(7, 'Vinay', '72', 29, 22, 23, 27, 27);

--
-- Triggers `unit_results`
--
DELIMITER $$
CREATE TRIGGER `check_unit_marks_before_insert` BEFORE INSERT ON `unit_results` FOR EACH ROW BEGIN
  IF (NEW.dbms < 0 OR NEW.dbms > 30
      OR NEW.cn < 0 OR NEW.cn > 30
      OR NEW.dc < 0 OR NEW.dc > 30
      OR NEW.mc < 0 OR NEW.mc > 30
      OR NEW.eft < 0 OR NEW.eft > 30) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: Unit test marks must be between 0 and 30';
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `check_unit_marks_before_update` BEFORE UPDATE ON `unit_results` FOR EACH ROW BEGIN
  IF (NEW.dbms < 0 OR NEW.dbms > 30
      OR NEW.cn < 0 OR NEW.cn > 30
      OR NEW.dc < 0 OR NEW.dc > 30
      OR NEW.mc < 0 OR NEW.mc > 30
      OR NEW.eft < 0 OR NEW.eft > 30) THEN
    SIGNAL SQLSTATE '45000'
      SET MESSAGE_TEXT = 'Error: Unit test marks must be between 0 and 30';
  END IF;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `prelim_results`
--
ALTER TABLE `prelim_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regular_results`
--
ALTER TABLE `regular_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `unit_results`
--
ALTER TABLE `unit_results`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `prelim_results`
--
ALTER TABLE `prelim_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `regular_results`
--
ALTER TABLE `regular_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `unit_results`
--
ALTER TABLE `unit_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
