<?php
include 'auth.php';
include 'db_connect.php';

$search_query = "";
if (isset($_POST['search'])) {
    $search_query = $_POST['search'];
}

// Query to search the product table based on the Name and Description
$sql = "SELECT ProductID, Name, Description, Price, StockQuantity, Image FROM product WHERE Name LIKE '%$search_query%' OR Description LIKE '%$search_query%'";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Anton&family=Bungee+Shade&family=Bungee+Spice&family=Concert+One&family=Kalam:wght@300;400;700&family=Lilita+One&family=Luckiest+Guy&family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/loadHeader.js" defer></script>
    <style>
        .promo-box {
            background-color: #F6F6F6;
            border-radius: 12px; /* เพิ่มความโค้งมนของมุม */
            padding: 40px; /* เพิ่ม padding */
            width: 80%;
            text-align: center;
            margin-bottom: 40px; /* เพิ่มระยะห่าง */
        }

        .promo-box h1 {
            font-size: 64px; /* ขนาดตัวอักษรใหญ่ขึ้น */
            margin: 0;
        }

        .promo-box p {
            font-size: 20px; /* ขนาดตัวอักษรใหญ่ขึ้น */
            margin: 20px 0 0; /* เพิ่มระยะห่าง */
        }

        .product-gallery {
            display: flex;
            justify-content: center;
            gap: 30px; /* เพิ่มระยะห่างระหว่างรูปภาพ */
        }

        .product-gallery .product {
            text-align: center;
        }

        .product-gallery img {
            width: 350px; /* ขนาดภาพใหญ่ขึ้น */
            height: 350px;
            object-fit: cover;
            border-radius: 12px; /* เพิ่มความโค้งมนของมุม */
        }

        .product-gallery .product p {
            margin: 10px 0 0;
            font-size: 24px; /* ขนาดตัวอักษรใหญ่ขึ้น */
            font-weight: bold; /* ตัวหนา */
            color: black; /* สีตัวอักษรดำ */
            text-decoration: none; /* ไม่มีเส้นขีดด้านใต้ */
        }
    </style>
</head>
<body>
<div id="header-nav-container"></div> 
    <div class="main-content">
    <h1>Search</h1>
    <div class="promo-box">
        <div class="product-gallery">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="container">';
                    echo '<a href="product.php?id=' . $row['ProductID'] . '">';
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['Image']) . '" alt="' . $row['Name'] . '">';
                    echo '<p>' . $row['Name'] . '</p>';
                    echo '<p>Price: ฿ ' . $row['Price'] . '</p>';
                    echo '</a>';
                    echo '</div>'; // ปิด container
                }
            } else {
                echo '<p>No products found</p>';
            }
            ?>
        </div> <!-- ปิด product-gallery -->
    </div> <!-- ปิด promo-box -->
</div> <!-- ปิด main-content -->
</body>
</html>

