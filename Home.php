<?php
include 'db_connect.php';

// คำสั่ง SQL สำหรับดึงข้อมูลสินค้าสุ่ม 4 รายการ
$sql = "SELECT ProductID, Name, Image FROM product ORDER BY RAND() LIMIT 4";
$result = $conn->query($sql);

// ตรวจสอบว่ามีข้อมูลสินค้าหรือไม่
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $products = []; // หากไม่มีข้อมูล ให้ $products เป็น array ว่าง
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Second-Hand Figure Shop</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Anton&family=Bungee+Shade&family=Bungee+Spice&family=Concert+One&family=Kalam:wght@300;400;700&family=Lilita+One&family=Luckiest+Guy&family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .promo-box {
            background-color: #F6F6F6;
            border-radius: 12px;
            padding: 40px;
            width: 80%;
            text-align: center;
            margin-bottom: 40px;
        }

        .promo-box h1 {
            font-size: 64px;
            margin: 0;
        }

        .promo-box p {
            font-size: 20px;
            margin: 20px 0 0;
        }

        .product-gallery {
            display: flex;
            justify-content: center;
            gap: 30px;
        }

        .product-gallery .product {
            text-align: center;
        }

        .product-gallery img {
            width: 350px;
            height: 350px;
            object-fit: cover;
            border-radius: 12px;
        }

        .product-gallery .product p {
            margin: 10px 0 0;
            font-size: 24px;
            font-weight: bold;
            color: black;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="header-container">
        <div class="header-top">
            <div class="shop-name">Second-Hand Figure Shop</div>
        </div>
        <div class="header-bottom">
            <a href="Home.html" class="home-icon"></a>
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
    </div>
    <div class="nav">
        <div class="nav-item">
            <a href="#">Category</a>
            <div class="dropdown">
                <a href="#">Option 1</a>
                <a href="#">Option 2</a>
                <a href="#">Option 3</a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#">All Product</a>
            <div class="dropdown">
                <a href="#">Option 1</a>
                <a href="#">Option 2</a>
                <a href="#">Option 3</a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#">Member</a>
            <div class="dropdown">
                <a href="#">Option 1</a>
                <a href="#">Option 2</a>
                <a href="#">Option 3</a>
            </div>
        </div>
        <div class="nav-item">
            <a href="#">Support</a>
            <div class="dropdown">
                <a href="#">Option 1</a>
                <a href="#">Option 2</a>
                <a href="#">Option 3</a>
            </div>
        </div>
    </div>
    
    <div class="main-content">
        <div class="promo-box">
            <h1>NEW PRODUCT</h1>
            <p>MEGA SPACE MOLLY 100% BLIND BOX SERIES 01</p>
            <div class="product-gallery">
                <?php if (!empty($products)) : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="product">
                            <a href="product.php?id=<?php echo $product['ProductID']; ?>">
                                <?php
                                // ดึงข้อมูลไบต์ของภาพ
                                $imageData = $product['Image'];
                                // เข้ารหัสเป็น Base64
                                $base64Image = base64_encode($imageData);
                                // ระบุชนิดของข้อมูล
                                $imageType = 'image/jpeg'; // หรือชนิดอื่น ๆ เช่น 'image/png'
                                // สร้าง URL สำหรับรูปภาพ
                                $imageSrc = 'data:' . $imageType . ';base64,' . $base64Image;
                                ?>
                                <img src="<?php echo $imageSrc; ?>" alt="<?php echo htmlspecialchars($product['Name']); ?>">
                                <p><?php echo htmlspecialchars($product['Name']); ?></p>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>No products available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
