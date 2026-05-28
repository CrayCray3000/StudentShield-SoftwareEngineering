<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=student_shield;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $stmt = $pdo->query("SELECT id, name, role FROM users");
    print_r($stmt->fetchAll());
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
