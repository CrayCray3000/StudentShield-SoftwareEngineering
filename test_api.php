<?php

namespace App\Controllers;

require __DIR__ . '/web-app-ci4/vendor/autoload.php';

// A standalone script to test the interaction without starting everything?
// Wait, actually I can't easily bootstrap CI4 without the framework.
// Let's just use raw curl in PHP.

$text = "I am depressed and want to die";

$ch = curl_init('http://localhost:8000/analyze-text');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['raw_text' => $text]));

$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpcode\n";
echo "Response: $response\n";
echo "Error: $error\n";
