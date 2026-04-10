<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=student_shield", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if users exist
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    if ($stmt->fetchColumn() == 0) {
        $sql = "INSERT INTO users (student_id, name, passcode, role, parent_phone, parent_email) VALUES 
                ('STU-001', 'Juan Dela Cruz', 'hash', 0, '+639123456789', 'parent1@test.com'),
                ('STU-002', 'Maria Clara', 'hash', 0, '+639987654321', 'parent2@test.com'),
                ('CNS-001', 'Dr. Alcantara', 'hash', 1, NULL, NULL)";
        $pdo->exec($sql);
        echo "Successfully seeded default users.\n";
    } else {
        echo "Users table already has data.\n";
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
