-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 01, 2026 at 05:18 PM
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
-- Database: `student_coworking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `booking_purpose` varchar(100) DEFAULT NULL,
  `status` enum('booked','cancelled') DEFAULT 'booked',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `room_id`, `booking_date`, `start_time`, `end_time`, `booking_purpose`, `status`, `created_at`) VALUES
(4, 12, 14, '2026-04-30', '08:00:00', '10:00:00', 'Individual Work', 'cancelled', '2026-04-28 19:16:45'),
(5, 12, 15, '2026-04-29', '13:00:00', '17:00:00', 'Group Study', 'cancelled', '2026-04-28 19:25:45'),
(6, 11, 14, '2026-06-05', '09:00:00', '10:00:00', 'Individual Study', 'cancelled', '2026-04-28 20:58:54');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `room_type` enum('meeting','individual') NOT NULL,
  `capacity` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('available','unavailable') DEFAULT 'available',
  `wifi` tinyint(1) DEFAULT 1,
  `coffee` tinyint(1) DEFAULT 1,
  `whiteboard` tinyint(1) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`room_id`, `room_name`, `room_type`, `capacity`, `description`, `status`, `wifi`, `coffee`, `whiteboard`, `image`) VALUES
(14, 'Individual Room 1', 'individual', 1, NULL, 'available', 1, 1, 0, NULL),
(15, 'Meeting Room A', 'meeting', 10, NULL, 'available', 1, 1, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','organiser','attendee') DEFAULT 'attendee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(10, 'joao', 'joao@gmail.com', '$2y$10$r9yOPB01aDihvurqAuU1TejzCV7O8dY22Jy7iPyFy.MBu9vn14xi6', 'attendee', '2026-04-28 18:23:40'),
(11, 'boss', 'boss@gmail.com', '$2y$10$T.8di0DMVU.X3X2lo2bbDuJxVDrOUICI0XUkjeCH7mhYUVI5wwoNu', 'attendee', '2026-04-28 18:51:32'),
(12, 'joana', 'joana@gmail.com', '$2y$10$rsRzlcFV9nVPQTFA5TxHPum3o.tskgdnvMNynGit55Wq1lhPuY4yi', 'attendee', '2026-04-28 19:15:43'),
(13, 'Admin User', 'admin@system.com', '$2y$10$Fk46wjLciZHKzUx9sDEcQe.fbs0B.FkyBzqWXUKxzhwvju/Sq3wL2', 'admin', '2026-04-28 19:54:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
