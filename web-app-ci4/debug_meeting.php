<?php
// Quick diagnostic — DELETE THIS FILE AFTER DEBUGGING
$mysqli = new mysqli("localhost", "root", "", "student_shield");
if ($mysqli->connect_error) {
    die(json_encode(['error' => 'DB connect failed: ' . $mysqli->connect_error]));
}

$results = [];

// 1. Check table exists
$r = $mysqli->query("SHOW TABLES LIKE 'virtual_meetings'");
$results['table_exists'] = $r->num_rows > 0;

// 2. Check counselor_id column is nullable
$r = $mysqli->query("SHOW COLUMNS FROM virtual_meetings WHERE Field = 'counselor_id'");
$col = $r->fetch_assoc();
$results['counselor_id_nullable'] = ($col['Null'] === 'YES');
$results['counselor_id_column'] = $col;

// 3. Find a real student user_id to use for testing
$r = $mysqli->query("SELECT id, name FROM users WHERE role = 0 LIMIT 1");
$student = $r->fetch_assoc();
$results['test_student'] = $student;

// 4. Try an actual insert with NULL counselor_id
if ($student) {
    $sid = (int)$student['id'];
    $ins = $mysqli->query("INSERT INTO virtual_meetings (student_id, counselor_id, meet_link, status, created_at) VALUES ($sid, NULL, '', 'requested', NOW())");
    $results['insert_success'] = $ins;
    $results['insert_error'] = $mysqli->error;
    if ($ins) {
        $results['inserted_id'] = $mysqli->insert_id;
        // Clean it up
        $mysqli->query("DELETE FROM virtual_meetings WHERE id = " . $mysqli->insert_id);
    }
}

// 5. Check all rows currently in virtual_meetings
$r = $mysqli->query("SELECT * FROM virtual_meetings");
$results['existing_rows'] = $r->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($results, JSON_PRETTY_PRINT);
