-- Adminer 4.12 MySQL 8.0.30 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `attendences`;
CREATE TABLE `attendences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `kantor_id` bigint unsigned NOT NULL,
  `schedule_latitude` double NOT NULL,
  `schedule_longitude` double NOT NULL,
  `schedule_start_time` time NOT NULL,
  `schedule_end_time` time NOT NULL,
  `start_latitude` double NOT NULL,
  `start_longitude` double NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `end_latitude` double DEFAULT NULL,
  `end_longitude` double DEFAULT NULL,
  `is_wfa` tinyint(1) NOT NULL DEFAULT '0',
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `attendences_user_id_foreign` (`user_id`),
  KEY `attendences_kantor_id_foreign` (`kantor_id`),
  CONSTRAINT `attendences_kantor_id_foreign` FOREIGN KEY (`kantor_id`) REFERENCES `kantors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `attendences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `attendences` (`id`, `user_id`, `kantor_id`, `schedule_latitude`, `schedule_longitude`, `schedule_start_time`, `schedule_end_time`, `start_latitude`, `start_longitude`, `start_time`, `end_time`, `created_at`, `updated_at`, `deleted_at`, `end_latitude`, `end_longitude`, `is_wfa`, `deskripsi`) VALUES
(1,	2,	1,	-7.9379100602436,	112.62552200667,	'08:00:00',	'16:00:00',	-7.9351236568123,	112.62340307245,	'07:15:34',	'16:20:12',	'2024-12-11 00:15:34',	'2024-12-11 09:20:12',	NULL,	-7.9351236568123,	112.62340307245,	0,	NULL),
(2,	3,	2,	-7.6678,	112.69946,	'08:00:00',	'16:00:00',	-7.6679108765432,	112.69938023456,	'07:45:10',	'16:10:25',	'2024-12-11 00:45:10',	'2024-12-11 09:10:25',	NULL,	-7.6679108765432,	112.69938023456,	0,	NULL),
(3,	4,	3,	-7.9379100602436,	112.62552200667,	'08:00:00',	'16:00:00',	-7.938123456789,	112.62554321098,	'07:30:45',	'16:30:30',	'2024-12-11 00:30:45',	'2024-12-11 09:30:30',	NULL,	-7.938123456789,	112.62554321098,	1,	NULL),
(4,	5,	4,	-7.6678,	112.69946,	'08:00:00',	'16:00:00',	-7.6678901234567,	112.69941234567,	'08:15:15',	'16:15:45',	'2024-12-11 00:40:15',	'2024-12-11 09:15:45',	NULL,	-7.6678901234567,	112.69941234567,	0,	NULL),
(5,	6,	1,	-7.9379100602436,	112.62552200667,	'08:00:00',	'16:00:00',	-7.9379123456789,	112.62551234567,	'07:55:22',	'16:05:33',	'2024-12-11 00:55:22',	'2024-12-11 09:05:33',	NULL,	-7.9379123456789,	112.62551234567,	1,	NULL),
(6,	7,	2,	-7.6678,	112.69946,	'08:00:00',	'16:00:00',	-7.667823456789,	112.69949012345,	'07:35:50',	'16:40:20',	'2024-12-11 00:35:50',	'2024-12-11 09:40:20',	NULL,	-7.667823456789,	112.69949012345,	0,	NULL),
(7,	8,	3,	-7.9379100602436,	112.62552200667,	'08:00:00',	'16:00:00',	-7.9356789123456,	112.62356789012,	'07:28:40',	'16:45:10',	'2024-12-11 00:28:40',	'2024-12-11 09:45:10',	NULL,	-7.9356789123456,	112.62356789012,	0,	NULL),
(8,	2,	1,	-7.9379100602436,	112.62552200667,	'08:00:00',	'16:00:00',	-7.9351236568123,	112.62340307245,	'07:15:34',	'16:20:12',	'2024-12-10 00:15:34',	'2024-12-10 09:20:12',	NULL,	-7.9351236568123,	112.62340307245,	0,	NULL),
(9,	3,	2,	-7.6678,	112.69946,	'08:00:00',	'16:00:00',	-7.6679108765432,	112.69938023456,	'07:45:10',	'16:10:25',	'2024-12-10 00:45:10',	'2024-12-10 09:10:25',	NULL,	-7.6679108765432,	112.69938023456,	0,	NULL),
(10,	4,	3,	-7.9379100602436,	112.62552200667,	'08:00:00',	'16:00:00',	-7.938123456789,	112.62554321098,	'07:30:45',	'16:30:30',	'2024-12-10 00:30:45',	'2024-12-10 09:30:30',	NULL,	-7.938123456789,	112.62554321098,	1,	NULL),
(11,	5,	4,	-7.6678,	112.69946,	'08:00:00',	'16:00:00',	-7.6678901234567,	112.69941234567,	'08:15:15',	'16:15:45',	'2024-12-10 00:40:15',	'2024-12-10 09:15:45',	NULL,	-7.6678901234567,	112.69941234567,	0,	NULL),
(12,	6,	1,	-7.9379100602436,	112.62552200667,	'08:00:00',	'16:00:00',	-7.9379123456789,	112.62551234567,	'07:55:22',	'16:05:33',	'2024-12-10 00:55:22',	'2024-12-10 09:05:33',	NULL,	-7.9379123456789,	112.62551234567,	1,	NULL),
(13,	7,	2,	-7.6678,	112.69946,	'08:00:00',	'16:00:00',	-7.667823456789,	112.69949012345,	'07:35:50',	'16:40:20',	'2024-12-10 00:35:50',	'2024-12-10 09:40:20',	NULL,	-7.667823456789,	112.69949012345,	0,	NULL),
(14,	8,	3,	-7.9379100602436,	112.62552200667,	'08:00:00',	'16:00:00',	-7.9356789123456,	112.62356789012,	'07:28:40',	'16:45:10',	'2024-12-10 00:28:40',	'2024-12-10 09:45:10',	NULL,	-7.9356789123456,	112.62356789012,	0,	NULL);

DROP TABLE IF EXISTS `cutis`;
CREATE TABLE `cutis` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `jumlah_hari` int DEFAULT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_by_hrd` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by_leader` tinyint(1) NOT NULL DEFAULT '0',
  `approved_by_direksi` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `approval_by_leader_id` int DEFAULT NULL,
  `approval_by_hrd_id` int DEFAULT NULL,
  `approval_by_direksi_id` int DEFAULT NULL,
  `path_cuti_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cutis_user_id_foreign` (`user_id`),
  CONSTRAINT `cutis_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `profiles`;
CREATE TABLE `profiles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `no_hp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `agama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` bigint unsigned DEFAULT NULL,
  `city_id` bigint unsigned DEFAULT NULL,
  `district_id` bigint unsigned DEFAULT NULL,
  `subdistrict_id` bigint unsigned DEFAULT NULL,
  `ijasah_terakhir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `divisi_id` bigint unsigned NOT NULL,
  `jabatan_id` bigint unsigned NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `kantor_id` bigint unsigned NOT NULL,
  `foto_ktp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `signature` text COLLATE utf8mb4_unicode_ci,
  `profile_pic` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `profiles_email_unique` (`email`),
  UNIQUE KEY `profiles_nik_unique` (`nik`),
  KEY `profiles_user_id_foreign` (`user_id`),
  KEY `profiles_divisi_id_foreign` (`divisi_id`),
  KEY `profiles_jabatan_id_foreign` (`jabatan_id`),
  KEY `profiles_kantor_id_foreign` (`kantor_id`),
  CONSTRAINT `profiles_divisi_id_foreign` FOREIGN KEY (`divisi_id`) REFERENCES `divisis` (`id`) ON DELETE CASCADE,
  CONSTRAINT `profiles_jabatan_id_foreign` FOREIGN KEY (`jabatan_id`) REFERENCES `jabatans` (`id`) ON DELETE CASCADE,
  CONSTRAINT `profiles_kantor_id_foreign` FOREIGN KEY (`kantor_id`) REFERENCES `kantors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `schedules`;
CREATE TABLE `schedules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `shift_id` bigint unsigned NOT NULL,
  `kantor_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `is_wfa` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `schedules_user_id_unique` (`user_id`),
  KEY `schedules_shift_id_foreign` (`shift_id`),
  KEY `schedules_kantor_id_foreign` (`kantor_id`),
  CONSTRAINT `schedules_kantor_id_foreign` FOREIGN KEY (`kantor_id`) REFERENCES `kantors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedules_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `schedules_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `jatah_cuti` int NOT NULL DEFAULT '7',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2024-12-11 04:02:45
