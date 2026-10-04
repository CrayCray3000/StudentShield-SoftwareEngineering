<?php
// Local, read-only renderer of the real PHP views. No database or submissions.
$appRoot = dirname(__DIR__, 2) . '/web-app-ci4/';
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    exit('Submissions are disabled in this design preview.');
}
if (preg_match('#^/(css/(?:student-forms|school-brand)\.css|js/student-meet\.js|images/logo\.png)$#', $path, $match)) {
    $file = $appRoot . 'public/' . $match[1];
    $type = ['css' => 'text/css', 'js' => 'application/javascript', 'png' => 'image/png'];
    header('Content-Type: ' . $type[pathinfo($file, PATHINFO_EXTENSION)]);
    readfile($file);
    exit;
}
if ($path === '/student/api/meeting/status') {
    header('Content-Type: application/json');
    echo '{"status":"success","has_meeting":false,"has_request":false,"join_url":null}';
    exit;
}
$views = [
    '/login' => 'login', '/admin/login' => 'admin_login',
    '/register' => 'register', '/admin/otp' => 'admin_otp',
    '/student/dashboard' => 'student_dashboard',
    '/student/vent' => 'vent_form', '/report-bullying' => 'reports/bullying_form',
    '/privacy' => 'privacy', '/terms' => 'terms', '/guardian-consent' => 'guardian_consent',
    '/admin/pending-registrations' => 'admin_pending_registrations',
];
if (!isset($views[$path])) {
    http_response_code(404);
    exit('Read-only UI preview. This route has no preview fixture.');
}
chdir($appRoot);
require $appRoot . 'vendor/codeigniter4/framework/system/Test/bootstrap.php';
helper('form');
// Empty fixtures are for layout checks only; they do not represent live records.
$previewData = $path === '/admin/pending-registrations'
    ? ['pendingUsers' => [], 'pendingCount' => 0, 'totalStudents' => 0, 'approvedToday' => 0]
    : [];
echo view($views[$path], $previewData);
