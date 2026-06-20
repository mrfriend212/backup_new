-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 20, 2026 at 07:28 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `backup_new`
--

-- --------------------------------------------------------

--
-- Table structure for table `backup_logs`
--

CREATE TABLE `backup_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `sftp_account_id` bigint UNSIGNED NOT NULL,
  `status` enum('success','failed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_size` bigint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_06_20_075209_create_systems_table', 1),
(2, '2026_06_20_075210_create_units_table', 1),
(3, '2026_06_20_075211_create_sftp_accounts', 1),
(4, '2026_06_20_081650_create_backup_logs', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('0SrbtNjyP9I5gmWILZEZ5tX9SBz0YmZUkhtUQsbk', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ2cWJrTWtDNVNocjl3WXFYZzRpdjk2elVsVklYMEVtYmV6MWI0R3VvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYW5lbFwvYWRtaW5cL2RhaGJvYXJkIiwicm91dGUiOiJwYW5lbC5hZG1pbi5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMCJ9LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1781893967),
('giCVSR01zBxEvEU2gvYHSX1E1P40cVgUIWiTQ4Sf', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiI2N2tWbk82NGlsM0M5ZWh2U1VVTVVCcEYycUJUMzkyR1d0ODdSbTlCIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1781946418),
('MozG82qzAIB9Klnfwz2cOovB9thrj670kz3XmJLK', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJvdDJHQ0tSM29wTFVsWHUxcEo3TFA0N1RBcFMyRjVmcTRZcTRkbXRvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9sb2dpbiIsInJvdXRlIjoic2hvd0xvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=', 1781969847),
('NRpejRvJx5QHxPXywHorvklfHwRn8ej7p8DmPLeD', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ6aVRiYk5URGNXY0R4WWlEWVYyemxPQTdZTkJJa1BSRnhzWDhIVzc4IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYW5lbFwvYWRtaW5cL2RhaGJvYXJkIiwicm91dGUiOiJwYW5lbC5hZG1pbi5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1781946427),
('TEM3iOnQVrXBiend1ZGHshE7jqIuz0dOcdCzH9Fj', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJEMWdiNkdSOTZsa0NqQVo0MGxibEFlOHE0TFJiSTcyVjdSZ2lMRlU3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYW5lbFwvYWRtaW5cL2RhaGJvYXJkIiwicm91dGUiOiJwYW5lbC5hZG1pbi5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1781977531),
('uaPB2oyT1Y0ZR1p4POPlVfLukFNp8yOpIXFkH8oX', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJ6eEF2VDRZWWVLdnJMZEhZZnhIc2pybzNac2FuTlF0NVljQU9KbnFrIiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvMTI3LjAuMC4xOjgwMDAifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9wYW5lbFwvYWRtaW5cL2RhaGJvYXJkIiwicm91dGUiOiJwYW5lbC5hZG1pbi5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MX0=', 1781929749);

-- --------------------------------------------------------

--
-- Table structure for table `sftp_accounts`
--

CREATE TABLE `sftp_accounts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `unit_id` bigint UNSIGNED NOT NULL,
  `system_id` bigint UNSIGNED NOT NULL,
  `database_type` enum('mysql','pgsql','sqlite','sqlsrv') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sqlsrv',
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` text COLLATE utf8mb4_unicode_ci,
  `host` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '10.10.10.9',
  `port` int NOT NULL DEFAULT '32',
  `root_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `days_of_week` json NOT NULL,
  `private_key` text COLLATE utf8mb4_unicode_ci,
  `public_key` text COLLATE utf8mb4_unicode_ci,
  `passphrase` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sftp_accounts`
--

INSERT INTO `sftp_accounts` (`id`, `user_id`, `unit_id`, `system_id`, `database_type`, `username`, `password`, `host`, `port`, `root_path`, `days_of_week`, `private_key`, `public_key`, `passphrase`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 1, 1, 'sqlsrv', 'h_meybod', 'h09mD@11)277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Meybod\\', '\"[6,2]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(2, 3, 2, 1, 'sqlsrv', 'h_mehrab', 'h09Mb@12*277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Mehrab\\', '\"[6,2]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(3, 4, 3, 1, 'sqlsrv', 'h_mehriz', 'h09MZ@13#277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Mehriz\\', '\"[6,2]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(4, 5, 4, 1, 'sqlsrv', 'h_herat', 'h09Ht@13%313', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Herat\\', '\"[0,3]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(5, 6, 5, 1, 'sqlsrv', 'h_rahnemoun', 'h09rN@11)277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Rahnemoun\\', '\"[0,3]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(6, 7, 6, 1, 'sqlsrv', 'h_afshar', 'h09AR@12*277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Afshar\\', '\"[0,3]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(7, 8, 7, 1, 'sqlsrv', 'h_bahabad', 'h09bD@13#277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Bahabad\\', '\"[0,3]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(8, 9, 8, 1, 'sqlsrv', 'h_beheshti', 'h09Bi@13%313', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Beheshti\\', '\"[1,4]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(9, 10, 9, 1, 'sqlsrv', 'h_ardekan', 'h09AN@11)277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Ardekan\\', '\"[1,4]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(10, 11, 10, 1, 'sqlsrv', 'h_bafq', 'h09Bq@12*277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Bafq\\', '\"[1,4]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(11, 12, 11, 1, 'sqlsrv', 'h_sadoughi', 'H09si@13#277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Sadoughi\\', '\"[6,2]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(12, 13, 12, 1, 'sqlsrv', 'h_abarkouh', 'H09ah@13%313', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Abarkouh\\', '\"[1,4]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(13, 14, 13, 1, 'sqlsrv', 'h_ravanpezeshki', 'H09Ri@11)277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Ravanpezeshki\\', '\"[1,4]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(14, 15, 14, 1, 'sqlsrv', 'h_ashkezar', 'H09aR@12*277', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Ashkezar\\', '\"[1,4]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL),
(15, 16, 15, 1, 'sqlsrv', 'h_nabarvari', 'H09NB@13%313', '10.10.10.9', 32, 'G:\\Backup\\Ssu_Bk\\usr\\HIS\\Nabarvari\\HIS\\', '\"[0,3]\"', NULL, NULL, NULL, 1, '2026-06-20 15:51:41', '2026-06-20 15:51:41', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `systems`
--

CREATE TABLE `systems` (
  `id` bigint UNSIGNED NOT NULL,
  `name_fa` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `systems`
--

INSERT INTO `systems` (`id`, `name_fa`, `name_en`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'سیستم مدیریت اطلاعات بیمارستان', 'HIS', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'بیمارستان امام جعفر صادق میبد', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(2, 'بیمارستان شهدای محراب یزد', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(3, 'بیمارستان فاطمه الزهرا مهریز', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(4, 'بیمارستان آیت الله خاتمی خاتم(هرات)', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(5, 'بیمارستان شهید دکتر رهنمون یزد', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(6, 'بیمارستان محمد صادق افشار یزد', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(7, 'بیمارستان حکیم بهاباد', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(8, 'بیمارستان شهید بهشتی تفت', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(9, 'بیمارستان ضیائی اردکان', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(10, 'بیمارستان ولیعصر بافق', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(11, 'بیمارستان شهید صدوقی یزد', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(12, 'بیمارستان خاتم النبیاء ابرکوه', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(13, 'بیمارستان روان پزشکی تفت', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(14, 'بیمارستان حضرت زینب(س) اشکذر', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL),
(15, 'مرکز تحقیقات درمانی ناباروری یزد', '2026-06-20 15:28:55', '2026-06-20 15:28:55', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `family` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_type` enum('admin','manager','user') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `status` enum('active','deactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `family`, `username`, `password`, `user_type`, `status`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'حسین', 'دوست حسینی', '4420179910', '$2y$12$kZ7rN8W.J0RiB6WiXECfLeeIUFB2Pk5GVcPjL.vyunWK2tD.c7ak.', 'admin', 'active', NULL, '2026-06-16 01:41:02', '2026-06-16 01:41:02', NULL),
(2, 'فاطمه', 'سیفی', '4449280261', '$2y$12$b0L6VaOEfX.o14LjIT6Hi.tFS/v1w6ITPUX/6XxDgeWcdoouj/mHW', 'user', 'active', NULL, '2026-06-20 12:06:31', '2026-06-20 12:06:31', NULL),
(3, 'محسن', 'کبیری', '4420077522', '$2y$12$zST8/bSAOueuxbq/Yno56.fAt6C3AO1mvPYl2wEZWLKGjY91D2.Ym', 'user', 'active', NULL, '2026-06-20 12:06:32', '2026-06-20 12:06:32', NULL),
(4, 'میثم', 'زارع', '4469931632', '$2y$12$oS2.g1ke50kRD2DdwHxDremsrS6A6gQm0D22BVkh9/KmEAJGjEXQy', 'user', 'active', NULL, '2026-06-20 12:06:32', '2026-06-20 12:06:32', NULL),
(5, 'میلاد', 'ملایی', '5439979018', '$2y$12$uIvQlw/ThPQ8VYlqJA5A.ezTMe7XXvOJc3cbTvgnR8KRdHyW.76v6', 'user', 'active', NULL, '2026-06-20 12:06:32', '2026-06-20 12:06:32', NULL),
(6, 'مجید', 'کریمی', '4430699836', '$2y$12$C0qtBJNIJSWglqbdcbifPOF5g7fm6lRFvvoU1264b3H64n/G7i9Sa', 'user', 'active', NULL, '2026-06-20 12:06:32', '2026-06-20 12:06:32', NULL),
(7, 'محمدرضا', 'رفیعی', '4430600813', '$2y$12$PXQAHY3gtxmzEvzs0ASGCOZ3L6b37Zqow3RRCset9D5tuTd2QGBVi', 'user', 'active', NULL, '2026-06-20 12:06:33', '2026-06-20 12:06:33', NULL),
(8, 'علیرضا', 'نبی زاده', '5619881962', '$2y$12$qCF.8fwoUn/f6TmFL6v/DOJTr8lvY0mv0ivFGYbSBhZbTMp5N7Oc6', 'user', 'active', NULL, '2026-06-20 12:06:33', '2026-06-20 12:06:33', NULL),
(9, 'علیرضا', 'فلاح', '4450100204', '$2y$12$LIqVuYYXjioD2swttfX.0.jkC2KBxtoGItrEKKu.eid5XfBZE9cWu', 'user', 'active', NULL, '2026-06-20 12:06:33', '2026-06-20 12:06:33', NULL),
(10, 'یحیی', 'جماعتی', '4449649516', '$2y$12$a6.o.oWmunJCS3eStmudPesYHdXJwncccvaBqaq/YwxUWYqfDPVze', 'user', 'active', NULL, '2026-06-20 12:06:33', '2026-06-20 12:06:33', NULL),
(11, 'مریم السادات', 'رشیدی', '0010458107', '$2y$12$nbfdQOla6BRzzy/9R.2jgOm1l8GK0UOezdIrrBYJvaWb/nrgFkCAG', 'user', 'active', NULL, '2026-06-20 12:06:33', '2026-06-20 12:06:33', NULL),
(12, 'فرشته', 'تقوی', '4432775866', '$2y$12$ddfi6f7T0umzow05et490uMRJzJhJhkEMgwIXK.U4m2FtNtYEmGCK', 'user', 'active', NULL, '2026-06-20 12:06:34', '2026-06-20 12:06:34', NULL),
(13, 'راضیه', 'فلاح زاده', '5030019881', '$2y$12$RMKv7VPZtoVS9.DlrR6xCOeBHduPUoOR/Br22qov37ztgwsURJGcK', 'user', 'active', NULL, '2026-06-20 12:06:34', '2026-06-20 12:06:34', NULL),
(14, 'علی', 'کمالی', '4449656008', '$2y$12$mRR0SZ6aCSU9oQMf77DcL.Jrtoe4Gmym3w1zOzYZJU.lwm.IFBKOy', 'user', 'active', NULL, '2026-06-20 12:06:34', '2026-06-20 12:06:34', NULL),
(15, 'علی', 'تقوی', '4433140813', '$2y$12$WnLkmJuoa4dh3v9xufEIqOkOIxd5xHKyCnYy76JykQrTySLaXpcjq', 'user', 'active', NULL, '2026-06-20 12:06:34', '2026-06-20 12:06:34', NULL),
(16, 'محدثه', 'زارع', '4431676244', '$2y$12$nXNxmfFSZhQ2zMEynj8kfeS9LUZLXUMPspHlgPetz5L7sF9qpMgrK', 'user', 'active', NULL, '2026-06-20 12:06:35', '2026-06-20 12:06:35', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `backup_logs`
--
ALTER TABLE `backup_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `backup_logs_sftp_account_id_foreign` (`sftp_account_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `sftp_accounts`
--
ALTER TABLE `sftp_accounts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sftp_accounts_user_id_foreign` (`user_id`),
  ADD KEY `sftp_accounts_unit_id_foreign` (`unit_id`),
  ADD KEY `sftp_accounts_system_id_foreign` (`system_id`);

--
-- Indexes for table `systems`
--
ALTER TABLE `systems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `backup_logs`
--
ALTER TABLE `backup_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `sftp_accounts`
--
ALTER TABLE `sftp_accounts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `systems`
--
ALTER TABLE `systems`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `backup_logs`
--
ALTER TABLE `backup_logs`
  ADD CONSTRAINT `backup_logs_sftp_account_id_foreign` FOREIGN KEY (`sftp_account_id`) REFERENCES `sftp_accounts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sftp_accounts`
--
ALTER TABLE `sftp_accounts`
  ADD CONSTRAINT `sftp_accounts_system_id_foreign` FOREIGN KEY (`system_id`) REFERENCES `systems` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sftp_accounts_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sftp_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
