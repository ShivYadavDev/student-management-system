CREATE DATABASE IF NOT EXISTS student_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE student_db;

CREATE TABLE IF NOT EXISTS students (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_name VARCHAR(100) NOT NULL,
    roll_no VARCHAR(30) NOT NULL UNIQUE,
    address VARCHAR(200) NOT NULL,
    mobile_no VARCHAR(15) NOT NULL,
    course VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Optional sample records for testing:
INSERT INTO students (student_name, roll_no, address, mobile_no, course)
VALUES
('Ram Yadav', 'BIT-001', 'Janakpur', '9800000001', 'BIT'),
('Sita Sharma', 'BIT-002', 'Dhanusha', '9800000002', 'BIT');
