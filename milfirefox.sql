-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 26, 2023 at 02:01 PM
-- Server version: 8.0.34-0ubuntu0.20.04.1
-- PHP Version: 8.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `milfirefox`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int NOT NULL,
  `first_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `gender` enum('Male','Female','Other') COLLATE utf8mb4_general_ci NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `first_name`, `last_name`, `email`, `gender`, `date_of_birth`, `phone`, `password`, `created_at`) VALUES
(0, 'Bojan', 'Knežević', 'knezboki.19@gmail.com', 'Male', '2000-10-19', '0638795332', '$2y$10$5XrvRDqmMOk/XCBK47cO2u7XtGxW.Ji/vobSPHwQ9Ajm6dAeMj7Oy', '2023-08-04 13:42:00'),
(14, 'Bojan', 'Knežević', 'bkmontages2000@gmail.com', 'Male', '2000-10-20', '0638795332', '$2y$10$1GSavM/YC0FxZxsEgVCgju095yX0GIRAO.U/0h21fnB96XX2gJ3SC', '2023-08-12 19:39:40'),
(49, 'Bojan', 'Knežević', 'bksmurf19@gmail.com', 'Male', '2000-10-19', '0638795332', '$2y$10$Z8IeD0A3ZdMCl28F5JhdceknFCZLNxWjo4c7wA2t/F3LYsaKNdECG', '2023-08-25 08:40:08'),
(50, 'Bojan', 'Knežević', 'maza@gmail.com', 'Female', '2004-06-23', '0638795332', '$2y$10$bpCv2rhkCcpU///ZiK2fkesI5GJqjMn2bjYznpR4nqehaNh9vjTLO', '2023-08-25 09:32:20'),
(53, 'Bojan', 'Knežević', 'testworker@test.com', 'Male', '2021-11-29', '0638795332', '$2y$10$yzKs8vJxgy1o/hbbuuZ3Fu8HKHyWhehE4e7FEO3U0sQDn.Z7BN7qe', '2023-08-25 11:19:57'),
(54, 'Bojan', 'Knežević', 'testuser@test.com', 'Female', '2023-12-30', '0638795332', '$2y$10$jsZq4abTLrEdKo5zRdF1SOpsDH2EGtjaldxxvDS5dY5myauvRYIku', '2023-08-25 11:21:08'),
(55, 'Bojan', 'Knežević', 'testworker2@test.mail', 'Male', '2023-12-31', '0638795332', '$2y$10$0e7IMrB7LGPw43FL2Azkl.sAJIzKQdJX..W.7MoRp8IcH.rqf34BK', '2023-08-25 11:28:27'),
(60, 'Boki', 'Knez', 'bktestmail@gmail.com', 'Male', '2000-06-08', '1', '$2y$10$R230mXCPaUIm3lVjlOlxgu.mdnZqurvGYTPELp1wod6PT/9jcGsBi', '2023-08-25 11:36:58'),
(61, 'ya', 'nigah', 'sadasd@aswfgw.gewhr', 'Other', '2000-06-14', '21413412412', '$2y$10$COlMQ/pGafYlI.bhIn9is.CpWuTfAteNlkZAuXwj77a047zFBz3qq', '2023-08-25 13:33:22'),
(62, 'Luca', 'Nunyabusmal', 'entertrashemailhere@gay.com', 'Male', '2005-04-13', '12345678910', '$2y$10$OQIvuQsZWEuQB5f57pIO2uqL5MrM75b8gYY54yHyHm6ZmjKh7KhkW', '2023-08-25 13:58:11'),
(63, 'Stefan', 'Berček', 'stefanbercek55@gmail.com', 'Female', '1990-02-08', '530', '$2y$10$cCBNHNXxmWhgHFkmD5JEI.ME5gAtlgKE2PaDYb2zNGUc51l21M09W', '2023-08-25 17:31:22');

-- --------------------------------------------------------

--
-- Table structure for table `email_requests`
--

CREATE TABLE `email_requests` (
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `last_request` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_requests`
--

INSERT INTO `email_requests` (`email`, `last_request`) VALUES
('bksmurf19@gmail.com', '2023-08-25 11:39:26'),
('bktest@gmail.com', '2023-08-25 11:38:09'),
('knezboki.19@gmail.com', '2023-08-25 11:52:29'),
('ludisrpskigejmplejevi@gmail.com', '2023-08-25 14:35:31'),
('stefanbercek55@gmail.com', '2023-08-25 17:32:26'),
('tests@bk.com', '2023-08-26 13:58:12');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `id` int NOT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `item_desc` varchar(500) DEFAULT NULL,
  `food_type` varchar(100) NOT NULL,
  `price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int NOT NULL,
  `account_id` int NOT NULL,
  `table_id` int DEFAULT NULL,
  `status` enum('arriving','seated','ordered','served','paid','cancelled') COLLATE utf8mb4_general_ci NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `reservation_end` time DEFAULT NULL,
  `reservation_code` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `worker_comment` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `account_id`, `table_id`, `status`, `reservation_date`, `reservation_time`, `reservation_end`, `reservation_code`, `worker_comment`) VALUES
(31, 14, 1, 'paid', '2023-08-22', '00:00:00', NULL, NULL, 'undefined'),
(55, 14, 18, 'paid', '2023-08-26', '13:00:00', '14:00:00', 'xIPsKW4MH8bkuYj3dsLo', 'TEST'),
(56, 14, 18, 'paid', '2023-08-26', '08:00:00', '09:00:00', 'XSX2rekBXrKVZDeeWYuy', 'TEST'),
(57, 14, 19, 'served', '2023-08-26', '09:00:00', '13:00:00', '26cVfWuuQ1ZrA9qA1vOo', 'TEST'),
(58, 14, 20, 'served', '2023-08-26', '09:00:00', '14:00:00', 'y4hCDSKutdqm6V9Yo4qr', ''),
(59, 63, 2, 'arriving', '6245-02-13', '09:00:00', '12:00:00', 'BAjdiZcitbmhsMYW1o6u', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int NOT NULL,
  `title` varchar(10) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `title`) VALUES
(1, 'admin'),
(2, 'worker'),
(3, 'user');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `table_id` int NOT NULL,
  `num_seats` int NOT NULL,
  `location` enum('Indoor','Outdoor','Balcony') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Indoor',
  `smoking` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`table_id`, `num_seats`, `location`, `smoking`) VALUES
(1, 222, 'Outdoor', 1),
(2, 2, 'Outdoor', 0),
(3, 2, 'Outdoor', 0),
(4, 2, 'Balcony', 1),
(5, 2, 'Balcony', 0),
(6, 4, 'Outdoor', 0),
(7, 4, 'Outdoor', 0),
(8, 4, 'Indoor', 0),
(9, 4, 'Balcony', 0),
(10, 4, 'Indoor', 0),
(11, 4, 'Indoor', 0),
(12, 4, 'Indoor', 0),
(13, 4, 'Indoor', 0),
(14, 4, 'Indoor', 0),
(15, 4, 'Indoor', 0),
(16, 4, 'Indoor', 0),
(17, 4, 'Indoor', 0),
(18, 8, 'Indoor', 0),
(19, 8, 'Indoor', 0),
(20, 8, 'Indoor', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `account_id` int NOT NULL,
  `role_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`account_id`, `role_id`) VALUES
(0, 1),
(14, 2),
(61, 2),
(49, 3),
(50, 3),
(60, 3),
(62, 3),
(63, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `email_requests`
--
ALTER TABLE `email_requests`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `menu_item`
--
ALTER TABLE `menu_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservations`
--
ALTER TABLE `reservations`
  ADD PRIMARY KEY (`reservation_id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`table_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD PRIMARY KEY (`account_id`,`role_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `table_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reservations`
--
ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`table_id`) REFERENCES `tables` (`table_id`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`account_id`) REFERENCES `accounts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
