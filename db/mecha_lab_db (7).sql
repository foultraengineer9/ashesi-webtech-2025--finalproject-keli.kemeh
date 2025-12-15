-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 15, 2025 at 11:25 AM
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
-- Database: `mecha_lab_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Microcontroller'),
(2, 'Sensor'),
(3, 'Motor'),
(4, 'Microcontrollers'),
(5, 'Sensors');

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `item_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `item_name` varchar(100) NOT NULL,
  `serial_number` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Available','Borrowed','Broken','Lost') DEFAULT 'Available',
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory`
--

INSERT INTO `inventory` (`item_id`, `category_id`, `item_name`, `serial_number`, `description`, `status`, `image_url`) VALUES
(1, 1, 'Arduino Uno R3', 'ARD-001', 'Standard microcontroller board', 'Broken', NULL),
(2, 1, 'Arduino Uno R3', 'ARD-002', 'Standard microcontroller board', 'Borrowed', NULL),
(3, 2, 'DHT11 Temp Sensor', 'SNS-104', 'Temperature and Humidity sensor', 'Available', NULL),
(4, 2, 'Ultrasonic Sensor', 'SNS-202', 'HC-SR04 Distance sensor', 'Broken', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `status` enum('Pending','Approved','Borrowed','Returned','Rejected') NOT NULL DEFAULT 'Pending',
  `request_date` datetime NOT NULL DEFAULT current_timestamp(),
  `approve_date` datetime DEFAULT NULL,
  `due_date` datetime DEFAULT NULL,
  `borrow_date` datetime DEFAULT NULL,
  `return_date` datetime DEFAULT NULL,
  `status_at_return` enum('Good','Damaged') DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `renewals_count` int(11) DEFAULT 0,
  `max_renewals` int(11) DEFAULT 1,
  `desired_start_date` datetime DEFAULT NULL,
  `desired_end_date` datetime DEFAULT NULL,
  `checklist` text DEFAULT NULL,
  `renewal_requested` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `loans`
--

INSERT INTO `loans` (`loan_id`, `user_id`, `item_id`, `status`, `request_date`, `approve_date`, `due_date`, `borrow_date`, `return_date`, `status_at_return`, `notes`, `renewals_count`, `max_renewals`, `desired_start_date`, `desired_end_date`, `checklist`, `renewal_requested`) VALUES
(1, 4, 1, 'Borrowed', '2025-12-15 09:40:34', '2025-12-15 09:41:57', '2025-12-26 00:00:00', '2025-12-15 09:41:57', NULL, NULL, NULL, 1, 1, NULL, NULL, NULL, 0),
(2, 4, 3, 'Rejected', '2025-12-15 09:58:35', NULL, NULL, NULL, NULL, NULL, NULL, 0, 1, '2025-12-24 00:00:00', '2025-12-27 00:00:00', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_tickets`
--

CREATE TABLE `maintenance_tickets` (
  `ticket_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `opened_by` int(11) DEFAULT NULL,
  `status` enum('Open','InProgress','Closed') NOT NULL DEFAULT 'Open',
  `opened_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL,
  `title` varchar(150) NOT NULL,
  `details` text DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `maintenance_tickets`
--

INSERT INTO `maintenance_tickets` (`ticket_id`, `item_id`, `opened_by`, `status`, `opened_at`, `updated_at`, `title`, `details`, `photo_url`) VALUES
(1, 1, 4, 'Open', '2025-12-15 09:58:16', '2025-12-15 10:12:32', 'Issue reported by student', 'It doesn\\\'t work', NULL),
(2, 1, 4, 'Open', '2025-12-15 10:13:51', NULL, 'Issue reported by student', 'motor broken...Wires cut and worn out...', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Student') DEFAULT 'Student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `strikes` int(11) DEFAULT 0,
  `fines` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fname`, `lname`, `email`, `password`, `role`, `created_at`, `strikes`, `fines`) VALUES
(1, 'Lockman', 'Tinubu', 'admin@ashesi.edu.gh', '$2y$10$wS2Yk.6qj/DqBw/s/u.uEe.v4.w/1.u/1.u/1.u/1.u/1.u', 'Admin', '2025-12-13 09:23:20', 0, 0.00),
(2, 'Nels', 'Pels', 'keli@ashesi.edu.gh', '$2y$10$mLPm8ZIg/Ph/06Vx1x5eiei8JQoez.tiURsp3A2GSJvqZ1ik6fw9m', 'Student', '2025-12-13 13:14:46', 0, 0.00),
(3, 'Lockman', 'Kpeli', 'kpeli@ashesi.edu.gh', '$2y$10$HrwRMNCwTpflbuZOhrOVvu2fw4V3.5AISbffyGEFYNFpZe7sZ0zQm', 'Admin', '2025-12-13 13:18:15', 0, 0.00),
(4, 'Keli', 'Kemeh', '4k@gmail.com', '$2y$10$F2iyb56/auI.irnE89fuqePUlJh0cW3rtLXr7EsN2E5vrVnUKP5tK', 'Student', '2025-12-15 09:28:21', 0, 0.00),
(5, 'Keli', '2.0', '3k@gmail.com', '$2y$10$mcn7fMpY4B6olAv2YWQ1g.tS7SWDRitOZDWNRmJuowlLf.JJuW/FK', 'Admin', '2025-12-15 09:41:24', 0, 0.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`item_id`),
  ADD UNIQUE KEY `serial_number` (`serial_number`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `idx_loans_user_item_status` (`user_id`,`item_id`,`status`);

--
-- Indexes for table `maintenance_tickets`
--
ALTER TABLE `maintenance_tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `item_id` (`item_id`),
  ADD KEY `opened_by` (`opened_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `maintenance_tickets`
--
ALTER TABLE `maintenance_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `loans`
--
ALTER TABLE `loans`
  ADD CONSTRAINT `loans_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `loans_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`) ON DELETE CASCADE;

--
-- Constraints for table `maintenance_tickets`
--
ALTER TABLE `maintenance_tickets`
  ADD CONSTRAINT `fk_maint_item` FOREIGN KEY (`item_id`) REFERENCES `inventory` (`item_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_maint_user` FOREIGN KEY (`opened_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
