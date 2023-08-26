-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 25, 2023 at 11:05 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `restaurant_booking`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `first_name`, `last_name`, `email`, `gender`, `date_of_birth`, `phone`, `password`, `created_at`) VALUES
(0, 'Bojan', 'Knežević', 'knezboki.19@gmail.com', 'Male', '2000-10-19', '0638795332', '$2y$10$fN1Wu2XWq/Pa39Ox/00tNuvG3EwDZf8bBccXD1QvG1b97ieEA5uu6', '2023-08-04 13:42:00'),
(14, 'Bojan', 'Knežević', 'bkmontages2000@gmail.com', 'Male', '2000-10-20', '0638795332', '$2y$10$1GSavM/YC0FxZxsEgVCgju095yX0GIRAO.U/0h21fnB96XX2gJ3SC', '2023-08-12 19:39:40'),
(49, 'Bojan', 'Knežević', 'bksmurf19@gmail.com', 'Male', '2000-10-19', '0638795332', '$2y$10$sPn4brh.UWjfnkWsaaFQEuPpLktVSU24gJV4b1hwOC586yLhQswI2', '2023-08-25 08:40:08');

--
-- Triggers `accounts`
--
DELIMITER $$
CREATE TRIGGER `after_accounts_insert` AFTER INSERT ON `accounts` FOR EACH ROW BEGIN
  DECLARE role_id INT DEFAULT 3;

  INSERT INTO user_role (account_id, role_id) VALUES (NEW.id, role_id);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `email_requests`
--

CREATE TABLE `email_requests` (
  `email` varchar(255) NOT NULL,
  `last_request` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_requests`
--

INSERT INTO `email_requests` (`email`, `last_request`) VALUES
('bksmurf19@gmail.com', '2023-08-25 10:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `location_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `location_name`) VALUES
(1, 'SUBOTICA'),
(2, 'NOVI KNEZEVAC');

-- --------------------------------------------------------

--
-- Table structure for table `menu_item`
--

CREATE TABLE `menu_item` (
  `id` int(11) NOT NULL,
  `item_name` varchar(200) DEFAULT NULL,
  `item_desc` varchar(500) DEFAULT NULL,
  `food_type` varchar(100) NOT NULL,
  `price` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE `reservations` (
  `reservation_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `status` enum('arriving','seated','ordered','served','paid','cancelled') NOT NULL,
  `reservation_date` date NOT NULL,
  `reservation_time` time NOT NULL,
  `reservation_end` time DEFAULT NULL,
  `reservation_code` varchar(20) DEFAULT NULL,
  `worker_comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`reservation_id`, `account_id`, `table_id`, `status`, `reservation_date`, `reservation_time`, `reservation_end`, `reservation_code`, `worker_comment`) VALUES
(31, 14, 1, 'paid', '2023-08-22', '00:00:00', NULL, NULL, 'undefined');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(11) NOT NULL,
  `title` varchar(10) NOT NULL
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
  `table_id` int(11) NOT NULL,
  `num_seats` int(11) NOT NULL,
  `location` enum('Indoor','Outdoor','Balcony') NOT NULL DEFAULT 'Indoor',
  `smoking` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`table_id`, `num_seats`, `location`, `smoking`) VALUES
(1, 2, 'Indoor', 0),
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
  `account_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`account_id`, `role_id`) VALUES
(0, 1),
(14, 2),
(49, 3);

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
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `menu_item`
--
ALTER TABLE `menu_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reservations`
--
ALTER TABLE `reservations`
  MODIFY `reservation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `table_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

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
