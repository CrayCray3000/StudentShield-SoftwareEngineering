<?php
$conn = new mysqli('sql308.infinityfree.com', 'if0_41428179', 'A0XMtOxIQVLBpLw', 'if0_41428179_studentshield');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "vent_logs schema:\n";
$res = $conn->query('SHOW COLUMNS FROM vent_logs');
while($row = $res->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
echo "----\n";
echo "bully_reports schema:\n";
$res2 = $conn->query('SHOW COLUMNS FROM bully_reports');
while($row = $res2->fetch_assoc()) {
    echo $row['Field'] . "\n";
}
