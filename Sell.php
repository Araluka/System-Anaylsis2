<?php
// เริ่ม session
session_start();

// เชื่อมต่อกับฐานข้อมูล
include 'db_connect.php';

// ตรวจสอบว่ามีการส่งข้อมูลมาแบบ POST หรือไม่
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $condition = $_POST['condition'];
    $description = $_POST['description'];
    
    // ตรวจสอบว่ามีการอัพโหลดรูปภาพหรือไม่
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);
    } else {
        $imageData = null; // หากไม่มีการอัพโหลดภาพ
    }

    // เพิ่มข้อมูลสินค้าเข้าในตาราง product
    $sql = "INSERT INTO product (Name, Description, Price, StockQuantity, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stock_quantity = 1; // กำหนดค่าปริมาณในสต็อกเป็น 1 สำหรับสินค้าใหม่
    $stmt->bind_param("ssdss", $name, $description, $price, $stock_quantity, $imageData);

    if ($stmt->execute()) {
        echo "Product added successfully!";
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
    <title>Sell Page</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Anton&family=Bungee+Shade&family=Bungee+Spice&family=Concert+One&family=Kalam:wght@300;400;700&family=Lilita+One&family=Luckiest+Guy&family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/stylesSell.css">
    <style>
    
       
    </style>
</head>
<body>
    
    <div class="container">
        <div class="header">
            <h1>Sell</h1>
        </div>
        <div class="form-container">
            <form action="Sell.php" method="post" enctype="multipart/form-data">
                <div class="photo-upload">
                    <div class="photo-placeholder">
                        <img src="image/image01.png" alt="Add photos" id="photo-preview">
                        <input type="file" name="image" accept="image/*" onchange="previewImage(event)">
                        <p>Add photos</p>
                    </div>
                    <p class="photo-info">Photos: 0/10 Choose your listing's main photo first.</p>
                </div>
                <div class="address-row">
                    <input type="text" name="name" placeholder="Name" required>
                </div>
                <div class="address-row">
                    <input type="text" name="price" placeholder="Price" required>
                </div>
                <div class="address-row">
                    <input type="text" name="category" placeholder="Category" required>
                </div>
                <div class="address-row">
                    <select name="condition" required>
                        <option value="">Condition</option>
                        <option value="New">New</option>
                        <option value="Used">Used</option>
                    </select>
                </div>
                <div class="address-row">
                    <input type="text" name="description" placeholder="Description">
    
                </div>
                <div class="submit-btn">
                    <button type="submit">DONE</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('photo-preview');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</body>
</html>