-- Create Database
CREATE DATABASE IF NOT EXISTS connect;
USE connect;

-- Admins Table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Services Table
CREATE TABLE IF NOT EXISTS services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('Individual', 'Company') NOT NULL,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Engineers Table
CREATE TABLE IF NOT EXISTS engineers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    mobile VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    certificate VARCHAR(255),
    location VARCHAR(255),
    experience INT,
    age INT,
    vehicle VARCHAR(50),
    assessment_score INT DEFAULT 0,
    has_given_assessment TINYINT(1) DEFAULT 0,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Junction table for Engineer Skills/Services
CREATE TABLE IF NOT EXISTS engineer_services (
    engineer_id INT,
    service_id INT,
    PRIMARY KEY (engineer_id, service_id),
    FOREIGN KEY (engineer_id) REFERENCES engineers(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Assessment Questions Table
CREATE TABLE IF NOT EXISTS assessment_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    option_a VARCHAR(255) NOT NULL,
    option_b VARCHAR(255) NOT NULL,
    option_c VARCHAR(255) NOT NULL,
    option_d VARCHAR(255) NOT NULL,
    correct_option ENUM('A', 'B', 'C', 'D') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Assessment Results Table
CREATE TABLE IF NOT EXISTS assessment_results (
    id INT AUTO_INCREMENT PRIMARY KEY,
    engineer_id INT NOT NULL,
    question_id INT NOT NULL,
    selected_answer ENUM('A', 'B', 'C', 'D'),
    is_correct TINYINT(1),
    FOREIGN KEY (engineer_id) REFERENCES engineers(id) ON DELETE CASCADE,
    FOREIGN KEY (question_id) REFERENCES assessment_questions(id) ON DELETE CASCADE
);

-- Bookings Table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    engineer_id INT NOT NULL,
    service_id INT NOT NULL,
    booking_date DATE NOT NULL,
    time_slot VARCHAR(50) NOT NULL,
    work_description TEXT,
    estimated_payout DECIMAL(10, 2),
    status ENUM('Pending', 'Confirmed', 'Rejected', 'Completed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (engineer_id) REFERENCES engineers(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Ratings Table
CREATE TABLE IF NOT EXISTS ratings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    user_id INT NOT NULL,
    engineer_id INT NOT NULL,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    feedback TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (engineer_id) REFERENCES engineers(id) ON DELETE CASCADE
);

-- Seed Default Admin
INSERT INTO admins (name, email, password) VALUES ('Admin', 'admin@connect.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

-- Seed Sample Services
INSERT INTO services (name, description) VALUES 
('PCB Design', 'Custom PCB layout and design using Altium, KiCad or Eagle.'),
('Embedded Firmware', 'Firmware development for ARM, ESP32, and Arduino platforms.'),
('IoT Prototyping', 'End-to-end IoT solutions including sensor integration and cloud connectivity.'),
('Robotics & Automation', 'Design and implementation of robotic arms, rovers, and automated systems.'),
('Digital Logic Design', 'FPGA and CPLD programming using VHDL/Verilog.'),
('Analog Circuitry', 'Design and troubleshooting of analog filters, amplifiers, and power supplies.');

-- Seed Sample Questions
INSERT INTO assessment_questions (question, option_a, option_b, option_c, option_d, correct_option) VALUES 
('Which component is used to store electrical charge?', 'Resistor', 'Capacitor', 'Inductor', 'Diode', 'B'),
('What does IC stand for in electronics?', 'Internal Circuit', 'Integrated Circuit', 'Interactive Connection', 'Ion Collector', 'B'),
('Which of these is a serial communication protocol?', 'I2C', 'PCB', 'FPGA', 'LDO', 'A'),
('What is the standard unit of frequency?', 'Henry', 'Farad', 'Hertz', 'Ohm', 'C'),
('Which transistor type is commonly used for switching power?', 'MOSFET', 'LED', 'Zener', 'Photo-resistor', 'A'),
('What is the purpose of a decoupling capacitor?', 'Voltage regulation', 'Noise reduction', 'Signal amplification', 'Current limiting', 'B'),
('Which law relates V, I, and R?', 'Kirchhoffs Law', 'Faradays Law', 'Ohms Law', 'Lenzs Law', 'C'),
('What is the main advantage of SMT over THT?', 'Lower cost', 'Smaller size', 'Easier hand soldering', 'Higher voltage capacity', 'B'),
('Which microcontroller is the heart of most Arduino UNO boards?', 'ESP32', 'STM32', 'ATmega328P', 'PIC16F84', 'C'),
('What does ESD stand for?', 'Electronic Signal Device', 'Electrostatic Discharge', 'Embedded System Design', 'Extended Serial Data', 'B');
