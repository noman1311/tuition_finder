-- Fix Admin Login Script
-- Run these commands in phpMyAdmin to fix admin login issues

-- Step 1: Check current admin user status
SELECT user_id, username, email, role, password FROM users WHERE username = 'admin';

-- Step 2: Delete existing admin user if it exists (to start fresh)
DELETE FROM users WHERE username = 'admin';

-- Step 3: Create fresh admin user with plain text password (will be hashed by Laravel)
INSERT INTO users (username, email, password, role, phone, created_at, updated_at) VALUES
('admin', 'admin@tuitionfinder.com', 'admin123', 'admin', NULL, NOW(), NOW());

-- Step 4: Verify the admin user was created correctly
SELECT user_id, username, email, role, password FROM users WHERE username = 'admin';

-- Step 5: Test login credentials:
-- Username: admin
-- Password: admin123

-- Note: The password will be automatically hashed by Laravel on first login