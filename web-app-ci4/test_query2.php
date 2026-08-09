<?php
$mysqli = new mysqli("localhost", "root", "", "student_shield");
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$res = $mysqli->query("SHOW CREATE TABLE virtual_meetings");
$row = $res->fetch_assoc();
print_r($row['Create Table']);
