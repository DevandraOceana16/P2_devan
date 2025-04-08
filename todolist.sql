-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 07, 2025 at 02:30 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `todolist`
--

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `created_by`, `created_at`) VALUES
(1, 'Bergunjay', 1, '2025-03-26 09:16:17'),
(2, 'main ml', 4, '2025-03-27 16:55:11'),
(3, 'Technica', 3, '2025-04-03 05:47:53'),
(4, 'membuat Tokopedia', 2, '2025-04-04 15:37:44'),
(5, 'tokped', 1, '2025-04-04 15:42:45'),
(6, 'ngentot bareng', 1, '2025-04-05 10:24:12'),
(7, 'Technicaoooo', 1, '2025-04-07 07:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `group_invitations`
--

CREATE TABLE `group_invitations` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `user_id` int NOT NULL,
  `role` enum('admin','member') NOT NULL DEFAULT 'member',
  `status` enum('pending','accepted','rejected') DEFAULT 'accepted'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `user_id`, `role`, `status`) VALUES
(1, 1, 1, 'admin', 'accepted'),
(2, 2, 4, 'admin', 'accepted'),
(3, 1, 3, 'member', 'accepted'),
(4, 3, 3, 'admin', 'accepted'),
(5, 3, 2, 'member', 'accepted'),
(6, 3, 1, 'member', 'accepted'),
(7, 4, 2, 'admin', 'accepted'),
(8, 5, 1, 'admin', 'accepted'),
(9, 5, 2, 'member', 'accepted'),
(10, 6, 1, 'admin', 'accepted'),
(11, 6, 2, 'member', 'accepted'),
(12, 7, 1, 'admin', 'accepted'),
(13, 7, 2, 'member', 'accepted');

-- --------------------------------------------------------

--
-- Table structure for table `group_tasks`
--

CREATE TABLE `group_tasks` (
  `id` int NOT NULL,
  `group_id` int NOT NULL,
  `created_by` int NOT NULL,
  `text` varchar(255) NOT NULL,
  `priority` enum('1','2','3') NOT NULL,
  `due_date_time` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_tasks`
--

INSERT INTO `group_tasks` (`id`, `group_id`, `created_by`, `text`, `priority`, `due_date_time`, `created_at`) VALUES
(3, 2, 4, 'mm', '2', '2025-03-28 00:57:00', '2025-03-27 16:56:52'),
(4, 1, 1, 'Makan sampeu', '2', '2025-03-30 06:30:00', '2025-03-27 19:24:53'),
(7, 3, 3, 'Buat Desain Web Profile Technica', '2', '2025-04-04 12:00:00', '2025-04-03 05:50:29'),
(8, 1, 1, 'besok bangun tidur mantap', '1', '2025-04-24 02:10:00', '2025-04-04 14:10:37'),
(9, 6, 1, 'besok bangun tidur mantap', '3', '2025-04-05 17:31:00', '2025-04-05 10:25:19'),
(10, 7, 1, 'besok bangun tidur mantap', '2', '2025-04-07 14:37:00', '2025-04-07 07:37:27');

-- --------------------------------------------------------

--
-- Table structure for table `group_task_assignees`
--

CREATE TABLE `group_task_assignees` (
  `id` int NOT NULL,
  `task_id` int NOT NULL,
  `user_id` int NOT NULL,
  `completed` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `group_task_assignees`
--

INSERT INTO `group_task_assignees` (`id`, `task_id`, `user_id`, `completed`) VALUES
(3, 3, 4, 0),
(4, 4, 3, 1),
(7, 7, 2, 1),
(8, 8, 1, 0),
(9, 9, 2, 1),
(10, 10, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `individual_tasks`
--

CREATE TABLE `individual_tasks` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `text` text NOT NULL,
  `priority` int NOT NULL,
  `due_date_time` datetime NOT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `individual_tasks`
--

INSERT INTO `individual_tasks` (`id`, `user_id`, `text`, `priority`, `due_date_time`, `completed`, `created_at`) VALUES
(1, 2, 'saur', 2, '2025-03-16 03:00:00', 0, '2025-03-15 18:50:07'),
(2, 1, 'Mandi Wajib', 1, '2025-03-16 04:00:00', 0, '2025-03-15 18:51:07'),
(4, 3, 'Membuat Projek Website', 2, '2025-03-30 16:29:00', 0, '2025-03-26 09:29:30'),
(6, 4, 'tidur', 3, '2025-03-28 01:00:00', 0, '2025-03-27 16:54:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'Anjay Gurinjay', '$2y$10$YF8HfNzu/yO0hDkr4Q2fyeoel9IzDFCWymHv45LnuID7HBvWwl.5W', 'gurinjay2@gmail.com', '2025-03-15 18:43:38'),
(2, 'depunk11', '$2y$10$5gsZTYhw55kY0AKyqaJ9Wu0tFlA1aW3rcaqe6SOm.rqgdQAf63Jwy', 'dep@gmail.com', '2025-03-15 18:43:38'),
(3, 'adnngrn12', '$2y$10$YxxzJLhQYeNBdUpUMoMXVuDBVFJhJ/rb/Rx5wd5LdFL0KAJkwH3e.', 'agrn321@gmail.com', '2025-03-26 09:23:32'),
(4, 'anokeren', '$2y$10$HQcEyTD2teiC2cCmlVc.KedtU7HjZXb3ABmAm0C4nuRXAk.QWygjK', 'anomen1@gmail.com', '2025-03-27 16:53:49');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `group_invitations`
--
ALTER TABLE `group_invitations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `group_tasks`
--
ALTER TABLE `group_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `group_task_assignees`
--
ALTER TABLE `group_task_assignees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `individual_tasks`
--
ALTER TABLE `individual_tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `group_invitations`
--
ALTER TABLE `group_invitations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `group_tasks`
--
ALTER TABLE `group_tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `group_task_assignees`
--
ALTER TABLE `group_task_assignees`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `individual_tasks`
--
ALTER TABLE `individual_tasks`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
  ADD CONSTRAINT `groups_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_invitations`
--
ALTER TABLE `group_invitations`
  ADD CONSTRAINT `group_invitations_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_invitations_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_invitations_ibfk_3` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_tasks`
--
ALTER TABLE `group_tasks`
  ADD CONSTRAINT `group_tasks_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_tasks_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_task_assignees`
--
ALTER TABLE `group_task_assignees`
  ADD CONSTRAINT `group_task_assignees_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `group_tasks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_task_assignees_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `individual_tasks`
--
ALTER TABLE `individual_tasks`
  ADD CONSTRAINT `individual_tasks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
