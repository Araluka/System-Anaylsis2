<?php
$servername = "localhost";
$username = "ProG13";
$password = "";
$dbname = "prog13";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
// http://192.168.2.128/SystemA/Home.php
}
?>

