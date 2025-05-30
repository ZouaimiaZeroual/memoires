-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 11:40 PM
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

INSERT INTO `establishments` (`id`, `nom_etablissement`, `nom`, `prenom`, `telephone`, `email`, `type`, `wilaya`, `approval_status`, `created_at`) VALUES
(117, 'Akram', 'Bellir', 'salah', '0676602035', 'akramblair21@gmail.com', 'Hôtel', '20 - Saïda', 'pending', '2025-05-25 11:50:58'),
(118, 'Akram', 'Bellir', 'salah', '0676602035', 'akram@gmail.com', 'Hôtel', '1 - Adrar', 'pending', '2025-05-25 11:51:27'),
(119, 'Akram', 'Bellir', 'salah', '0676602035', 'akra@gmail.com', 'Hôtel', '15 - Tizi Ouzou', 'pending', '2025-05-30 12:39:13'),
(120, 'Akram', 'Bellir', 'mohamed said', '0676602035', 'ak@gmail.com', 'Hôtel', '19 - Sétif', 'pending', '2025-05-30 12:45:07');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `establishments`
--
ALTER TABLE `establishments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `establishments`
--
ALTER TABLE `establishments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
