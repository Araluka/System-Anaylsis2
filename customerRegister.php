<?php
include 'db_connect.php'; // รวมไฟล์การเชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งข้อมูลมาหรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $first_name = $_POST['FirstName'];
    $last_name = $_POST['LastName'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $phone_number = $_POST['Phone'];
    $birth_date = isset($_POST['BirthDate']) ? $_POST['BirthDate'] : null;
    $sex = isset($_POST['Sex']) ? $_POST['Sex'] : null;
    $address = $_POST['Address'];
    $city = $_POST['City'];
    $province = $_POST['Province'];
    $zip_code = $_POST['ZipCode'];

    // เก็บภาพโปรไฟล์ (หากมี)
    $image = null;
    if (isset($_FILES['Image']) && $_FILES['Image']['error'] == UPLOAD_ERR_OK) {
        $image = file_get_contents($_FILES['Image']['tmp_name']);
    }

    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL เพื่อเพิ่มข้อมูลลงในตาราง
    $sql = "INSERT INTO usercustomer (FirstName, LastName, Email, Password, Phone, BirthDate, Sex, Address, City, Province, `Zip Code`, Image, registration_date, Active)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 1)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssssssssssss', $first_name, $last_name, $email, $hashed_password, $phone_number, $birth_date, $sex, $address, $city, $province, $zip_code, $image);
    
    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
    <link rel="stylesheet" href="css/stylesRegis.css"> <!-- Assuming you have a CSS file for styling -->
</head>
<body>
    <div class="registration-form">
        <h2>Customer Registration</h2>
        <form action="customerRegister.php" method="post" enctype="multipart/form-data">
            <!-- Header -->
            <div class="header">
                <p>Customer Details:</p>
            </div>
            <!-- First Name and Last Name -->
            <div class="form-row">
                <div class="form-group">
                    <label for="first-name">First Name</label>
                    <input type="text" id="first-name" name="FirstName" required>
                </div>
                <div class="form-group">
                    <label for="last-name">Last Name</label>
                    <input type="text" id="last-name" name="LastName" required>
                </div>
            </div>

            <!-- Email and Password -->
            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="Password" required>
                </div>
            </div>

            <!-- Phone Number, Birth Date, and Sex -->
            <div class="form-row">
                <div class="form-group">
                    <label for="phone-number">Phone Number *</label>
                    <input type="tel" id="phone-number" name="Phone" required>
                </div>
                <div class="form-group">
                    <label for="birth-date">Birth Date</label>
                    <input type="date" id="birth-date" name="BirthDate">
                </div>
                <div class="form-group">
                    <label for="sex">Sex</label>
                    <select id="sex" name="Sex">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <!-- Address, City, Province, and Zip Code -->
            <div class="address-row">
                <label for="address">Address *</label>
                <input type="text" id="address" name="Address" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="City">
                </div>
                <div class="form-group">
                    <label for="province">Province</label>
                    <input type="text" id="province" name="Province">
                </div>
                <div class="form-group">
                    <label for="zip-code">Zip Code</label>
                    <input type="text" id="zip-code" name="ZipCode">
                </div>
            </div>

            <!-- Image Upload -->
            <div class="form-row">
                <div class="form-group">
                    <label for="image">Profile Image</label>
                    <input type="file" id="image" name="Image">
                </div>
            </div>

            <!-- Social Buttons -->
            <div class="social-buttons">
                <a href="#" class="social-button google-button">
                    <img src="image/google-icon.png" alt="Google" class="social-icon">
                    <span class="button-text">Google</span>
                </a>
                <a href="#" class="social-button Facebook-button">
                    <img src="image/Facebook-icon.png" alt="Facebook" class="social-icon">
                    <span class="button-text">Facebook</span>
                </a>
            </div>
            
            <!-- Lower -->
            <div class="Lower">
                <p>By signing up, you agree to our Terms of Service and Privacy Policy</p>
            </div>

            <!-- Register Button -->
            <button type="submit" class="register-button">Register</button>
        </form>
    </div>
</body>
</html>