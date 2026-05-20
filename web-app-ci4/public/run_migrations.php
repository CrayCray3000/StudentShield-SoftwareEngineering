<?php
// public/run_migrations.php — Programmatic migration runner for remote environments
date_default_timezone_set('Asia/Manila');

// Check security passcode to prevent unauthorized triggers
if (php_sapi_name() !== 'cli') {
    $passcode = $_GET['passcode'] ?? '';
    if ($passcode !== 'StudentShieldSecureMigrate2026') {
        die("Unauthorized access.");
    }
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';

if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', $_ENV['CI_ENVIRONMENT'] ?? $_SERVER['CI_ENVIRONMENT'] ?? getenv('CI_ENVIRONMENT') ?: 'production');
}

// Initialize CodeIgniter system
CodeIgniter\Boot::bootConsole($paths);
$migrations = \Config\Services::migrations();

try {
    echo "<h3>Starting Database Migrations...</h3>";
    $result = $migrations->latest();
    if ($result) {
        echo "<p style='color:green;font-weight:bold;'>✅ Database Migrations completed successfully!</p>";
    } else {
        echo "<p style='color:orange;'>ℹ️ Database is already up to date.</p>";
    }
} catch (\Throwable $e) {
    echo "<p style='color:red;font-weight:bold;'>❌ Migration failed: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
