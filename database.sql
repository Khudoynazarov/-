CREATE DATABASE tutor_search;
USE tutor_search;

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255)
);

CREATE TABLE tutors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    photo VARCHAR(255),
    experience INT,
    education VARCHAR(255),
    price DECIMAL(10,2),
    description TEXT
);

CREATE TABLE tutor_subjects (
    tutor_id INT,
    subject_id INT,
    PRIMARY KEY (tutor_id, subject_id),
    FOREIGN KEY (tutor_id) REFERENCES tutors(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student'
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tutor_id INT,
    student_id INT,
    booking_date DATE,
    booking_time TIME,
    format ENUM('online', 'offline'),
    contact VARCHAR(255),
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tutor_id) REFERENCES tutors(id),
    FOREIGN KEY (student_id) REFERENCES users(id)
);

CREATE TABLE tutor_schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tutor_id INT,
    schedule_date DATE,
    schedule_time TIME,
    available BOOLEAN DEFAULT true,
    FOREIGN KEY (tutor_id) REFERENCES tutors(id)
);