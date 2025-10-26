-- Migration to hash existing plain text passwords
-- Run this in phpMyAdmin if you have existing users with plain text passwords

-- This script will hash common plain text passwords
-- You may need to manually update other passwords or ask users to reset them

-- Hash admin password (admin123)
UPDATE users SET password = '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm' WHERE username = 'admin' AND password = 'admin123';

-- Hash common test passwords (password)
UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE password = 'password';

-- Hash other common passwords
UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE password = '123456';
UPDATE users SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE password = 'test';

-- Note: For production, you should:
-- 1. Force users to reset their passwords
-- 2. Or manually hash each password using Laravel's Hash::make() function
-- 3. The hashes above are for 'password' - update as needed