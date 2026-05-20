<?php
// public/run_migrations.php — Standalone database migration runner for InfinityFree
date_default_timezone_set('Asia/Manila');

// Check security passcode
if (php_sapi_name() !== 'cli') {
    $passcode = $_GET['passcode'] ?? '';
    if ($passcode !== 'StudentShieldSecureMigrate2026') {
        die("Unauthorized access.");
    }
}

$envFile = __DIR__ . '/../.env';
$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'studentshield';

if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
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

// 1. Create students table
$createStudents = "CREATE TABLE IF NOT EXISTS `students` (
    `user_id` INT(11) NOT NULL,
    `student_id` VARCHAR(50) NULL,
    `parent_phone` VARCHAR(50) NULL,
    `parent_email` VARCHAR(255) NULL,
    `section` VARCHAR(100) NULL,
    `adviser_name` VARCHAR(255) NULL,
    `grade_level` VARCHAR(50) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `fk_students_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 2. Create counselors table
$createCounselors = "CREATE TABLE IF NOT EXISTS `counselors` (
    `user_id` INT(11) NOT NULL,
    `counselor_email` VARCHAR(255) NULL,
    `counselor_phone` VARCHAR(50) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `fk_counselors_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// 3. Create admins table
$createAdmins = "CREATE TABLE IF NOT EXISTS `admins` (
    `user_id` INT(11) NOT NULL,
    `email` VARCHAR(255) NULL,
    `phone` VARCHAR(50) NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`),
    CONSTRAINT `fk_admins_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

// Execute creations
if ($conn->query($createStudents)) {
    echo "<p style='color:green;'>✅ Table <b>students</b> created or already exists.</p>";
} else {
    echo "<p style='color:red;'>❌ Failed to create students table: {$conn->error}</p>";
}

if ($conn->query($createCounselors)) {
    echo "<p style='color:green;'>✅ Table <b>counselors</b> created or already exists.</p>";
} else {
    echo "<p style='color:red;'>❌ Failed to create counselors table: {$conn->error}</p>";
}

if ($conn->query($createAdmins)) {
    echo "<p style='color:green;'>✅ Table <b>admins</b> created or already exists.</p>";
} else {
    echo "<p style='color:red;'>❌ Failed to create admins table: {$conn->error}</p>";
}

// 4. Check if we need to migrate data
// If profile tables are empty, migrate data from `users`
$studentCount = $conn->query("SELECT COUNT(*) as count FROM `students`")->fetch_assoc()['count'] ?? 0;
$counselorCount = $conn->query("SELECT COUNT(*) as count FROM `counselors`")->fetch_assoc()['count'] ?? 0;
$adminCount = $conn->query("SELECT COUNT(*) as count FROM `admins`")->fetch_assoc()['count'] ?? 0;

if ($studentCount == 0 && $counselorCount == 0 && $adminCount == 0) {
    echo "<p>🚀 Profile tables are empty. Migrating data from <b>users</b>...</p>";
    
    // Check if columns exist in `users`
    $colsCheck = $conn->query("SHOW COLUMNS FROM `users` LIKE 'student_id'");
    if ($colsCheck && $colsCheck->num_rows > 0) {
        $users = $conn->query("SELECT id, role, student_id, parent_phone, parent_email, section, adviser_name, grade_level FROM `users`");
        if ($users) {
            $stmtStudent = $conn->prepare("INSERT INTO `students` (user_id, student_id, parent_phone, parent_email, section, adviser_name, grade_level) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmtCounselor = $conn->prepare("INSERT INTO `counselors` (user_id, counselor_email, counselor_phone) VALUES (?, ?, ?)");
            $stmtAdmin = $conn->prepare("INSERT INTO `admins` (user_id, email, phone) VALUES (?, ?, ?)");
            
            while ($row = $users->fetch_assoc()) {
                $role = (int)$row['role'];
                if ($role === 0) {
                    $stmtStudent->bind_param("issssss", $row['id'], $row['student_id'], $row['parent_phone'], $row['parent_email'], $row['section'], $row['adviser_name'], $row['grade_level']);
                    $stmtStudent->execute();
                } else if ($role === 1) {
                    $stmtCounselor->bind_param("iss", $row['id'], $row['parent_email'], $row['parent_phone']);
                    $stmtCounselor->execute();
                } else if ($role === 2) {
                    $stmtAdmin->bind_param("iss", $row['id'], $row['parent_email'], $row['parent_phone']);
                    $stmtAdmin->execute();
                }
            }
            echo "<p style='color:green;'>✅ Data migration complete!</p>";
        } else {
            echo "<p style='color:red;'>❌ Failed to query users table: {$conn->error}</p>";
        }
    } else {
        echo "<p style='color:orange;'>ℹ️ Profile columns already dropped from <b>users</b>. Skipping data migration.</p>";
    }
} else {
    echo "<p style='color:orange;'>ℹ️ Profile tables already contain data. Skipping migration.</p>";
}

// 5. Drop old columns from users table if they exist
$columnsToDrop = ['student_id', 'parent_phone', 'parent_email', 'section', 'adviser_name', 'grade_level'];
$droppedAny = false;

foreach ($columnsToDrop as $col) {
    $checkCol = $conn->query("SHOW COLUMNS FROM `users` LIKE '{$col}'");
    if ($checkCol && $checkCol->num_rows > 0) {
        if ($conn->query("ALTER TABLE `users` DROP COLUMN `{$col}`")) {
            echo "<p style='color:green;'>✅ Dropped column <b>{$col}</b> from <b>users</b> table.</p>";
            $droppedAny = true;
        } else {
            echo "<p style='color:red;'>❌ Failed to drop column <b>{$col}</b>: {$conn->error}</p>";
        }
    }
}

if (!$droppedAny) {
    echo "<p style='color:orange;'>ℹ️ No deprecated columns found in <b>users</b> table to drop.</p>";
}

$conn->close();
echo "<h3>Database operations completed!</h3>";
