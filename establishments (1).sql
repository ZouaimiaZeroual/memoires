-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 02:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `memoire`
--

-- --------------------------------------------------------

--
-- Table structure for table `establishments`
--

CREATE TABLE `establishments` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `nom_etablissement` varchar(100) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `type` enum('Hôtel','Restaurant','Clinique','Hôpital') NOT NULL,
  `wilaya` varchar(50) NOT NULL,
  `approval_status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `establishments`
--

INSERT INTO `establishments` (`id`, `owner_id`, `nom_etablissement`, `nom`, `prenom`, `telephone`, `email`, `type`, `wilaya`, `approval_status`, `created_at`) VALUES
(2, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:24:09'),
(3, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 14:24:12'),
(4, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:24:12'),
(5, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 14:24:16'),
(6, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:24:16'),
(7, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 14:24:18'),
(8, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:24:18'),
(9, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 14:24:19'),
(10, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:24:19'),
(11, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 14:24:20'),
(12, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:24:20'),
(13, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 14:27:14'),
(14, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:27:14'),
(15, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 14:27:17'),
(16, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:27:17'),
(17, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 14:27:18'),
(18, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:27:18'),
(19, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 14:27:20'),
(20, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 14:27:20'),
(21, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 15:14:54'),
(22, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 15:14:54'),
(23, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 15:14:57'),
(24, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 15:14:57'),
(25, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 15:14:58'),
(26, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 15:14:58'),
(27, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 15:14:59'),
(28, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 15:14:59'),
(29, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 15:16:26'),
(30, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 15:16:26'),
(31, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 15:16:38'),
(32, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 15:16:38'),
(33, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 15:16:40'),
(34, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 15:16:40'),
(35, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-09 15:30:03'),
(36, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-09 15:30:03'),
(37, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:57:54'),
(38, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:57:54'),
(39, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:57:57'),
(40, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:57:57'),
(41, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:57:58'),
(42, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:57:58'),
(43, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:57:59'),
(44, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:57:59'),
(45, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:57:59'),
(46, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:57:59'),
(47, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:57:59'),
(48, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:57:59'),
(49, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:57:59'),
(50, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:57:59'),
(51, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:58:03'),
(52, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:58:03'),
(53, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:58:05'),
(54, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:58:05'),
(55, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:58:06'),
(56, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:58:06'),
(57, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:58:08'),
(58, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:58:08'),
(59, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:59:30'),
(60, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:59:30'),
(61, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:59:31'),
(62, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:59:31'),
(63, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:59:31'),
(64, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:59:31'),
(65, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-10 10:59:32'),
(66, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-10 10:59:32'),
(67, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 19:42:55'),
(68, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 19:42:55'),
(69, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:23'),
(70, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:23'),
(71, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:25'),
(72, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:25'),
(73, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:26'),
(74, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:26'),
(75, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:27'),
(76, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:27'),
(77, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:29'),
(78, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:29'),
(79, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:30'),
(80, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:30'),
(81, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:35'),
(82, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:35'),
(83, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:37'),
(84, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:37'),
(85, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:39'),
(86, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:39'),
(87, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:23:40'),
(88, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:23:40'),
(89, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:26:58'),
(90, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:26:58'),
(91, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:26:58'),
(92, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:26:58'),
(93, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:26:59'),
(94, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:26:59'),
(95, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:27:00'),
(96, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:27:00'),
(97, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:29:44'),
(98, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:29:44'),
(99, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:29:45'),
(100, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:29:45'),
(101, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:29:46'),
(102, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:29:46'),
(103, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:29:47'),
(104, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:29:47'),
(105, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:29:50'),
(106, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:29:50'),
(107, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:29:56'),
(108, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:29:56'),
(109, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:29:57'),
(110, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:29:57'),
(111, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:29:57'),
(112, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:29:57'),
(113, 1, 'Établissement 1', '', '', '', '', 'Hôtel', '', 'pending', '2025-05-11 20:29:58'),
(114, 2, 'Établissement 2', '', '', '', '', 'Restaurant', '', 'pending', '2025-05-11 20:29:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `establishments`
--
ALTER TABLE `establishments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `establishments`
--
ALTER TABLE `establishments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `establishments`
--
ALTER TABLE `establishments`
  ADD CONSTRAINT `establishments_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
