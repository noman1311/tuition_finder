-- Migration to add admin role to users table
-- Run this in phpMyAdmin if you have an existing database

ALTER TABLE users MODIFY COLUMN role ENUM('student/parent', 'teacher', 'admin') NOT NULL DEFAULT 'student/parent';

-- Insert admin user if it doesn't exist
INSERT IGNORE INTO users (username, password, role, email, phone) 
VALUES ('admin', 'admin123', 'admin', 'admin@tuitionfinder.com', NULL);

-- Fix foreign key constraint in applications table if it exists
ALTER TABLE applications DROP FOREIGN KEY IF EXISTS applications_ibfk_1;
ALTER TABLE applications ADD CONSTRAINT applications_ibfk_1 FOREIGN KEY (offer_id) REFERENCES tuition_offers(offer_id) ON DELETE CASCADE;