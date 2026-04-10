<?php
$db = new mysqli('localhost', 'root', '', 'student_shield');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
$sql = "CREATE TABLE IF NOT EXISTS bully_reports (
  id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  reporter_student_id INT(11) UNSIGNED NULL,
  reporter_type ENUM('self', 'bystander') DEFAULT 'self',
  victim_student_id INT(11) UNSIGNED NULL,
  bully_student_id INT(11) UNSIGNED NULL,
  incident_details TEXT NULL,
  status ENUM('pending_review', 'verified', 'rejected_fake') DEFAULT 'pending_review',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";
if ($db->query($sql) === TRUE) {
    echo "Table bully_reports created successfully";
} else {
    echo "Error creating table: " . $db->error;
}
$db->close();
?>
