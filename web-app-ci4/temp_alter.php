<?php
$conn = mysqli_connect('localhost', 'root', '', 'student_shield');
mysqli_query($conn, "ALTER TABLE virtual_meetings MODIFY COLUMN status VARCHAR(50) DEFAULT 'requested'");
mysqli_query($conn, "ALTER TABLE virtual_meetings ADD COLUMN schedule_date DATE NULL DEFAULT NULL AFTER status");
mysqli_query($conn, "ALTER TABLE virtual_meetings ADD COLUMN schedule_time TIME NULL DEFAULT NULL AFTER schedule_date");
echo mysqli_error($conn);
