<?php
// fix_db.php — Quick database patch for InfinityFree

// Load InfinityFree Database credentials from .env or just use the simplest standard MySQLi connection based on standard CI4 env vars.
// We'll read the CodeIgniter .env file manually to ensure this works without loading the whole framework.

$envFile = __DIR__ . '/../.env';
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'studentshield';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        if (strpos($line, 'database.default.hostname') !== false) {
            $dbHost = trim(explode('=', $line)[1]);
        }
        if (strpos($line, 'database.default.database') !== false) {
            $dbName = trim(explode('=', $line)[1]);
        }
        if (strpos($line, 'database.default.username') !== false) {
            $dbUser = trim(explode('=', $line)[1]);
        }
        if (strpos($line, 'database.default.password') !== false) {
            $dbPass = trim(explode('=', $line)[1], ' "\'');
        }
    }
}

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

if ($conn->connect_error) {
    die("<h1 style='color:red;'>Connection failed: " . $conn->connect_error . "</h1>");
}

echo "<h2>Database Connected Successfully to {$dbName}!</h2>";

// Add Section
$resultSection = $conn->query("SHOW COLUMNS FROM `users` LIKE 'section'");
if ($resultSection && $resultSection->num_rows == 0) {
    if ($conn->query("ALTER TABLE `users` ADD `section` VARCHAR(100) NULL")) {
        echo "<p style='color:green;'>✅ Successfully added <b>section</b> column.</p>";
    }
    else {
        echo "<p style='color:red;'>❌ Failed to add section: {$conn->error}</p>";
    }
}
else {
    echo "<p>ℹ️ Column <b>section</b> already exists.</p>";
}

// Add Adviser Name
$resultAdviser = $conn->query("SHOW COLUMNS FROM `users` LIKE 'adviser_name'");
if ($resultAdviser && $resultAdviser->num_rows == 0) {
    if ($conn->query("ALTER TABLE `users` ADD `adviser_name` VARCHAR(255) NULL")) {
        echo "<p style='color:green;'>✅ Successfully added <b>adviser_name</b> column.</p>";
    }
    else {
        echo "<p style='color:red;'>❌ Failed to add adviser_name: {$conn->error}</p>";
    }
}
else {
    echo "<p>ℹ️ Column <b>adviser_name</b> already exists.</p>";
}

$conn->close();

echo "<h3>Action Complete. <a href='/'>Click here to return home</a>. Don't forget to delete this file later for security.</h3>";
