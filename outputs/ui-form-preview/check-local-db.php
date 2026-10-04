<?php
require __DIR__ . '/../../web-app-ci4/vendor/autoload.php';
(new CodeIgniter\Config\DotEnv(__DIR__ . '/../../web-app-ci4'))->load();
$host = getenv('database.default.hostname');
if (!in_array($host, ['localhost', '127.0.0.1', '::1'], true)) { exit("Local DB check skipped.\n"); }
mysqli_report(MYSQLI_REPORT_OFF);
try {
    $db = new mysqli($host, getenv('database.default.username'), getenv('database.default.password'), getenv('database.default.database'), (int) (getenv('database.default.port') ?: 3306));
    if ($db->connect_errno) { exit("Local database connection: unavailable\n"); }
    $tables = $db->query("SHOW TABLES LIKE 'users'");
    echo 'Local database connected; users table exists: ' . ($tables && $tables->num_rows > 0 ? 'yes' : 'no') . "\n";
    $db->close();
} catch (Throwable $error) { echo "Local database connection: unavailable\n"; }
