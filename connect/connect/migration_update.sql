-- Run these commands if you have already imported the previous database.sql
ALTER TABLE users ADD COLUMN user_type ENUM('Individual', 'Company') DEFAULT 'Individual' AFTER address;

CREATE TABLE IF NOT EXISTS engineer_services (
    engineer_id INT,
    service_id INT,
    PRIMARY KEY (engineer_id, service_id),
    FOREIGN KEY (engineer_id) REFERENCES engineers(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);
