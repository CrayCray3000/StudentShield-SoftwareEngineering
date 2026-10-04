<?php
// Local-only, no-authentication fixture for testing the real login views.
function base_url($path = '') { return 'https://studentshield.app/' . $path; }
function csrf_field() { return '<input type="hidden" name="csrf_test" value="fixture-only">'; }
function session() { return new class { function getFlashdata($key) { return null; } }; }
function old($key) { return ''; }
function esc($value, $context = 'html') { return htmlspecialchars((string) $value, ENT_QUOTES); }
$view = ($argv[1] ?? '') === 'admin' ? 'admin_login' : 'login';
ob_start();
require __DIR__ . '/../web-app-ci4/app/Views/' . $view . '.php';
$html = ob_get_clean();
// Force only the existing reduced-motion rule, reproducing an OS with animations disabled.
if (($argv[2] ?? '') === 'reduce') {
    $html = str_replace('@media (prefers-reduced-motion: reduce)', '@media all', $html);
}
file_put_contents(__DIR__ . '/login-check-' . $view . '.html', $html);
echo 'Rendered ' . $view . PHP_EOL;
