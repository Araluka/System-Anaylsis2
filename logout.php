<?php
session_start();

// ลบข้อมูลเซสชันทั้งหมด
session_unset();

// ทำลายเซสชัน
session_destroy();

// เปลี่ยนเส้นทางไปที่หน้าเข้าสู่ระบบ
header("Location: login.php");
exit();
?>