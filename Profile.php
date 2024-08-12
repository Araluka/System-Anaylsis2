<?php
include 'auth.php'; // รวม auth.php เพื่อให้สามารถใช้ข้อมูลผู้ใช้ได้

// ตรวจสอบว่าผู้ใช้ล็อกอินแล้ว
if ($is_logged_in) {
    echo "Welcome, " . htmlspecialchars($user_data['FirstName']) . "!";
    // แสดงข้อมูลเพิ่มเติมของผู้ใช้
    echo "<p>Email: " . htmlspecialchars($user_data['Email']) . "</p>";
} else {
    echo "You need to log in to view this page.";
}
?>