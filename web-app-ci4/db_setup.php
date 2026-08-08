<?php
$db = new mysqli('localhost', 'root', '', 'student_shield');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
$sql = "CREATE TABLE IF NOT EXISTS virtual_meetings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    counselor_id INT NOT NULL,
    meet_link VARCHAR(255) NOT NULL,
    status ENUM('active', 'completed', 'cancelled') DEFAULT 'active',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (counselor_id) REFERENCES users(id) ON DELETE CASCADE
)";
if ($db->query($sql) === TRUE) {
    echo "Table bully_reports created successfully";
} else {
    echo "Error creating table: " . $db->error;
}
$db->close();
?>
