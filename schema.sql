-- Database Schema for StudentShield

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(50) UNIQUE,
    name VARCHAR(255) NOT NULL,
    passcode VARCHAR(255) NOT NULL,
    role TINYINT(1) DEFAULT 0 COMMENT '0=Student, 1=Counselor',
    parent_phone VARCHAR(50),
    parent_email VARCHAR(255),
    section VARCHAR(100),
    adviser_name VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS student_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    grade_level VARCHAR(50),
    assigned_counselor_id INT,
    parent_contact_number VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_counselor_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS vent_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    raw_text TEXT NOT NULL,
    emotions VARCHAR(255) NULL,
    risk_percent FLOAT,
    risk_level VARCHAR(50),
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS bully_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_student_id INT,
    reporter_type VARCHAR(50),
    victim_student_id INT NULL,
    bully_student_id INT NULL,
    incident_details TEXT,
    status VARCHAR(50) DEFAULT 'pending_review',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL DEFAULT NULL,
    deleted_at DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (reporter_student_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (victim_student_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (bully_student_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    action VARCHAR(50) NOT NULL,
    entity_type VARCHAR(50) NOT NULL,
    entity_id INT NOT NULL,
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(255) NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
