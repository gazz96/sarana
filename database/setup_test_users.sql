-- =====================================================
-- SETUP TEST USERS UNTUK NOTIFICATION TESTING
-- =====================================================
-- Password untuk semua test users: password123
-- =====================================================

START TRANSACTION;

-- Update passwords untuk semua existing users (hashed for Laravel)
-- Password: password123
UPDATE users SET 
    password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE id IN (1,2,3,4,5,6,7);

-- Ensure semua users punya roles yang proper
UPDATE users SET role_id = 1 WHERE name = 'bagas' AND role_id = 6; -- Change super user to admin
UPDATE users SET role_id = 1 WHERE name = 'adit'; -- Ensure admin
UPDATE users SET role_id = 2 WHERE name = 'guru'; -- Ensure guru
UPDATE users SET role_id = 3 WHERE name = 'teknisi'; -- Ensure teknisi  
UPDATE users SET role_id = 4 WHERE name = 'keuangan'; -- Ensure keuangan
UPDATE users SET role_id = 5 WHERE name = 'lembaga'; -- Ensure lembaga

-- Fadlan sebagai admin tambahan
UPDATE users SET role_id = 1 WHERE name = 'fadlan';

COMMIT;

-- =====================================================
-- VERIFICATION - SHOW TEST USERS
-- =====================================================

SELECT '✅ Test Users Setup Complete!' as status;
SELECT 'All users can login with password: password123' as credentials;

SELECT 
    u.id,
    u.name,
    u.email,
    r.name as role,
    'password123' as password
FROM users u
LEFT JOIN roles r ON u.role_id = r.id
WHERE u.id IN (1,2,3,4,5,6,7)
ORDER BY r.name, u.name;