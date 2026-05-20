<?php
/**
 * ┌────────────────────────────────────────────────────────┐
 * │  ONE-TIME PASSWORD MIGRATION SCRIPT                    │
 * │  Converts all plaintext passwords to bcrypt hashes.    │
 * │                                                        │
 * │  Run via CLI:  php migrate_passwords.php               │
 * │  Delete this file after running.                       │
 * └────────────────────────────────────────────────────────┘
 */

// Boot CodeIgniter
require_once __DIR__ . '/vendor/autoload.php';

$app = \Config\Services::codeigniter();
$app->initialize();

$db = \Config\Database::connect();
$users = $db->table('users')->get()->getResultArray();

$migrated = 0;
$alreadyHashed = 0;
$total = count($users);

echo "=== StudentShield Password Migration ===\n";
echo "Found {$total} users.\n\n";

foreach ($users as $user) {
    $pwHash = $user['password_hash'];
    
    // Bcrypt hashes always start with $2y$ (or $2a$, $2b$)
    if (preg_match('/^\$2[aby]\$/', $pwHash)) {
        $alreadyHashed++;
        continue;
    }
    
    // This is a plaintext password — hash it
    $hashed = password_hash($pwHash, PASSWORD_BCRYPT);
    $db->table('users')->where('id', $user['id'])->update([
        'password_hash' => $hashed
    ]);
    
    echo "  [MIGRATED] User #{$user['id']} ({$user['name']})\n";
    $migrated++;
}

echo "\n=== Migration Complete ===\n";
echo "  Already hashed: {$alreadyHashed}\n";
echo "  Migrated now:   {$migrated}\n";
echo "  Total users:    {$total}\n";
echo "\n⚠️  DELETE THIS FILE NOW: rm migrate_passwords.php\n";
echo "⚠️  Then remove the plaintext fallback from AuthController.php\n";
