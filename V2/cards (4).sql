-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 14, 2024 at 11:30 AM
-- Server version: 8.0.36
-- PHP Version: 8.1.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `invitsto_stock`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` int NOT NULL,
  `user` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int DEFAULT NULL,
  `at_hand` int NOT NULL,
  `out` int DEFAULT NULL,
  `in` int DEFAULT NULL,
  `balance` int NOT NULL,
  `signature` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remarks` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cards`
--

INSERT INTO `cards` (`id`, `product_id`, `user`, `size`, `at_hand`, `out`, `in`, `balance`, `signature`, `remarks`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 10, 'JACINTA KIOKO', 2, 100, 20, 30, 100, 'JACINTA KIOKO', 'Comment', NULL, '2024-08-16 10:57:22', '2024-08-16 10:57:22'),
(2, 10, 'JACINTA KIOKO', 100, 80, 20, 0, 60, 'JACINTA KIOKO', 'OK', NULL, '2024-08-19 11:40:29', '2024-08-19 11:40:29'),
(3, 304, 'JACINTA KIOKO', 25, 12, 4, 0, 8, 'JACINTA KIOKO', 'OK', NULL, '2024-08-19 11:43:27', '2024-08-19 11:43:27'),
(4, 13, 'JACINTA KIOKO', NULL, 5, 0, 10, 15, 'JACINTA KIOKO', 'OK', NULL, '2024-09-05 11:56:13', '2024-09-05 11:56:13'),
(5, 10, 'Mary W', NULL, 137, 0, 0, 137, 'Mary W', 'ok', NULL, '2024-09-20 08:39:48', '2024-09-20 08:39:48'),
(6, 10, 'Mary W', NULL, 137, 10, 10, 127, 'Mary W', 'From Supplier A', NULL, '2024-09-20 08:44:47', '2024-09-20 08:44:47'),
(7, 10, 'Patricia N', NULL, 137, NULL, 15, 152, 'Patricia N', 'From Supplier B', NULL, '2024-09-20 08:53:42', '2024-09-20 08:53:42'),
(8, 14, 'Patricia N', NULL, 10, NULL, 8, 18, 'Patricia N', 'OK', NULL, '2024-09-24 05:59:48', '2024-09-24 05:59:48'),
(9, 10, 'Mary W', NULL, 137, NULL, 70, 207, 'Mary W', 'from crown', NULL, '2024-09-24 13:27:54', '2024-09-24 13:27:54'),
(10, 292, 'Patricia N', NULL, 293, 10, NULL, 283, 'Patricia N', 'ok', NULL, '2024-10-04 11:55:10', '2024-10-04 11:55:10'),
(11, 512, 'Patricia N', NULL, 90, NULL, 60, 150, 'Patricia N', 'OK', NULL, '2024-10-04 11:56:12', '2024-10-04 11:56:12'),
(12, 491, 'Mary W', NULL, 213, 10, NULL, 203, 'Mary W', 'ok', NULL, '2024-10-04 12:05:20', '2024-10-04 12:05:20'),
(13, 411, 'Mary W', NULL, 201, NULL, 400, 601, 'Mary W', 'ok', NULL, '2024-10-04 12:06:13', '2024-10-04 12:06:13'),
(14, 382, 'Mary W', NULL, 7, 2, NULL, 5, 'Mary W', 'M.P SHAH HOSPITAL', NULL, '2024-10-08 07:23:56', '2024-10-08 07:23:56'),
(15, 1237, 'Mary W', NULL, 34, 2, NULL, 32, 'Mary W', 'M.P SHAH HOSPITAL', NULL, '2024-10-08 07:25:33', '2024-10-08 07:25:33'),
(16, 292, 'Patricia N', NULL, 272, 1, NULL, 271, 'Patricia N', 'A-Z LABORATORIES LIMITED', NULL, '2024-10-08 08:04:41', '2024-10-08 08:04:41'),
(17, 291, 'Patricia N', NULL, 79, 1, NULL, 78, 'Patricia N', 'A - Z LABORATORIES LIMITED', NULL, '2024-10-08 08:05:55', '2024-10-08 08:05:55'),
(18, 80, 'Patricia N', NULL, 1192, NULL, 25, 1217, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:26:57', '2024-10-08 08:26:57'),
(19, 83, 'Patricia N', NULL, 3917, NULL, 23, 3940, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:28:13', '2024-10-08 08:28:13'),
(20, 266, 'Patricia N', NULL, 0, NULL, 1, 1, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:29:43', '2024-10-08 08:29:43'),
(21, 834, 'Patricia N', NULL, 51, NULL, 1, 52, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:33:21', '2024-10-08 08:33:21'),
(22, 625, 'Patricia N', NULL, 161, NULL, 1, 162, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:35:41', '2024-10-08 08:35:41'),
(23, 129, 'Patricia N', NULL, 2389, NULL, 1, 2390, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:36:45', '2024-10-08 08:36:45'),
(24, 301, 'Patricia N', NULL, 22, NULL, 1, 23, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:37:44', '2024-10-08 08:37:44'),
(25, 390, 'Patricia N', NULL, 887, NULL, 1, 888, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:38:57', '2024-10-08 08:38:57'),
(26, 309, 'Patricia N', NULL, 2, NULL, 1, 3, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:40:00', '2024-10-08 08:40:00'),
(27, 486, 'Patricia N', NULL, 0, NULL, 1, 1, 'Patricia N', 'Lincoln\r\n- Demonstration in Meru region', NULL, '2024-10-08 08:41:08', '2024-10-08 08:41:08'),
(28, 881, 'Mary W', NULL, 70130, 2000, NULL, 68130, 'Mary W', 'AGA KHAN HOSPITAL KISUMU', NULL, '2024-10-08 08:52:22', '2024-10-08 08:52:22'),
(29, 295, 'Mary W', NULL, 35, 5, NULL, 30, 'Mary W', 'AGA KHAN HOSPITAL KISUMU', NULL, '2024-10-08 08:53:57', '2024-10-08 08:53:57'),
(30, 64, 'Mary W', NULL, 126, 5, NULL, 121, 'Mary W', 'AGA KHAN HOSPITAL KISUMU', NULL, '2024-10-08 08:55:45', '2024-10-08 08:55:45'),
(31, 428, 'Mary W', NULL, 660, 10, NULL, 650, 'Mary W', 'AGA KHAN HOSPITAL KISUMU', NULL, '2024-10-08 08:56:49', '2024-10-08 08:56:49'),
(32, 138, 'Mary W', NULL, 10, NULL, 15, 25, 'Mary W', 'LABOQUICK', NULL, '2024-10-08 10:25:24', '2024-10-08 10:25:24'),
(33, 292, 'Mary W', NULL, 272, 25, NULL, 247, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:29:03', '2024-10-08 10:29:03'),
(34, 456, 'Mary W', NULL, 323, 10, NULL, 313, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:30:06', '2024-10-08 10:30:06'),
(35, 267, 'Mary W', NULL, 86, 1, NULL, 85, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:31:54', '2024-10-08 10:31:54'),
(36, 820, 'Mary W', NULL, 8, 3, NULL, 5, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:33:27', '2024-10-08 10:33:27'),
(37, 599, 'Mary W', NULL, 4, 3, NULL, 1, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:36:01', '2024-10-08 10:36:01'),
(38, 59, 'Mary W', NULL, 83, 2, NULL, 81, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:37:06', '2024-10-08 10:37:06'),
(39, 61, 'Mary W', NULL, 37, 2, NULL, 35, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:40:34', '2024-10-08 10:40:34'),
(40, 512, 'Mary W', NULL, 87, 5, NULL, 82, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:41:35', '2024-10-08 10:41:35'),
(41, 395, 'Mary W', NULL, 857, 15, NULL, 842, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:42:45', '2024-10-08 10:42:45'),
(42, 881, 'Mary W', NULL, 70130, 2000, NULL, 68130, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:43:37', '2024-10-08 10:43:37'),
(43, 83, 'Mary W', NULL, 3917, 20, NULL, 3897, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:45:01', '2024-10-08 10:45:01'),
(44, 834, 'Mary W', NULL, 51, 1, NULL, 50, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:45:57', '2024-10-08 10:45:57'),
(45, 882, 'Mary W', NULL, 127409, 2000, NULL, 125409, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:46:52', '2024-10-08 10:46:52'),
(46, 240, 'Mary W', NULL, 1850, 20, NULL, 1830, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:48:02', '2024-10-08 10:48:02'),
(47, 491, 'Mary W', NULL, 203, 4, NULL, 199, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:48:57', '2024-10-08 10:48:57'),
(48, 411, 'Mary W', NULL, 201, 2, NULL, 199, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:49:49', '2024-10-08 10:49:49'),
(49, 834, 'Mary W', NULL, 51, 2, NULL, 49, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:50:51', '2024-10-08 10:50:51'),
(50, 882, 'Mary W', NULL, 127409, 2000, NULL, 125409, 'Mary W', 'RUIRU LEVEL 4', NULL, '2024-10-08 10:51:53', '2024-10-08 10:51:53'),
(51, 240, 'Mary W', NULL, 1850, 20, NULL, 1830, 'Mary W', 'KPA MOMBASA', NULL, '2024-10-08 12:21:46', '2024-10-08 12:21:46'),
(52, 395, 'Mary W', NULL, 857, 10, NULL, 847, 'Mary W', 'KPA MOMBASA', NULL, '2024-10-08 12:23:07', '2024-10-08 12:23:07'),
(53, 248, 'Mary W', NULL, 22, 5, NULL, 17, 'Mary W', 'KPA MOMBASA', NULL, '2024-10-08 12:25:19', '2024-10-08 12:25:19'),
(54, 1113, 'Mary W', NULL, 16, 1, NULL, 15, 'Mary W', 'KPA MOMBASA', NULL, '2024-10-08 12:26:47', '2024-10-08 12:26:47'),
(55, 248, 'Mary W', NULL, 22, NULL, 5, 27, 'Mary W', 'Not delivered to KPA Mombasa', NULL, '2024-10-08 12:35:31', '2024-10-08 12:35:31'),
(56, 180, 'Patricia N', NULL, 104, NULL, 1, 105, 'Patricia N', 'KIAMBU LEVEL 5 HOSPITAL\r\n-Exchanged with Coralyzer smart lamp house assembly', NULL, '2024-10-08 12:46:50', '2024-10-08 12:46:50'),
(57, 267, 'Patricia N', NULL, 86, NULL, 1, 87, 'Patricia N', 'KIAMBU LEVEL 5 HOSPITAL\r\n-Exchanged with Coralyzer smart lamp house assembly', NULL, '2024-10-08 12:47:56', '2024-10-08 12:47:56'),
(58, 70, 'Patricia N', NULL, 28, 4, NULL, 24, 'Patricia N', 'For office use', NULL, '2024-10-08 12:51:23', '2024-10-08 12:51:23'),
(59, 769, 'Mary W', NULL, 19, NULL, 6, 25, 'Mary W', 'WONDFO', NULL, '2024-10-09 06:47:16', '2024-10-09 06:47:16'),
(60, 863, 'Mary W', NULL, 20, NULL, 6, 26, 'Mary W', 'WONDFO', NULL, '2024-10-09 06:48:32', '2024-10-09 06:48:32'),
(61, 625, 'Mary W', NULL, 161, NULL, 120, 281, 'Mary W', 'WONDFO', NULL, '2024-10-09 06:50:30', '2024-10-09 06:50:30'),
(62, 628, 'Mary W', NULL, 92, NULL, 40, 132, 'Mary W', 'WONDFO', NULL, '2024-10-09 06:51:19', '2024-10-09 06:51:19'),
(63, 1254, 'Mary W', NULL, 14, NULL, 15, 29, 'Mary W', 'PUSHKANG', NULL, '2024-10-09 06:52:31', '2024-10-09 06:52:31'),
(64, 1170, 'Patricia N', NULL, 44, 1, NULL, 43, 'Patricia N', 'CHP NAROK', NULL, '2024-10-09 08:44:46', '2024-10-09 08:44:46'),
(65, 625, 'Mary W', NULL, 159, 3, NULL, 156, 'Mary W', 'ST. FRANCIS COMMUNITY HOSPITAL', NULL, '2024-10-09 08:56:10', '2024-10-09 08:56:10'),
(66, 881, 'Patricia N', NULL, 67980, 150, NULL, 67830, 'Patricia N', 'CHP NAROK', NULL, '2024-10-09 09:04:14', '2024-10-09 09:04:14'),
(67, 297, 'Patricia N', NULL, 26, 4, NULL, 22, 'Patricia N', 'KEMRI KILIFI', NULL, '2024-10-09 09:10:36', '2024-10-09 09:10:36'),
(68, 851, 'Patricia N', NULL, 77, 3, NULL, 74, 'Patricia N', 'KEMRI KILIFI', NULL, '2024-10-09 09:12:00', '2024-10-09 09:12:00'),
(69, 524, 'Mary W', NULL, 79, 1, NULL, 78, 'Mary W', 'For Office Use', NULL, '2024-10-09 09:22:22', '2024-10-09 09:22:22'),
(70, 266, 'Mary W', NULL, 1, 1, NULL, 0, 'Mary W', 'MAKONGENI HEALTH CENTRE', NULL, '2024-10-09 09:26:51', '2024-10-09 09:26:51'),
(71, 818, 'Mary W', NULL, 16, 1, NULL, 15, 'Mary W', 'BIFOUR MEDICAL LABS', NULL, '2024-10-09 11:19:36', '2024-10-09 11:19:36'),
(72, 819, 'Mary W', NULL, 11, 1, NULL, 10, 'Mary W', 'BIFOUR MEDICAL LABS', NULL, '2024-10-09 11:20:26', '2024-10-09 11:20:26'),
(73, 817, 'Mary W', NULL, 3, 1, NULL, 2, 'Mary W', 'BIFOUR MEDICAL LABS', NULL, '2024-10-09 11:21:42', '2024-10-09 11:21:42'),
(74, 790, 'Mary W', NULL, 31, 1, NULL, 30, 'Mary W', 'BIFOUR MEDICAL LABS', NULL, '2024-10-09 11:22:29', '2024-10-09 11:22:29'),
(75, 822, 'Mary W', NULL, 12, 1, NULL, 11, 'Mary W', 'BIFOUR MEDICAL LABS', NULL, '2024-10-09 11:32:17', '2024-10-09 11:32:17'),
(76, 809, 'Mary W', NULL, 1, 1, NULL, 0, 'Mary W', 'BIFOUR MEDICAL LABS', NULL, '2024-10-09 11:33:32', '2024-10-09 11:33:32'),
(77, 129, 'Patricia N', NULL, 2384, 6, NULL, 2378, 'Patricia N', 'METROPOLIS STAR LAB KENYA LIMITED', NULL, '2024-10-09 11:41:10', '2024-10-09 11:41:10'),
(78, 127, 'Patricia N', NULL, 122745, 1000, NULL, 121745, 'Patricia N', 'METROPOLIS STAR LAB KENYA LIMITED', NULL, '2024-10-09 11:43:41', '2024-10-09 11:43:41'),
(79, 35, 'Patricia N', NULL, 30, 2, NULL, 28, 'Patricia N', 'METROPOLIS STAR LAB KENYA LIMITED', NULL, '2024-10-09 11:45:13', '2024-10-09 11:45:13'),
(80, 595, 'Patricia N', NULL, 13, 2, NULL, 11, 'Patricia N', 'METROPOLIS STAR LAB KENYA LIMITED', NULL, '2024-10-09 11:47:15', '2024-10-09 11:47:15'),
(81, 411, 'Mary W', NULL, 192, 6, NULL, 186, 'Mary W', 'MATER HOSPITAL', NULL, '2024-10-09 11:58:56', '2024-10-09 11:58:56'),
(82, 550, 'Mary W', NULL, 900, 300, NULL, 600, 'Mary W', 'MATER HOSPITAL', NULL, '2024-10-09 12:01:05', '2024-10-09 12:01:05'),
(83, 625, 'Mary W', NULL, 276, 3, NULL, 273, 'Mary W', 'PANDYA MEMORIAL HOSPITAL', NULL, '2024-10-09 12:11:57', '2024-10-09 12:11:57'),
(84, 606, 'Patricia N', NULL, 64, 5, NULL, 59, 'Patricia N', 'GITHUNGURI H/C', NULL, '2024-10-09 12:54:58', '2024-10-09 12:54:58'),
(85, 1351, 'Patricia N', NULL, 5, 5, NULL, 0, 'Patricia N', 'GITHUNGURI H/C', NULL, '2024-10-09 12:56:19', '2024-10-09 12:56:19'),
(86, 1352, 'Patricia N', NULL, 5, 5, NULL, 0, 'Patricia N', 'GITHUNGURI H/C', NULL, '2024-10-09 12:57:17', '2024-10-09 12:57:17'),
(87, 7, 'Patricia N', NULL, 61, 2, NULL, 59, 'Patricia N', 'GITHUNGURI H/C', NULL, '2024-10-09 12:58:18', '2024-10-09 12:58:18'),
(88, 83, 'Patricia N', NULL, 3915, 5, NULL, 3910, 'Patricia N', 'GITHUNGURI H/C', NULL, '2024-10-09 12:59:33', '2024-10-09 12:59:33'),
(89, 292, 'Mary W', NULL, 241, 5, NULL, 236, 'Mary W', 'GITHUNGURI HEALTH CENTRE', NULL, '2024-10-09 13:27:09', '2024-10-09 13:27:09'),
(90, 411, 'Mary W', NULL, 191, 1, NULL, 190, 'Mary W', 'GITHUNGURI HEALTH CENTRE', NULL, '2024-10-09 13:28:11', '2024-10-09 13:28:11'),
(91, 473, 'Mary W', NULL, 168, 5, NULL, 163, 'Mary W', 'GITHUNGURI HEALTH CENTRE', NULL, '2024-10-09 13:30:10', '2024-10-09 13:30:10'),
(92, 491, 'Mary W', NULL, 198, 1, NULL, 197, 'Mary W', 'GITHUNGURI HEALTH CENTRE', NULL, '2024-10-09 13:31:03', '2024-10-09 13:31:03'),
(93, 833, 'Mary W', NULL, 62, 10, NULL, 52, 'Mary W', 'GITHUNGURI HEALTH CENTRE', NULL, '2024-10-09 13:32:08', '2024-10-09 13:32:08'),
(94, 183, 'Mary W', NULL, 1832, 5, NULL, 1827, 'Mary W', 'GITHUNGURI HEALTH CENTRE', NULL, '2024-10-09 13:33:40', '2024-10-09 13:33:40'),
(95, 882, 'Mary W', NULL, 124409, 1000, NULL, 123409, 'Mary W', 'GITHUNGURI HEALTH CENTRE', NULL, '2024-10-09 13:34:26', '2024-10-09 13:34:26'),
(96, 456, 'Mary W', NULL, 318, 5, NULL, 313, 'Mary W', 'GITHUNGURI HEALTH CENTRE', NULL, '2024-10-09 13:35:37', '2024-10-09 13:35:37'),
(97, 512, 'Mary W', NULL, 77, 5, NULL, 72, 'Mary W', 'GITHUNGURI HEALTH CENTRE', NULL, '2024-10-09 13:37:04', '2024-10-09 13:37:04'),
(98, 833, 'Patricia N', NULL, 65, NULL, 3, 68, 'Patricia N', 'MMYITO', NULL, '2024-10-09 14:22:30', '2024-10-09 14:22:30'),
(99, 416, 'Patricia N', NULL, 14960, NULL, 55, 15015, 'Patricia N', 'MMYITO', NULL, '2024-10-09 14:24:59', '2024-10-09 14:24:59'),
(100, 862, 'Mary W', NULL, 10, 1, NULL, 9, 'Mary W', 'For Office Use', NULL, '2024-10-11 05:46:39', '2024-10-11 05:46:39'),
(101, 83, 'Mary W', NULL, 3915, 40, NULL, 3875, 'Mary W', 'NAZARETH HOSPITAL', NULL, '2024-10-11 05:49:53', '2024-10-11 05:49:53'),
(102, 250, 'Mary W', NULL, 6, 5, NULL, 1, 'Mary W', 'For Office Use (Gents Washroom)', NULL, '2024-10-11 06:47:36', '2024-10-11 06:47:36'),
(103, 247, 'Mary W', NULL, 608, NULL, 3, 611, 'Mary W', 'FROM MMYTO', NULL, '2024-10-11 06:51:20', '2024-10-11 06:51:20'),
(104, 833, 'Patricia N', NULL, 68, NULL, 3, 71, 'Patricia N', 'MAKONGENI H/C\r\n-Exchanged with 1 micropipette 2-20ul,1 micro pipette 10-100ul and 1 packet of yellow tips.', NULL, '2024-10-11 06:58:17', '2024-10-11 06:58:17'),
(105, 881, 'Patricia N', NULL, 66980, NULL, 1000, 67980, 'Patricia N', 'MAKONGENI H/C\r\n-Exchanged with 1 micropipette 2-20ul,1 micro pipette 10-100ul and 1 packet of yellow tips.', NULL, '2024-10-11 06:59:08', '2024-10-11 06:59:08'),
(106, 1154, 'Mary W', NULL, 2, 2, NULL, 0, 'Mary W', 'CHESONGOCH HEALTH CENTRE', NULL, '2024-10-11 09:08:47', '2024-10-11 09:08:47'),
(107, 861, 'Patricia N', NULL, 63, 3, NULL, 60, 'Patricia N', 'For Office Use', NULL, '2024-10-11 09:34:18', '2024-10-11 09:34:18'),
(108, 24, 'Patricia N', NULL, 2, 1, NULL, 1, 'Patricia N', 'PATHCARE KENYA LIMITED', NULL, '2024-10-11 09:37:30', '2024-10-11 09:37:30'),
(109, 24, 'Patricia N', NULL, 2, 1, NULL, 1, 'Patricia N', 'PATHCARE KENYA LIMITED', NULL, '2024-10-11 09:37:33', '2024-10-11 09:37:33'),
(110, 26, 'Patricia N', NULL, 12, 1, NULL, 11, 'Patricia N', 'PATHCARE KENYA LIMITED', NULL, '2024-10-11 09:39:37', '2024-10-11 09:39:37'),
(111, 369, 'Patricia N', NULL, 17, 1, NULL, 16, 'Patricia N', 'PATHCARE KENYA LIMITED', NULL, '2024-10-11 09:40:34', '2024-10-11 09:40:34'),
(112, 366, 'Patricia N', NULL, 15, 1, NULL, 14, 'Patricia N', 'PATHCARE KENYA LIMITED', NULL, '2024-10-11 09:41:18', '2024-10-11 09:41:18'),
(113, 363, 'Patricia N', NULL, 20, 1, NULL, 19, 'Patricia N', 'PATHCARE KENYA LIMITED', NULL, '2024-10-11 09:42:42', '2024-10-11 09:42:42'),
(114, 587, 'Patricia N', NULL, 18, 1, NULL, 17, 'Patricia N', 'PATHCARE KENYA LIMITED', NULL, '2024-10-11 09:43:43', '2024-10-11 09:43:43'),
(115, 183, 'Patricia N', NULL, 1831, 1, NULL, 1830, 'Patricia N', '3rd PARK HOSPITAL', NULL, '2024-10-11 09:47:12', '2024-10-11 09:47:12'),
(116, 60, 'Patricia N', NULL, 163, 5, NULL, 158, 'Patricia N', '3rd PARK HOSPITAL', NULL, '2024-10-11 09:49:47', '2024-10-11 09:49:47'),
(117, 542, 'Mary W', NULL, 24, 10, NULL, 14, 'Mary W', 'UOW - HOPE STUDY', NULL, '2024-10-11 10:00:21', '2024-10-11 10:00:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
