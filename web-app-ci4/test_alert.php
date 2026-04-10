<?php
define('WRITEPATH', __DIR__ . '/writable/');

$file = WRITEPATH . 'uploads/alerts.json';
echo "File path: $file\n";

if (!is_dir(WRITEPATH . 'uploads')) {
    mkdir(WRITEPATH . 'uploads', 0777, true);
    echo "Created dir.\n";
}

$alerts = [];
if (file_exists($file) && filesize($file) > 0) {
    $decoded = json_decode(file_get_contents($file), true);
    if (is_array($decoded)) {
        $alerts = $decoded;
    }
}
        
array_unshift($alerts, [
    'type' => 'SMS',
    'recipient' => '+123',
    'message' => 'Test',
    'timestamp' => date('Y-m-d H:i:s')
]);
        
$res = file_put_contents($file, json_encode($alerts, JSON_PRETTY_PRINT));
echo "Result: $res\n";
