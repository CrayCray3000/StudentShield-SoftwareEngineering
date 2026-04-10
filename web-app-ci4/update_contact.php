<?php
// ========================================================================
// EDIT THESE TWO LINES WITH YOUR REAL CONTACT INFO FOR TESTING
// ========================================================================

// 1. Phone number MUST be verified on your Twilio console! (Format: +639...)
$my_verified_phone_number = '+639123456789';


// 2. Email address where you want to receive the alerts
$my_real_email_address = 'vonnchristianmielmeneses@gmail.com';

// ========================================================================
// DO NOT EDIT BELOW THIS LINE
// ========================================================================
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $mysqli = new mysqli("localhost", "root", "", "student_shield");
    $stmt = $mysqli->prepare("UPDATE users SET parent_phone = ?, parent_email = ? WHERE id = 1");
    $stmt->bind_param("ss", $my_verified_phone_number, $my_real_email_address);
    $stmt->execute();

    echo "\n[SUCCESS] Updated Student #1 (Juan Dela Cruz)'s parent contact info!\n";
    echo "Phone is now: " . $my_verified_phone_number . "\n";
    echo "Email is now: " . $my_real_email_address . "\n\n";
    echo "You can now log in to the student portal and submit a Critical Vent to test it.\n";
}
catch (Exception $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}
