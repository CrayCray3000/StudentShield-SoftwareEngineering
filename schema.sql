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
    sentiment_score FLOAT,
    risk_level VARCHAR(50),
    status VARCHAR(50) DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS incident_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    reporter_id INT,
    perpetrator_name VARCHAR(255),
    incident_type VARCHAR(100),
    description TEXT,
    status VARCHAR(50) DEFAULT 'Pending Investigation',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL DEFAULT NULL,
    FOREIGN KEY (reporter_id) REFERENCES users(id) ON DELETE SET NULL
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
