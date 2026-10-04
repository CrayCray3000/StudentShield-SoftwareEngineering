<?php
// Local, read-only source inspection. This is not an application endpoint.
if (PHP_SAPI !== 'cli') { exit(1); }
$appRoot = dirname(__DIR__, 2) . '/web-app-ci4';
define('FCPATH', $appRoot . '/public/');
define('ENVIRONMENT', 'development');
require $appRoot . '/app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootConsole($paths);
$config = config('Database')->default;
if (!in_array($config['hostname'], ['localhost', '127.0.0.1', '::1'], true)) {
    fwrite(STDERR, "Refusing to inspect a non-local database.\n"); exit(1);
}
$db = Config\Database::connect();
$user = $db->table('users')
    ->select('users.id, users.name, students.student_id, students.section, students.grade_level, students.adviser_name, students.parent_email, students.parent_phone')
    ->join('students', 'students.user_id = users.id', 'left')
    ->where('users.name', $argv[1] ?? 'ashlog')->where('users.role', 0)
    ->get()->getRowArray();
if (!$user) { fwrite(STDERR, "Requested local student not found.\n"); exit(1); }
$vents = $db->table('vent_logs')->where('user_id', $user['id'])
    ->where('deleted_at IS NULL')->orderBy('created_at', 'ASC')->limit(30)
    ->get()->getResultArray();
$encrypter = Config\Services::encrypter();
$timeline = [];
foreach ($vents as &$vent) {
    $text = $vent['raw_text'];
    if (ctype_xdigit($text)) {
        try { $text = $encrypter->decrypt(hex2bin($text)); }
        catch (Throwable $error) { $text = null; }
    }
    $risk = strtoupper($vent['risk_level'] ?? 'LOW');
    $timeline[] = ['date' => $vent['created_at'], 'y' => match ($risk) {
        'CRITICAL' => 100, 'HIGH' => 75, 'MEDIUM' => 50, default => 25
    }];
    $vent = array_intersect_key($vent, array_flip(['id','created_at','risk_level','risk_percent','status','trajectory','escalation_probability','emotions']));
    $vent['raw_text'] = $text;
}
unset($vent);
$incidents = $db->table('bully_reports')
    ->select('id, status, created_at')
    ->groupStart()->where('reporter_student_id', $user['id'])
    ->orWhere('victim_student_id', $user['id'])->orWhere('bully_student_id', $user['id'])
    ->groupEnd()->orderBy('created_at', 'DESC')->limit(15)->get()->getResultArray();
$latest = !empty($vents) ? end($vents) : null;
$caseReports = $db->table('bully_reports')->where('deleted_at IS NULL')
    ->groupStart()->where('reporter_student_id', $user['id'])
    ->orWhere('victim_student_id', $user['id'])->orWhere('bully_student_id', $user['id'])
    ->groupEnd()->orderBy('created_at', 'DESC')->get()->getResultArray();
foreach ($caseReports as &$report) {
    if (ctype_xdigit($report['incident_details']) && strlen($report['incident_details']) > 32) {
        try { $report['incident_details'] = $encrypter->decrypt(hex2bin($report['incident_details'])); }
        catch (Throwable $error) { $report['incident_details'] = null; }
    }
    $report = array_intersect_key($report, array_flip(['id','reporter_student_id','victim_student_id','bully_student_id','created_at','status','incident_details']));
}
unset($report);
$caseLogs = [];
foreach (['sms_logs','email_logs','counselor_notes','case_status_history'] as $table) {
    if (!$db->tableExists($table)) { $caseLogs[$table] = []; continue; }
    $query = $db->table($table)->where('student_id', $user['id']);
    if ($table === 'counselor_notes') { $query->where('deleted_at IS NULL'); }
    $caseLogs[$table] = $query->orderBy(in_array($table,['sms_logs','email_logs']) ? 'timestamp' : 'created_at', 'DESC')->limit(50)->get()->getResultArray();
}
echo json_encode(['user'=>$user,'timeline'=>$timeline,'vents'=>array_reverse($vents),
    'incidents'=>$incidents,'current_trajectory'=>$latest['trajectory'] ?? 'INSUFFICIENT_DATA',
    'escalation_probability'=>(float)($latest['escalation_probability'] ?? 0),
    'case_reports'=>$caseReports,'case_logs'=>$caseLogs],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
