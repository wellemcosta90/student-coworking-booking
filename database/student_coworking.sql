-- Clean SQL dump for Student Coworking Booking System
-- Database: student_coworking_db
-- Demo password for all users: Password123

DROP DATABASE IF EXISTS `student_coworking_db`;
CREATE DATABASE `student_coworking_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `student_coworking_db`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','organiser','attendee') DEFAULT 'attendee',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Admin User', 'admin@system.com', '$2y$10$sfVTgyYmrTce4aC3amDTQu84q6J2RMiXNJ8VVl4T9bdhubUhpByh.', 'admin', current_timestamp()),
(2, 'Organiser User', 'organiser@system.com', '$2y$10$sfVTgyYmrTce4aC3amDTQu84q6J2RMiXNJ8VVl4T9bdhubUhpByh.', 'organiser', current_timestamp()),
(3, 'Attendee User', 'attendee@system.com', '$2y$10$sfVTgyYmrTce4aC3amDTQu84q6J2RMiXNJ8VVl4T9bdhubUhpByh.', 'attendee', current_timestamp());

INSERT INTO `rooms` (`room_id`, `room_name`, `room_type`, `capacity`, `description`, `status`, `wifi`, `coffee`, `whiteboard`, `image`) VALUES
(1, 'Individual Room 1', 'individual', 1, 'Quiet individual study room.', 'available', 1, 1, 0, NULL),
(2, 'Meeting Room A', 'meeting', 10, 'Group meeting room for study and project work.', 'available', 1, 1, 1, NULL);

ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `rooms`
  ADD PRIMARY KEY (`room_id`);

ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `room_id` (`room_id`);

ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `rooms`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`);
