<?php
// เริ่มต้นเซสชัน
session_start();

// เชื่อมต่อฐานข้อมูล
include 'db_connect.php';

// ตรวจสอบการส่งข้อมูลจากฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthDate = $_POST['birthDate'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $zipCode = $_POST['zipCode'];
    $userName = $_POST['userName'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    
    // ตรวจสอบว่ารหัสผ่านและการยืนยันรหัสผ่านตรงกัน
    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }
    
    // เข้ารหัสรหัสผ่าน
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // SQL เพื่อเพิ่มข้อมูลลงในตาราง
    $sql = "INSERT INTO userseller (
        FirstName, LastName, Email, Phone, BirthDate, Address, City, Province, `Zip Code`, UserName, Password
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // เตรียมคำสั่ง SQL
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }
    
    // ผูกพารามิเตอร์กับคำสั่ง SQL
    $stmt->bind_param(
        "sssssssssss",
        $firstName, $lastName, $email, $phone, $birthDate, $address, $city, $province, $zipCode, $userName, $hashedPassword
    );
    
    // ดำเนินการคำสั่ง SQL
    if ($stmt->execute()) {
        echo "Registration successful!";
        // เปลี่ยนเส้นทางไปที่หน้า home.php
        header("Location: home.php");
        exit();
    } else {
        die("Error executing the statement: " . $stmt->error);
    }
    
    // ปิดคำสั่ง SQL และการเชื่อมต่อฐานข้อมูล
    $stmt->close();
    $conn->close();
}
?>
<?php include 'auth.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Registration</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Anton&family=Antic&display=swap">
    <style>
        body {
            font-family: 'Antic', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f7f7f7;
        }
        .container {
            width: 60%;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            font-family: 'Anton', sans-serif;
            text-align: center;
            color: #000;
            background-color: #ffccff;
            padding: 10px 0;
            margin: 0 -20px 20px -20px;
        }
        label {
            display: block;
            margin: 10px 0 5px 0;
        }
        input[type="text"], input[type="email"], input[type="password"], input[type="url"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 10px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        .row {
            display: flex;
            justify-content: space-between;
            gap: 15px; /* เพิ่มช่องว่างระหว่างช่องพิมพ์ */
        }
        .row .column {
            flex: 1;
        }
        .row .column.half {
            flex: 1;
            min-width: calc(50% - 15px); /* ลดขนาดช่องพิมพ์ให้พอดีกับพื้นที่ */
        }
        .row .column.third {
            flex: 1;
            min-width: calc(33.33% - 10px);
        }
        .row .column.quarter {
            flex: 1;
            min-width: calc(25% - 10px);
        }
        .terms {
            display: flex;
            align-items: center;
            margin: 20px 0;
        }
        .terms input[type="checkbox"] {
            margin-right: 10px;
        }
        .register-btn {
            width: 100%;
            padding: 10px;
            background-color: #ffccff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Seller Registration</h1>
        
        <form action="SellerRegister.php" method="post" enctype="multipart/form-data">
            <h2>Seller Information</h2>
            <div class="row">
                <div class="column half">
                    <label for="firstName">First Name</label>
                    <input type="text" id="firstName" name="firstName" required>
                </div>
                <div class="column half">
                    <label for="lastName">Last Name</label>
                    <input type="text" id="lastName" name="lastName" required>
                </div>
            </div>
            
            <label for="email">Email *</label>
            <input type="email" id="email" name="email" required>
            
            <div class="row">
                <div class="column half">
                    <label for="phone">Phone Number *</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
                <div class="column half">
                    <label for="birthDate">Birth Date</label>
                    <input type="date" id="birthDate" name="birthDate">
                </div>
            </div>

            <label for="address">Address *</label>
            <input type="text" id="address" name="address" required>
            
            <div class="row">
                <div class="column third">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city">
                </div>
                <div class="column third">
                    <label for="province">Province</label>
                    <input type="text" id="province" name="province">
                </div>
                <div class="column third">
                    <label for="zipCode">Zip Code</label>
                    <input type="text" id="zipCode" name="zipCode">
                </div>
            </div>
            
            <h2>Shop Information</h2>
            <div class="row">
                <div class="column half">
                    <label for="shopName">Shop Name</label>
                    <input type="text" id="shopName" name="shopName">
                </div>
                <div class="column half">
                    <label for="contactNumber">Contact Number</label>
                    <input type="text" id="contactNumber" name="contactNumber">
                </div>
            </div>
            
            <div class="row">
                <div class="column half">
                    <label for="shopEmail">Shop Email</label>
                    <input type="email" id="shopEmail" name="shopEmail">
                </div>
                <div class="column half">
                    <label for="websiteUrl">Website URL</label>
                    <input type="url" id="websiteUrl" name="websiteUrl">
                </div>
            </div>
            
            <div class="row">
                <div class="column half">
                    <label for="productType">Product Type</label>
                    <input type="text" id="productType" name="productType">
                </div>
                <div class="column half">
                    <label for="shopDescription">Describe your shop in detail</label>
                    <input type="text" id="shopDescription" name="shopDescription">
                </div>
            </div>
            
            <h2>Registration Information</h2>
            <div class="row">
                <div class="column half">
                    <label for="userName">User Name</label>
                    <input type="text" id="userName" name="userName" required>
                </div>
                <div class="column half">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>
            <div class="row">
                <div class="column half">
                    <label for="confirmPassword">Confirm Password</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
            </div>
            
            <div class="terms">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the terms and conditions.</label>
            </div>
            
            <button type="submit" class="register-btn">Register</button>
        </form>
    </div>
</body>
</html>
