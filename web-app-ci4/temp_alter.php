<?php
$mysqli = new mysqli("localhost", "root", "", "student_shield");
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
$res = $mysqli->query("ALTER TABLE virtual_meetings MODIFY counselor_id INT(11) NULL DEFAULT NULL;");
if (!$res) { echo "Alter error: " . $mysqli->error; } else { echo "Altered counselor_id to NULL!\n"; }
