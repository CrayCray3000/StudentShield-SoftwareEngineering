<?php
$db = new mysqli('localhost', 'root', '', 'student_shield');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check and add remember_token to users table
$result = $db->query("SHOW COLUMNS FROM users LIKE 'remember_token'");
if ($result->num_rows == 0) {
    if ($db->query("ALTER TABLE users ADD COLUMN remember_token VARCHAR(255) NULL AFTER passcode") === TRUE) {
        echo "Column 'remember_token' added to users successfully.\n";
    } else {
        echo "Error adding remember_token: " . $db->error . "\n";
    }
} else {
    echo "Column 'remember_token' already exists in users.\n";
}

// Check and add emotions to vent_logs table
$result = $db->query("SHOW COLUMNS FROM vent_logs LIKE 'emotions'");
if ($result->num_rows == 0) {
    if ($db->query("ALTER TABLE vent_logs ADD COLUMN emotions VARCHAR(255) NULL AFTER raw_text") === TRUE) {
        echo "Column 'emotions' added to vent_logs successfully.\n";
    } else {
        echo "Error adding emotions: " . $db->error . "\n";
    }
} else {
    echo "Column 'emotions' already exists in vent_logs.\n";
}

$db->close();
?>
