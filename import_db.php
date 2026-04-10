<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS student_shield");
    $pdo->exec("USE student_shield");
    
    // Drop existing tables just in case of mismatch
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    $pdo->exec("DROP TABLE IF EXISTS vent_logs;");
    $pdo->exec("DROP TABLE IF EXISTS users;");
    $pdo->exec("DROP TABLE IF EXISTS Student_Profiles;");
    $pdo->exec("DROP TABLE IF EXISTS Journal_Entries;");
    $pdo->exec("DROP TABLE IF EXISTS Incident_Reports;");
    $pdo->exec("DROP TABLE IF EXISTS SMS_Logs;");
    $pdo->exec("DROP TABLE IF EXISTS Users;"); // in case of case sensitivity
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");
    
    // Import schema
    $sql = file_get_contents(__DIR__ . '/schema.sql');
    $pdo->exec($sql);
    
    echo "Database student_shield created and schema imported successfully.\n";
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
