<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=student_shield", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT * FROM incident_reports");
    $incidents = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($incidents);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
