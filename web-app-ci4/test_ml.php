<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

echo "URL: " . env('ML_API_URL') . "\n";
echo "KEY: " . env('ML_API_KEY') . "\n";

$text = "im too sad. i dont think i can live for another day with this mindset of mine rn. i dont know what to do.";
$ch = curl_init(env('ML_API_URL') . '/analyze-text');
$payload = json_encode(['raw_text' => $text, 'student_id' => '1']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'X-API-KEY: ' . env('ML_API_KEY', '')
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Error: " . $curlError . "\n";
echo "Response: " . $response . "\n";
