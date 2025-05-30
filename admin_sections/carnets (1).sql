-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2025 at 12:03 AM
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
-- Table structure for table `carnets`
--

CREATE TABLE `carnets` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `location` varchar(100) DEFAULT NULL,
  `place` varchar(100) DEFAULT NULL,
  `transport` varchar(100) DEFAULT NULL,
  `content` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carnets`
--

INSERT INTO `carnets` (`id`, `title`, `author`, `created_at`, `location`, `place`, `transport`, `content`) VALUES
(1, 'Découverte de l\'Est algérien', 'Amine K.', '2025-05-10 13:32:29', NULL, NULL, NULL, NULL),
(2, 'Mes Beaux Souvenirs', 'Samira Harket', '2025-05-10 13:32:29', NULL, NULL, NULL, NULL),
(3, 'Road-trip 2024', 'Bouguera_22', '2025-05-10 13:32:29', NULL, NULL, NULL, NULL),
(4, 'Découverte de l\'Est algérien', 'Amine K.', '2025-05-10 13:34:37', NULL, NULL, NULL, NULL),
(6, 'Mes Beaux Souvenirs', 'Samira Harket', '2025-05-10 13:36:39', NULL, NULL, NULL, NULL),
(7, 'Road-trip 2024', 'Bouguera_22', '2025-05-10 13:36:39', NULL, NULL, NULL, NULL),
(8, 'Sur les traces de l\'histoire ottomane', 'Farid K.', '2025-05-10 13:36:39', NULL, NULL, NULL, NULL),
(9, 'Randonnées en Kabylie', 'Yasmine Z.', '2025-05-10 13:36:39', NULL, NULL, NULL, NULL),
(10, 'Les oasis du Grand Sud', 'Omar S.', '2025-05-10 13:36:39', NULL, NULL, NULL, NULL),
(11, 'Plages de l\'Ouest algérien', 'Leila M.', '2025-05-10 13:36:39', NULL, NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carnets`
--
ALTER TABLE `carnets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carnets`
--
ALTER TABLE `carnets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
