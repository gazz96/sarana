-- -------------------------------------------------------------
-- TablePlus 6.0.4(556)
--
-- https://tableplus.com/
--
-- Database: sarana
-- Generation Time: 2024-05-28 19:41:26.4550
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `connection` text COLLATE utf8_unicode_ci NOT NULL,
  `queue` text COLLATE utf8_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `goods` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `location_id` bigint DEFAULT NULL,
  `merk` varchar(255) DEFAULT NULL,
  `detail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `locations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `key` varchar(255) DEFAULT NULL,
  `settings` text,
  `autoload` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `problem_approvals` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `problem_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `problem_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `problem_id` bigint NOT NULL,
  `good_id` bigint NOT NULL,
  `issue` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `price` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` int NOT NULL,
  `note` text,
  PRIMARY KEY (`id`),
  KEY `problem_id` (`problem_id`),
  CONSTRAINT `problem_items_ibfk_1` FOREIGN KEY (`problem_id`) REFERENCES `problems` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `problems` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `status` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `date` date NOT NULL,
  `code` varchar(255) DEFAULT NULL,
  `note` text,
  `user_technician_id` bigint DEFAULT NULL,
  `user_management_id` bigint DEFAULT NULL,
  `user_finance_id` bigint DEFAULT NULL,
  `admin_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `user_roles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` bigint NOT NULL,
  `role_id` bigint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role_id` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `goods` (`id`, `name`, `location_id`, `merk`, `detail`, `status`, `created_at`, `updated_at`, `code`) VALUES
(2, 'barang', 1, 'barang', 'barang', '0', '2024-05-09 07:40:16', '2024-05-20 09:47:07', 'BRG-2'),
(3, 'barang', 1, 'barang', 'barang', '1', '2024-05-09 07:41:03', '2024-05-20 09:43:07', 'BRG-1'),
(4, 'barang BARU', 1, 'test', 'barang', '1', '2024-05-09 07:50:45', '2024-05-20 09:47:44', 'BRG-3');

INSERT INTO `locations` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Gudang', NULL, '2024-05-09 12:01:02'),
(2, 'Ruang TU', '2024-05-09 08:32:13', '2024-05-09 08:32:13');

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1);

INSERT INTO `options` (`id`, `key`, `settings`, `autoload`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'ini adalah aplikasi', 0, '2024-05-20 10:41:01', '2024-05-20 10:41:06'),
(2, 'address_instance', 'jl. ileng taman permata hijau', 0, '2024-05-20 10:52:05', '2024-05-20 10:52:05'),
(3, 'app_logo', 'settings/boRfIwYjQHxlLtMLuw2pHWTFsrud1csRZy7CmWsH.png', 0, '2024-05-20 11:10:27', '2024-05-25 14:13:33');

INSERT INTO `problem_items` (`id`, `problem_id`, `good_id`, `issue`, `price`, `created_at`, `updated_at`, `status`, `note`) VALUES
(9, 16, 2, 'ini ada masalah baru', 150000, '2024-05-12 14:07:50', '2024-05-12 14:07:50', 0, 'ini adalah note'),
(10, 16, 4, 'ini adalh masalah di barang baru', 1500000, '2024-05-12 14:08:11', '2024-05-12 14:08:11', 0, 'ini adalah note di barang baru'),
(11, 18, 4, 'barang baru', 1500000, '2024-05-25 09:52:15', '2024-05-25 11:42:22', 0, 'ini adalah note'),
(12, 19, 4, 'rusak', 1500000, '2024-05-25 12:37:12', '2024-05-25 13:09:05', 0, 'ganti baru');

INSERT INTO `problems` (`id`, `user_id`, `status`, `created_at`, `updated_at`, `date`, `code`, `note`, `user_technician_id`, `user_management_id`, `user_finance_id`, `admin_id`) VALUES
(16, 1, 0, '2024-05-12 13:30:00', '2024-05-12 13:30:00', '2024-05-12', 'PRB/20240512/0', NULL, NULL, NULL, NULL, NULL),
(17, 5, 0, '2024-05-25 09:52:02', '2024-05-25 09:52:02', '2024-05-25', 'PRB/20240525/1', NULL, NULL, NULL, NULL, NULL),
(18, 5, 0, '2024-05-25 09:52:15', '2024-05-25 11:48:27', '2024-05-25', 'PRB/20240525/2', NULL, NULL, NULL, NULL, NULL),
(19, 5, 3, '2024-05-25 12:37:12', '2024-05-25 13:53:53', '2024-05-25', 'PRB/20240525/3', NULL, 4, 7, 6, 2);

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', NULL, NULL),
(2, 'guru', NULL, NULL),
(3, 'teknisi', NULL, NULL),
(4, 'keuangan', NULL, NULL),
(5, 'lembaga', NULL, NULL),
(6, 'super user', NULL, NULL);

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role_id`) VALUES
(1, 'bagas', 'bagas@gmail.com', NULL, '$2y$10$SW5F6YFdEWKM63fTNdH/wehlD8WrIZoZ1n2MvOef9lQWysRwtD0la', NULL, '2024-05-09 09:54:42', '2024-05-09 10:00:53', 6),
(2, 'adit', 'adit@gmail.com', NULL, '$2y$10$20DTg8CZs3IJfqKu7CV9.u17ZJEozGDLi8b3UndhcB/1kqTPyan86', NULL, '2024-05-09 09:56:44', '2024-05-25 12:45:14', 1),
(3, 'fadlan', 'fadlan@gmail.com', NULL, '$2y$10$Yh/KqZijSzv2NphD8y5FeuMkrazvx4TOJQaYD.1FwH.DcU3B/JeGW', NULL, '2024-05-09 09:56:58', '2024-05-09 09:56:58', NULL),
(4, 'teknisi', 'teknisi@gmail.com', NULL, '$2y$10$Y8uNWWVgmfDm7A7SHAc34.y9j/0H5XPP9D4N235JS/0sBRAM6WCVO', NULL, '2024-05-21 10:19:34', '2024-05-21 10:19:34', 3),
(5, 'guru', 'guru@gmail.com', NULL, '$2y$10$neZxxhH1Y6xC17U0dF1csuy1T/gzLWhkE5lI8Av2FmL5e6FYwn6iO', NULL, '2024-05-25 08:53:10', '2024-05-25 08:53:10', 2),
(6, 'keuangan', 'keuangan@gmail.com', NULL, '$2y$10$seuOkNJZ5lH9qxqhkxjq4e1F9Ecdt9.dpx1hzZ/EXs9L90m0D9XrG', NULL, '2024-05-25 12:11:08', '2024-05-25 12:11:08', 4),
(7, 'lembaga', 'lembaga@gmail.com', NULL, '$2y$10$VL7zgorxvv378u92tP6VoevV4XiWz0/jVB0CxF1UUPZKZwY4viAli', NULL, '2024-05-25 13:14:37', '2024-05-25 13:14:37', 5);



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;