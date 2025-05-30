-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2025 at 03:14 AM
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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- --------------------------------------------------------

--
-- Table structure for table `admin_actions`
--

CREATE TABLE `admin_actions` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `target_id` int(11) NOT NULL,
  `target_type` enum('user','experience','establishment') DEFAULT NULL,
  `performed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL,
  `target_type` enum('experience','establishment') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carnets`
--

CREATE TABLE `carnets` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carnets`
--

INSERT INTO `carnets` (`id`, `title`, `author`, `created_at`) VALUES
(1, 'Découverte de l\'Est algérien', 'Amine K.', '2025-05-10 13:32:29'),
(2, 'Mes Beaux Souvenirs', 'Samira Harket', '2025-05-10 13:32:29'),
(3, 'Road-trip 2024', 'Bouguera_22', '2025-05-10 13:32:29'),
(4, 'Découverte de l\'Est algérien', 'Amine K.', '2025-05-10 13:34:37'),
(5, 'Découverte de l\'Est algérien', 'Amine K.', '2025-05-10 13:36:39'),
(6, 'Mes Beaux Souvenirs', 'Samira Harket', '2025-05-10 13:36:39'),
(7, 'Road-trip 2024', 'Bouguera_22', '2025-05-10 13:36:39'),
(8, 'Sur les traces de l\'histoire ottomane', 'Farid K.', '2025-05-10 13:36:39'),
(9, 'Randonnées en Kabylie', 'Yasmine Z.', '2025-05-10 13:36:39'),
(10, 'Les oasis du Grand Sud', 'Omar S.', '2025-05-10 13:36:39'),
(11, 'Plages de l\'Ouest algérien', 'Leila M.', '2025-05-10 13:36:39');

-- --------------------------------------------------------

--
-- Table structure for table `carnet_images`
--

CREATE TABLE `carnet_images` (
  `id` int(11) NOT NULL,
  `carnet_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carnet_images`
--

INSERT INTO `carnet_images` (`id`, `carnet_id`, `image_path`) VALUES
(1, 1, 'image/constantine_pont_sidi_mcid.jpg'),
(2, 1, 'image/constantine_gorges.jpg'),
(3, 1, 'image/constantine_vueille_ville.jpg'),
(4, 4, 'image/constantine_pont_sidi_mcid.jpg'),
(5, 4, 'image/constantine_gorges.jpg'),
(6, 4, 'image/constantine_vueille_ville.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `carnet_locations`
--

CREATE TABLE `carnet_locations` (
  `id` int(11) NOT NULL,
  `carnet_id` int(11) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carnet_locations`
--

INSERT INTO `carnet_locations` (`id`, `carnet_id`, `location`) VALUES
(1, 1, 'Constantine'),
(2, 1, 'Batna');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL COMMENT 'URL-friendly version of the name',
  `description` text DEFAULT NULL,
  `parent_category_id` int(11) DEFAULT NULL,
  `type` enum('experience','establishment','general') DEFAULT 'general' COMMENT 'Specifies if category is primarily for experiences, establishments, or general use',
  `icon_class` varchar(50) DEFAULT NULL COMMENT 'e.g., Font Awesome class for UI display',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_category_id`, `type`, `icon_class`, `created_at`, `updated_at`) VALUES
(1, 'Adventure Travel', 'adventure-travel', 'Experiences related to adventure and exploration.', NULL, 'experience', NULL, '2025-05-09 13:57:58', '2025-05-09 13:57:58'),
(2, 'Historical Sites', 'historical-sites', 'Places of historical significance.', NULL, 'general', NULL, '2025-05-09 13:57:58', '2025-05-09 13:57:58'),
(3, 'Restaurants', 'restaurants', 'Dining establishments.', NULL, 'establishment', NULL, '2025-05-09 13:57:58', '2025-05-09 13:57:58'),
(4, 'Local Cuisine', 'local-cuisine', 'Featuring local food and culinary traditions.', NULL, 'general', NULL, '2025-05-09 13:57:58', '2025-05-09 13:57:58');

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

-- --------------------------------------------------------

--
-- Table structure for table `establishment_categories`
--

CREATE TABLE `establishment_categories` (
  `establishment_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `experiences`
--

CREATE TABLE `experiences` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `content` text NOT NULL,
  `location_id` int(11) DEFAULT NULL,
  `cover_media_id` int(11) DEFAULT NULL,
  `status` enum('draft','pending','published','rejected') DEFAULT 'draft',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `experiences`
--

INSERT INTO `experiences` (`id`, `user_id`, `title`, `content`, `location_id`, `cover_media_id`, `status`, `created_at`) VALUES
(1, 2, 'Voyage dans le désert', 'Une expérience incroyable...', NULL, NULL, 'published', '2025-05-09 13:43:16'),
(2, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:24:09'),
(3, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:24:09'),
(4, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:24:12'),
(5, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:24:12'),
(6, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:24:16'),
(7, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:24:16'),
(8, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:24:18'),
(9, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:24:18'),
(10, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:24:19'),
(11, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:24:19'),
(12, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:24:20'),
(13, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:24:20'),
(14, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:27:14'),
(15, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:27:14'),
(16, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:27:17'),
(17, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:27:17'),
(18, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:27:18'),
(19, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:27:18'),
(20, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 14:27:20'),
(21, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 14:27:20'),
(22, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 15:14:54'),
(23, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 15:14:54'),
(24, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 15:14:57'),
(25, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 15:14:57'),
(26, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 15:14:58'),
(27, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 15:14:58'),
(28, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 15:14:59'),
(29, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 15:14:59'),
(30, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 15:16:26'),
(31, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 15:16:26'),
(32, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 15:16:38'),
(33, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 15:16:38'),
(34, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 15:16:40'),
(35, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 15:16:40'),
(36, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-09 15:30:03'),
(37, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-09 15:30:03'),
(38, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:57:54'),
(39, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:57:54'),
(40, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:57:57'),
(41, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:57:57'),
(42, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:57:58'),
(43, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:57:58'),
(44, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:57:59'),
(45, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:57:59'),
(46, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:57:59'),
(47, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:57:59'),
(48, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:57:59'),
(49, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:57:59'),
(50, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:57:59'),
(51, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:57:59'),
(52, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:58:03'),
(53, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:58:03'),
(54, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:58:05'),
(55, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:58:05'),
(56, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:58:06'),
(57, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:58:06'),
(58, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:58:08'),
(59, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:58:08'),
(60, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:59:30'),
(61, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:59:30'),
(62, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:59:31'),
(63, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:59:31'),
(64, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:59:31'),
(65, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:59:31'),
(66, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-10 10:59:32'),
(67, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-10 10:59:32'),
(68, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 19:42:55'),
(69, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 19:42:55'),
(70, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:23'),
(71, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:23'),
(72, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:25'),
(73, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:25'),
(74, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:26'),
(75, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:26'),
(76, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:27'),
(77, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:27'),
(78, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:29'),
(79, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:29'),
(80, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:30'),
(81, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:30'),
(82, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:35'),
(83, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:35'),
(84, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:37'),
(85, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:37'),
(86, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:39'),
(87, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:39'),
(88, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:23:40'),
(89, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:23:40'),
(90, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:26:58'),
(91, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:26:58'),
(92, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:26:58'),
(93, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:26:58'),
(94, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:26:59'),
(95, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:26:59'),
(96, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:27:00'),
(97, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:27:00'),
(98, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:29:44'),
(99, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:29:44'),
(100, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:29:45'),
(101, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:29:45'),
(102, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:29:46'),
(103, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:29:46'),
(104, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:29:47'),
(105, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:29:47'),
(106, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:29:50'),
(107, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:29:50'),
(108, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:29:56'),
(109, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:29:56'),
(110, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:29:57'),
(111, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:29:57'),
(112, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:29:57'),
(113, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:29:57'),
(114, 1, 'Première expérience', 'Description exemple 1', NULL, NULL, 'draft', '2025-05-11 20:29:58'),
(115, 2, 'Deuxième expérience', 'Description exemple 2', NULL, NULL, 'draft', '2025-05-11 20:29:58');

-- --------------------------------------------------------

--
-- Table structure for table `experience_categories`
--

CREATE TABLE `experience_categories` (
  `experience_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL COMMENT 'Name of the location, e.g., specific place, city, or general area',
  `address_line1` varchar(255) DEFAULT NULL,
  `address_line2` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region_or_state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `country_code` char(2) DEFAULT NULL COMMENT 'ISO 3166-1 alpha-2 country code, e.g., DZ for Algeria',
  `latitude` decimal(10,8) DEFAULT NULL COMMENT 'Latitude coordinates',
  `longitude` decimal(11,8) DEFAULT NULL COMMENT 'Longitude coordinates',
  `description` text DEFAULT NULL COMMENT 'Optional description of the location',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `name`, `address_line1`, `address_line2`, `city`, `region_or_state`, `postal_code`, `country_code`, `latitude`, `longitude`, `description`, `created_at`, `updated_at`) VALUES
(1, 'Casbah of Algiers', NULL, NULL, 'Algiers', 'Algiers Province', NULL, 'DZ', 36.78440000, 3.06000000, 'The historic citadel of Algiers.', '2025-05-09 13:57:58', '2025-05-09 13:57:58'),
(2, 'Tassili n\'Ajjer', NULL, NULL, 'Djanet', 'Illizi Province', NULL, 'DZ', 25.50000000, 9.00000000, 'A national park in the Sahara desert, known for prehistoric rock art.', '2025-05-09 13:57:58', '2025-05-09 13:57:58');

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `id` int(11) NOT NULL,
  `uploader_user_id` int(11) DEFAULT NULL COMMENT 'User who uploaded the media; NULL if system-uploaded or anonymous',
  `target_id` int(11) NOT NULL COMMENT 'ID of the entity this media is associated with (e.g., experience_id, establishment_id)',
  `target_type` enum('experience','establishment','user_profile','location','review') NOT NULL COMMENT 'Type of the entity this media is associated with',
  `file_path` varchar(255) NOT NULL COMMENT 'Path or URL to the media file',
  `file_name` varchar(255) DEFAULT NULL COMMENT 'Original name of the file',
  `mime_type` varchar(100) DEFAULT NULL COMMENT 'MIME type of the file, e.g., image/jpeg',
  `file_size_kb` int(11) DEFAULT NULL COMMENT 'File size in kilobytes',
  `caption` text DEFAULT NULL COMMENT 'Optional caption or description for the media',
  `is_cover` tinyint(1) DEFAULT 0 COMMENT 'Indicates if this is the primary/cover image for the target entity',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Order of media items if multiple are associated with one target',
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`id`, `uploader_user_id`, `target_id`, `target_type`, `file_path`, `file_name`, `mime_type`, `file_size_kb`, `caption`, `is_cover`, `sort_order`, `uploaded_at`) VALUES
(1, 2, 1, 'experience', '/uploads/images/experience1_cover.jpg', 'experience1_cover.jpg', 'image/jpeg', NULL, 'Cover image for Voyage dans le désert', 1, 0, '2025-05-09 13:57:58'),
(2, 2, 1, 'experience', '/uploads/images/experience1_gallery1.jpg', 'experience1_gallery1.jpg', 'image/jpeg', NULL, 'Camel ride in the desert', 0, 0, '2025-05-09 13:57:58');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `recipient_user_id` int(11) NOT NULL,
  `sender_user_id` int(11) DEFAULT NULL COMMENT 'User who triggered the notification, if applicable (e.g., user who commented)',
  `type` varchar(50) NOT NULL COMMENT 'Categorizes the notification, e.g., "new_review", "experience_approved", "follower_update"',
  `message` text NOT NULL,
  `related_entity_id` int(11) DEFAULT NULL COMMENT 'ID of the entity related to this notification (e.g., experience_id, review_id)',
  `related_entity_type` varchar(50) DEFAULT NULL COMMENT 'Type of the related entity (e.g., "experience", "review")',
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `recipient_user_id`, `sender_user_id`, `type`, `message`, `related_entity_id`, `related_entity_type`, `is_read`, `read_at`, `created_at`) VALUES
(1, 2, 1, 'experience_approved', 'Your submitted experience \"Voyage dans le désert\" has been approved!', 1, 'experience', 0, NULL, '2025-05-09 13:57:58');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `target_id` int(11) NOT NULL COMMENT 'ID of the item being reviewed (experience_id or establishment_id)',
  `target_type` enum('experience','establishment') NOT NULL COMMENT 'Type of item being reviewed',
  `rating` tinyint(3) UNSIGNED NOT NULL COMMENT 'Rating from 1 to 5',
  `title` varchar(150) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending' COMMENT 'Moderation status of the review',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `target_id`, `target_type`, `rating`, `title`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'experience', 5, 'Absolutely breathtaking!', 'The Sahara desert trip was a once-in-a-lifetime experience. Highly recommended.', 'approved', '2025-05-09 13:57:58', '2025-05-09 13:57:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('user','business','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@discoverdz.dz', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2025-05-09 13:43:15', '2025-05-09 13:43:15'),
(2, 'user1', 'user1@discoverdz.dz', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', '2025-05-09 13:43:15', '2025-05-09 13:43:15'),
(14, 'user2', 'user2@test.com', '', 'user', '2025-05-09 14:14:23', '2025-05-09 14:14:23'),
(179, 'test@test.com', 'akramblair21@gmail.com', '$2y$10$.4aI3Ac9fBeGfoYM4X1Rdeo69DlL9JLaRsqTdR1vGJjQfJtUI632i', 'user', '2025-05-25 00:16:55', '2025-05-25 00:16:55');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_media_id` int(11) DEFAULT NULL COMMENT 'FK to media table for profile picture',
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other','prefer_not_to_say') DEFAULT NULL,
  `home_city` varchar(100) DEFAULT NULL,
  `home_country_code` char(2) DEFAULT NULL,
  `social_links` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Store social media links, e.g., {"twitter": "url", "facebook": "url"}' CHECK (json_valid(`social_links`)),
  `preferences` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'User-specific preferences, e.g., {"theme": "dark", "notifications_enabled": true}' CHECK (json_valid(`preferences`)),
  `last_login_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`user_id`, `full_name`, `bio`, `profile_media_id`, `date_of_birth`, `gender`, `home_city`, `home_country_code`, `social_links`, `preferences`, `last_login_at`) VALUES
(2, 'User One Fullname', 'Loves to travel and explore new cultures. Passionate about photography.', NULL, NULL, NULL, 'Oran', 'DZ', '{\"twitter\": \"https://twitter.com/user1\", \"instagram\": \"https://instagram.com/user1\"}', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_user_target_bookmark` (`user_id`,`target_id`,`target_type`),
  ADD KEY `idx_bookmarks_target` (`target_id`,`target_type`);

--
-- Indexes for table `carnets`
--
ALTER TABLE `carnets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carnet_images`
--
ALTER TABLE `carnet_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carnet_id` (`carnet_id`);

--
-- Indexes for table `carnet_locations`
--
ALTER TABLE `carnet_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carnet_id` (`carnet_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parent_category_id` (`parent_category_id`);

--
-- Indexes for table `establishments`
--
ALTER TABLE `establishments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`);

--
-- Indexes for table `establishment_categories`
--
ALTER TABLE `establishment_categories`
  ADD PRIMARY KEY (`establishment_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_experiences_location` (`location_id`),
  ADD KEY `fk_experiences_cover_media` (`cover_media_id`);

--
-- Indexes for table `experience_categories`
--
ALTER TABLE `experience_categories`
  ADD PRIMARY KEY (`experience_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uploader_user_id` (`uploader_user_id`),
  ADD KEY `idx_media_target` (`target_id`,`target_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipient_user_id` (`recipient_user_id`),
  ADD KEY `sender_user_id` (`sender_user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_reviews_target` (`target_id`,`target_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `profile_media_id` (`profile_media_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `admin_actions`
--
ALTER TABLE `admin_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookmarks`
--
ALTER TABLE `bookmarks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carnets`
--
ALTER TABLE `carnets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `carnet_images`
--
ALTER TABLE `carnet_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `carnet_locations`
--
ALTER TABLE `carnet_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `establishments`
--
ALTER TABLE `establishments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=180;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_actions`
--
ALTER TABLE `admin_actions`
  ADD CONSTRAINT `admin_actions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carnet_images`
--
ALTER TABLE `carnet_images`
  ADD CONSTRAINT `carnet_images_ibfk_1` FOREIGN KEY (`carnet_id`) REFERENCES `carnets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carnet_locations`
--
ALTER TABLE `carnet_locations`
  ADD CONSTRAINT `carnet_locations_ibfk_1` FOREIGN KEY (`carnet_id`) REFERENCES `carnets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `establishments`
--
ALTER TABLE `establishments`
  ADD CONSTRAINT `establishments_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `establishment_categories`
--
ALTER TABLE `establishment_categories`
  ADD CONSTRAINT `establishment_categories_ibfk_1` FOREIGN KEY (`establishment_id`) REFERENCES `establishments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `establishment_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `experiences`
--
ALTER TABLE `experiences`
  ADD CONSTRAINT `experiences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_experiences_cover_media` FOREIGN KEY (`cover_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_experiences_location` FOREIGN KEY (`location_id`) REFERENCES `locations` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `experience_categories`
--
ALTER TABLE `experience_categories`
  ADD CONSTRAINT `experience_categories_ibfk_1` FOREIGN KEY (`experience_id`) REFERENCES `experiences` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `experience_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`uploader_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`recipient_user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`sender_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_profiles_ibfk_2` FOREIGN KEY (`profile_media_id`) REFERENCES `media` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
