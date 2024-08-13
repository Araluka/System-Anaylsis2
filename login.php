<?php
session_start();
include 'db_connect.php';

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // ตรวจสอบรหัสผ่านสำหรับการล็อกอิน
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

    if ($user) {
        // สร้างแฮชใหม่สำหรับรหัสผ่านและอัปเดตฐานข้อมูล
        $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);
        if (isset($user['CustomerID'])) {
            $update_sql = "UPDATE usercustomer SET Password = ? WHERE Email = ?";
        } else {
            $update_sql = "UPDATE userseller SET Password = ? WHERE Email = ?";
        }
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $new_hashed_password, $email);
        $update_stmt->execute();
        $update_stmt->close();

        // เข้าสู่ระบบสำเร็จ
        $_SESSION['customer_id'] = $user['CustomerID'] ?? $user['UserSellerID'];
        $_SESSION['user_type'] = $user['CustomerID'] ? 'customer' : 'seller';
        header("Location: home.php"); // เปลี่ยนเส้นทางไปที่หน้าโฮมเพจ
        exit();
    } else {
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
    <!-- <link rel="stylesheet" href="css/styles.css"> -->
    <link rel="stylesheet" href="css/login.css">
</head>
<body>
<!-- <div class="header-container">
    <div class="header-top">
        <div class="shop-name">Second-Hand Figure Shop</div>
    </div>
    <div class="header-bottom">
        <a href="Home.php" class="home-icon"></a>
        <div class="icon-container">
            <a href="#" class="user-icon">
                <img src="image/people.png" alt="User">
            </a>
            <a href="#" class="cart-icon">
                <img src="image/cart.png" alt="Cart">
            </a>
        </div>
        <div class="search-bar">
            <form action="search.php" method="POST">
                <input type="text" name="search" placeholder="ค้นหาสินค้า...">
                <button type="submit" class="search-button">
                    <img src="image/search.png" alt="Search">
                </button>
            </form>
        </div>
    </div>
</div> -->

    <div class="header-top">
        <div class="shop-name">Second-Hand Figure Shop</div>
    </div>
    <div class="registration-form">

        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
        <div class="form-row">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" placeholder="Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                
        </div>  
        <button type="submit" class="register-button">Log in</button>   
        </form>
    </div>
</body>
</html>
