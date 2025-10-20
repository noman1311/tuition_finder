-- TuitionFinder Database Schema for MySQL
-- Run these queries in phpMyAdmin to create the database

-- Create the database (optional - you can create it manually in phpMyAdmin)
CREATE DATABASE IF NOT EXISTS tuition_finder;
USE tuition_finder;

-- Users table
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student/parent', 'teacher') NOT NULL DEFAULT 'student',
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Teachers table
CREATE TABLE teachers (
    teacher_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    subject_expertise JSON NOT NULL,
    experience INT NOT NULL DEFAULT 0 COMMENT 'Years of experience',
    current_education_institution VARCHAR(200),
    location VARCHAR(100) NOT NULL,
    coins INT NOT NULL DEFAULT 0,
    preferred_type ENUM('online', 'offline', 'both') NOT NULL DEFAULT 'both',
    description TEXT,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Students table
CREATE TABLE students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    class_level VARCHAR(50) NOT NULL,
    location VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- Transactions table (Updated for wallet feature - simplified without approval system)
CREATE TABLE transactions (
    transaction_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    account_number VARCHAR(50) NULL COMMENT 'Payment account number - NULL for non-wallet transactions',
    amount DECIMAL(10,2) NOT NULL,
    type ENUM('bkash', 'rocket', 'banking', 'nagad') NOT NULL,
    description TEXT NULL,
    transaction_date DATE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Foreign key constraint
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    
    -- Indexes for better performance
    INDEX idx_user_id (user_id),
    INDEX idx_account_number (account_number),
    INDEX idx_created_at (created_at)
);

-- Tuition offers table
CREATE TABLE tuition_offers (
    offer_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT(20) UNSIGNED,
    subject VARCHAR(100) NOT NULL,
    class_level VARCHAR(50) NOT NULL,
    location VARCHAR(100) NOT NULL,
    salary DECIMAL(10,2) NOT NULL,
    status ENUM('open', 'closed', 'in_progress', 'completed') NOT NULL DEFAULT 'open',
    description TEXT,
    phone INT NOT NULL,
    type ENUM('assignment_help', 'tutoring') NOT NULL,
    preferred_type ENUM('online', 'offline', 'both') NOT NULL DEFAULT 'online',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE
);

-- Applications table
CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    offer_id INT NOT NULL,
    teacher_id INT NOT NULL,
    application_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'accepted', 'rejected', 'withdrawn') NOT NULL DEFAULT 'pending',
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (offer_id) REFERENCES tuition_offer(offer_id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(teacher_id) ON DELETE CASCADE,
    UNIQUE KEY unique_application (offer_id, teacher_id)
);



-- Insert sample data
INSERT INTO users (username, password, role, email, phone) VALUES
('john_student', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'john@example.com', '+1234567890'),
('sarah_teacher', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'sarah@example.com', '+1234567891'),
('mike_teacher', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'mike@example.com', '+1234567892');

INSERT INTO students (user_id, name, gender, class_level, location) VALUES
(1, 'John Smith', 'male', 'Grade 12', 'New York, NY');

INSERT INTO teachers (user_id, name, gender, subject_expertise, experience, current_education_institution, location, coins, preferred_type, description) VALUES
(2, 'Dr. Sarah Johnson', 'female', '["Mathematics", "Calculus", "Statistics"]', 10, 'Columbia University', 'New York, NY', 100, 'both', 'Experienced mathematics professor with 10+ years of teaching experience.'),
(3, 'Prof. Michael Chen', 'male', '["Computer Science", "Programming", "Python"]', 8, 'MIT', 'Boston, MA', 150, 'online', 'Software engineer turned educator. Expert in Python and web development.');

INSERT INTO tuition_offers (student_id, subject, class_level, location, salary, status, description, type, preferred_type) VALUES
(1, 'Mathematics', 'Grade 12', 'New York, NY', 50.00, 'open', 'Need help with calculus and algebra for final exams.', 'course_help', 'both');

INSERT INTO applications (offer_id, teacher_id, status, message) VALUES
(1, 1, 'pending', 'I have extensive experience in mathematics and can help you prepare for your final exams.');
