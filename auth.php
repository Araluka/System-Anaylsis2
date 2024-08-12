<?php
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// กำหนดค่าเริ่มต้น
$is_logged_in = false;
$user_data = null;

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (isset($_SESSION['user_id'])) {
    $is_logged_in = true;
    $user_id = $_SESSION['user_id'];
    $user_type = $_SESSION['user_type'];

    // ดึงข้อมูลของผู้ใช้จากฐานข้อมูล
    if ($user_type == 'customer') {
        $sql = "SELECT * FROM usercustomer WHERE CustomerID = ?";
    } else {
        $sql = "SELECT * FROM userseller WHERE UserSellerID = ?";
    }
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();
    $stmt->close();
}

$conn->close();

// ทำงานที่เหลือของไฟล์นี้
?>

