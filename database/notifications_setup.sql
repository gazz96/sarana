-- =====================================================
-- NOTIFICATION SYSTEM SETUP MANUAL SQL
-- =====================================================
-- Run this script manually after reviewing it
-- This will add notification tables to your existing database
-- =====================================================

-- 1. Create Notifications Table (Laravel standard)
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Create Notification Preferences Table
CREATE TABLE IF NOT EXISTS `notification_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `sms_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `in_app_enabled` tinyint(1) NOT NULL DEFAULT 1,
  `preferences` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notification_preferences_user_id_unique` (`user_id`),
  KEY `notification_preferences_user_id_foreign` (`user_id`),
  CONSTRAINT `notification_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Create Notification Jobs Table (for queue processing)
CREATE TABLE IF NOT EXISTS `notification_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `notification_type` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `data` json DEFAULT NULL,
  `status` enum('pending','sent','failed') DEFAULT 'pending',
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_jobs_user_id_index` (`user_id`),
  KEY `notification_jobs_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Insert default notification preferences for existing users
INSERT IGNORE INTO `notification_preferences` (`user_id`, `email_enabled`, `sms_enabled`, `in_app_enabled`, `created_at`, `updated_at`)
SELECT 
  `id` as user_id, 
  1 as email_enabled, 
  0 as sms_enabled, 
  1 as in_app_enabled,
  NOW() as created_at,
  NOW() as updated_at
FROM `users`
WHERE NOT EXISTS (
  SELECT 1 FROM `notification_preferences` WHERE `notification_preferences`.`user_id` = `users`.`id`
);

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Check if tables were created successfully
-- SELECT TABLE_NAME, TABLE_ROWS 
-- FROM information_schema.TABLES 
-- WHERE TABLE_SCHEMA = 'sarana' 
-- AND TABLE_NAME IN ('notifications', 'notification_preferences', 'notification_jobs');

-- Check notification preferences per user
-- SELECT u.name, u.email, np.email_enabled, np.sms_enabled, np.in_app_enabled
-- FROM users u
-- LEFT JOIN notification_preferences np ON u.id = np.user_id;

-- =====================================================
-- ROLLBACK SCRIPT (use only if needed)
-- =====================================================
-- DROP TABLE IF EXISTS `notification_jobs`;
-- DROP TABLE IF EXISTS `notification_preferences`;
-- DROP TABLE IF EXISTS `notifications`;