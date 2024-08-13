<?php
// รวมไฟล์ auth.php ที่ทำการตรวจสอบการล็อกอินและดึงข้อมูลผู้ใช้
include 'auth.php';

// ตรวจสอบว่าผู้ใช้เข้าสู่ระบบหรือไม่
if (!$is_logged_in) {
    // ถ้าผู้ใช้ไม่ได้ล็อกอินให้เปลี่ยนเส้นทางไปที่หน้า login.php
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Anton&family=Bungee+Shade&family=Bungee+Spice&family=Concert+One&family=Kalam:wght@300;400;700&family=Lilita+One&family=Luckiest+Guy&family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/regisCustomer.css">
    <script src="js/overlay.js" defer></script>
    <style>
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .message-box {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px 40px;
            text-align: center;
        }

        .message-box p {
            font-size: 18px;
            color: #000;
        }

        .profile-container {
            max-width: 600px;
            margin: auto;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .profile-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-container .form-group {
            margin-bottom: 15px;
        }

        .profile-container .form-group label {
            display: block;
            font-weight: bold;
        }

        .profile-container .form-group p {
            margin: 0;
        }

        .profile-container img {
            display: block;
            margin: 20px auto;
            border-radius: 50%;
            max-width: 150px;
            max-height: 150px;
        }
    </style>
</head>
<body>
    <div class="profile-container">
        <h2>User Profile</h2>

        <!-- Check if user data is available -->
        <?php if ($user_data): ?>
            <!-- First Name and Last Name -->
            <div class="form-group">
                <label>First Name:</label>
                <p><?php echo htmlspecialchars($user_data['FirstName']); ?></p>
            </div>
            <div class="form-group">
                <label>Last Name:</label>
                <p><?php echo htmlspecialchars($user_data['LastName']); ?></p>
            </div>

            <!-- Email and Phone Number -->
            <div class="form-group">
                <label>Email:</label>
                <p><?php echo htmlspecialchars($user_data['Email']); ?></p>
            </div>
            <div class="form-group">
                <label>Phone Number:</label>
                <p><?php echo htmlspecialchars($user_data['Phone']); ?></p>
            </div>

            <!-- Birth Date and Sex -->
            <div class="form-group">
                <label>Birth Date:</label>
                <p><?php echo htmlspecialchars($user_data['BirthDate']); ?></p>
            </div>
            <div class="form-group">
                <label>Sex:</label> 
                <p><?php echo htmlspecialchars($user_data['Sex']); ?></p>
            </div>

            <!-- Address, City, Province, and Zip Code -->
            <div class="form-group">
                <label>Address:</label>
                <p><?php echo htmlspecialchars($user_data['Address']); ?></p>
            </div>
            <div class="form-group">
                <label>City:</label>
                <p><?php echo htmlspecialchars($user_data['City']); ?></p>
            </div>
            <div class="form-group">
                <label>Province:</label>
                <p><?php echo htmlspecialchars($user_data['Province']); ?></p>
            </div>
            <div class="form-group">
                <label>Zip Code:</label>
                <p><?php echo htmlspecialchars($user_data['Zip Code']); ?></p>
            </div>

            <!-- Profile Image -->
            <div class="form-group">
                <label>Profile Image:</label>
                <?php if ($user_data['Image']): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($user_data['Image']); ?>" alt="Profile Image">
                <?php else: ?>
                    <p>No image uploaded</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <p>No user data available.</p>
        <?php endif; ?>
    </div>
</body>
</html>