<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // คำสั่ง SQL สำหรับตรวจสอบข้อมูลในตาราง userseller
    $sql_seller = "SELECT * FROM userseller WHERE Email = ?";
    $stmt = $conn->prepare($sql_seller);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result_seller = $stmt->get_result();
    $user = $result_seller->fetch_assoc();

    // ถ้าข้อมูลไม่พบใน userseller, ตรวจสอบใน usercustomer
    if (!$user) {
        $sql_customer = "SELECT * FROM usercustomer WHERE Email = ?";
        $stmt = $conn->prepare($sql_customer);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result_customer = $stmt->get_result();
        $user = $result_customer->fetch_assoc();
    }

    // ตรวจสอบรหัสผ่าน
    if ($user && password_verify($password, $user['Password'])) {
        // เข้าสู่ระบบสำเร็จ
        $_SESSION['customer_id'] = $user['CustomerID'] ?? $user['UserSellerID'];
        $_SESSION['user_type'] = $user['CustomerID'] ? 'customer' : 'seller';
        header("Location: home.php"); // เปลี่ยนเส้นทางไปที่หน้าโฮมเพจ
        exit();
    } else {
        // ข้อความเมื่อไม่สามารถเข้าสู่ระบบได้
        $error_message = "Invalid email or password.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Anton&family=Bungee+Shade&family=Bungee+Spice&family=Concert+One&family=Kalam:wght@300;400;700&family=Lilita+One&family=Luckiest+Guy&family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="header-container">
        <div class="header-top">
            <div class="shop-name">Second-Hand Figure Shop</div>
        </div>
        <div class="header-bottom">
            <a href="Home.php" class="home-icon"> </a>
            <div class="icon-container">
                <a href="#" class="user-icon">
                    <img src="image/people.png" alt="User">
                </a>
                <a href="#" class="cart-icon">
                    <img src="image/cart.png" alt="Cart">
                </a>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="ค้นหาสินค้า...">
                <button class="search-button">
                    <img src="image/search.png" alt="Search">
                </button>
            </div>
        </div>
    </div>
    <div class="login-container">
        <h2>Log In</h2>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <input type="text" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Log In">
        </form>
    </div>
</body>
</html>
