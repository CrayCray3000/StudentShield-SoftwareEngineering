<?php
$html = file_get_contents(__DIR__ . '/../error_response_utf8.html');
// Let's strip script and style tags to read the text
$clean = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $html);
$clean = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', "", $clean);
$clean = strip_tags($clean);
$clean = preg_replace("/\n\s*\n+/", "\n", $clean);
echo substr($clean, 0, 5000);
