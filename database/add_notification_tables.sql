-- =====================================================
-- SAFE NOTIFICATION SYSTEM SETUP
-- =====================================================
-- This script ADDS NEW TABLES without affecting existing ones
-- Safe to run on existing database with data
-- =====================================================

-- Start transaction for safety
START TRANSACTION;

-- =====================================================
-- 1. CHECK IF TABLES EXIST BEFORE CREATING
-- =====================================================

-- Create Notifications Table (Laravel standard)
-- This table stores in-app notifications for users
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL COMMENT 'Polymorphic type (usually App\\Models\\User)',
  `notifiable_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Polymorphic ID (user ID)',
  `data` text NOT NULL COMMENT 'JSON notification data',
  `read_at` timestamp NULL DEFAULT NULL COMMENT 'When notification was read',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User in-app notifications';

-- Create Notification Preferences Table
-- This stores user notification preferences
CREATE TABLE IF NOT EXISTS `notification_preferences` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `email_enabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Enable email notifications',
  `sms_enabled` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Enable SMS notifications',
  `in_app_enabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Enable in-app notifications',
  `preferences` json DEFAULT NULL COMMENT 'Additional notification preferences',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `notification_preferences_user_id_unique` (`user_id`),
  KEY `notification_preferences_user_id_index` (`user_id`),
  CONSTRAINT `notification_preferences_user_id_fk` 
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) 
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='User notification preferences';

-- =====================================================
-- 2. POPULATE DEFAULT DATA
-- =====================================================

-- Insert default notification preferences for all existing users
-- Only insert if not already exists
INSERT IGNORE INTO `notification_preferences` 
  (`user_id`, `email_enabled`, `sms_enabled`, `in_app_enabled`, `created_at`, `updated_at`)
SELECT 
  `id` as user_id, 
  1 as email_enabled,    -- Enable email by default
  0 as sms_enabled,      -- Disable SMS by default
  1 as in_app_enabled,   -- Enable in-app by default
  NOW() as created_at,
  NOW() as updated_at
FROM `users`
WHERE NOT EXISTS (
  SELECT 1 FROM `notification_preferences` 
  WHERE `notification_preferences`.`user_id` = `users`.`id`
);

-- =====================================================
-- 3. VERIFICATION QUERIES (Optional - uncomment to check)
-- =====================================================

-- Check if tables were created successfully
-- SELECT TABLE_NAME, TABLE_ROWS, CREATE_TIME 
-- FROM information_schema.TABLES 
-- WHERE TABLE_SCHEMA = 'sarana' 
-- AND TABLE_NAME IN ('notifications', 'notification_preferences');

-- Check notification preferences per user
-- SELECT u.id, u.name, u.email, np.email_enabled, np.sms_enabled, np.in_app_enabled
-- FROM users u
-- LEFT JOIN notification_preferences np ON u.id = np.user_id
-- ORDER BY u.id;

-- Count existing users vs preferences
-- SELECT 
--   (SELECT COUNT(*) FROM users) as total_users,
--   (SELECT COUNT(*) FROM notification_preferences) as users_with_preferences;

-- =====================================================
-- 4. ROLLBACK SCRIPT (Use ONLY if needed)
-- =====================================================
-- Uncomment these lines if you need to remove the notification tables:
-- DROP TABLE IF EXISTS `notification_preferences`;
-- DROP TABLE IF EXISTS `notifications`;

-- =====================================================
-- COMMIT THE TRANSACTION
-- =====================================================

COMMIT;

-- =====================================================
-- SUCCESS MESSAGE
-- =====================================================

SELECT '✅ Notification tables created successfully!' as status,
       'Existing tables were preserved' as message,
       NOW() as timestamp;