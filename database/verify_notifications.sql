-- =====================================================
-- NOTIFICATION SYSTEM VERIFICATION SCRIPT
-- =====================================================
-- Run this after setup to verify everything is working
-- =====================================================

-- 1. Check if notification tables exist
SELECT 
    TABLE_NAME, 
    TABLE_ROWS, 
    CREATE_TIME,
    UPDATE_TIME
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'sarana' 
AND TABLE_NAME IN ('notifications', 'notification_preferences')
ORDER BY TABLE_NAME;

-- 2. Show table structures
DESCRIBE notifications;
DESCRIBE notification_preferences;

-- 3. Check existing users and their preferences
SELECT 
    u.id,
    u.name,
    u.email,
    u.email_verified_at,
    np.email_enabled,
    np.sms_enabled,
    np.in_app_enabled,
    np.preferences,
    np.created_at as preferences_created
FROM users u
LEFT JOIN notification_preferences np ON u.id = np.user_id
ORDER BY u.id
LIMIT 10;

-- 4. Count statistics
SELECT 
    (SELECT COUNT(*) FROM users) as total_users,
    (SELECT COUNT(*) FROM notification_preferences) as total_preferences,
    (SELECT COUNT(*) FROM notifications) as total_notifications,
    (SELECT COUNT(*) FROM problems) as total_problems,
    (SELECT COUNT(*) FROM goods) as total_goods;

-- 5. Check foreign key constraints
SELECT 
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'sarana'
AND TABLE_NAME = 'notification_preferences'
AND REFERENCED_TABLE_NAME IS NOT NULL;

-- 6. Test notification data format (if any notifications exist)
SELECT 
    id,
    type,
    notifiable_type,
    notifiable_id,
    JSON_EXTRACT(data, '$.event') as event,
    JSON_EXTRACT(data, '$.message') as message,
    read_at,
    created_at
FROM notifications
LIMIT 5;