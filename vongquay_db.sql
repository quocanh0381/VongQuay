-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 04:41 AM
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
-- Database: `vongquay_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) NOT NULL,
  `room_code` varchar(10) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `max_players` int(11) DEFAULT 10,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `room_participants`
--

CREATE TABLE `room_participants` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_room_owner` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id` int(11) NOT NULL,
  `match_name` varchar(100) NOT NULL,
  `room_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `room_code`, `password`, `created_by`, `max_players`, `is_active`, `created_at`) VALUES
(1, 'Phòng Ranked Solo', 'RANK001', 'rank123', 1, 10, 1, '2025-10-08 02:40:25'),
(2, 'Phòng Team 5vs5', 'TEAM002', 'team456', 2, 10, 1, '2025-10-08 02:40:25'),
(3, 'Phòng Casual', 'CASU003', 'casu789', 3, 15, 1, '2025-10-08 02:40:25');

-- --------------------------------------------------------

--
-- Dumping data for table `room_participants`
--

INSERT INTO `room_participants` (`id`, `room_id`, `user_id`, `joined_at`, `is_room_owner`) VALUES
(1, 1, 1, '2025-10-08 02:40:25', 1),
(2, 1, 2, '2025-10-08 02:40:25', 0),
(3, 2, 2, '2025-10-08 02:40:25', 1),
(4, 2, 3, '2025-10-08 02:40:25', 0),
(5, 3, 3, '2025-10-08 02:40:25', 1);

-- --------------------------------------------------------

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id`, `match_name`, `room_id`, `created_by`, `created_at`) VALUES
(1, 'Trận Tối Nay 5vs5', 1, 1, '2025-10-08 02:40:25'),
(2, 'Solo Cuối Tuần', 2, 2, '2025-10-08 02:40:25'),
(3, 'Giải Vui LQM', 3, 3, '2025-10-08 02:40:25');

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `match_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `personalSkill` tinyint(4) NOT NULL,
  `mapReading` tinyint(4) NOT NULL,
  `teamwork` tinyint(4) NOT NULL,
  `reaction` tinyint(4) NOT NULL,
  `experience` tinyint(4) NOT NULL,
  `position` tinyint(4) NOT NULL,
  `totalScore` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id`, `match_id`, `name`, `personalSkill`, `mapReading`, `teamwork`, `reaction`, `experience`, `position`, `totalScore`, `created_by`, `created_at`) VALUES
(1, 1, 'Hữu Trí', 4, 4, 5, 4, 5, 1, 22, 1, '2025-10-08 02:40:25'),
(2, 1, 'Khánh', 3, 3, 4, 4, 4, 2, 18, 1, '2025-10-08 02:40:25'),
(3, 1, 'Phong', 5, 5, 4, 4, 5, 4, 23, 2, '2025-10-08 02:40:25'),
(4, 2, 'Long', 2, 3, 3, 3, 3, 3, 14, 2, '2025-10-08 02:40:25'),
(5, 3, 'Tú', 4, 4, 4, 4, 4, 5, 20, 3, '2025-10-08 02:40:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `level` int(11) DEFAULT 1,
  `total_matches` int(11) DEFAULT 0,
  `win_matches` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `display_name`, `email`, `phone`, `avatar`, `level`, `total_matches`, `win_matches`, `created_at`) VALUES
(1, 'admin', '123456', 'Admin', 'admin@vongquay.com', '0123456789', NULL, 5, 10, 7, '2025-10-08 02:40:25'),
(2, 'khanh', '123456', 'Khánh', 'khanh@vongquay.com', '0987654321', NULL, 3, 5, 3, '2025-10-08 02:40:25'),
(3, 'phong', '123456', 'Phong Trần', 'phong@vongquay.com', '0369852147', NULL, 4, 8, 5, '2025-10-08 02:40:25');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_player_summary`
-- (See below for the actual view)
--
CREATE TABLE `v_player_summary` (
`player_id` int(11)
,`player_name` varchar(100)
,`totalScore` int(11)
,`position` tinyint(4)
,`match_name` varchar(100)
,`created_by` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure for view `v_player_summary`
--
DROP TABLE IF EXISTS `v_player_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_player_summary`  AS SELECT `p`.`id` AS `player_id`, `p`.`name` AS `player_name`, `p`.`totalScore` AS `totalScore`, `p`.`position` AS `position`, `m`.`match_name` AS `match_name`, `u`.`display_name` AS `created_by` FROM ((`players` `p` join `matches` `m` on(`p`.`match_id` = `m`.`id`)) join `users` `u` on(`p`.`created_by` = `u`.`id`)) ORDER BY `p`.`created_at` DESC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_code` (`room_code`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `is_active` (`is_active`);

--
-- Indexes for table `room_participants`
--
ALTER TABLE `room_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `room_user_unique` (`room_id`, `user_id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_id` (`room_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `match_id` (`match_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `room_participants`
--
ALTER TABLE `room_participants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `room_participants`
--
ALTER TABLE `room_participants`
  ADD CONSTRAINT `room_participants_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `room_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `matches_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `matches_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `players`
--
ALTER TABLE `players`
  ADD CONSTRAINT `players_ibfk_1` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `players_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
