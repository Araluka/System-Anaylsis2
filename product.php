<?php
include 'db_connect.php';

// ตรวจสอบว่ามีการส่ง ID มาใน URL หรือไม่
if (isset($_GET['id'])) {
    $productID = intval($_GET['id']);
    
    // คำสั่ง SQL สำหรับดึงข้อมูลสินค้าตาม ID
    $sql = "SELECT ProductID, Name, Description, Price, StockQuantity, Image FROM product WHERE ProductID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $productID);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // ตรวจสอบว่ามีข้อมูลสินค้าหรือไม่
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        die("Product not found.");
    }
} else {
    die("Invalid product ID.");
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Details</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Anton&family=Bungee+Shade&family=Bungee+Spice&family=Concert+One&family=Kalam:wght@300;400;700&family=Lilita+One&family=Luckiest+Guy&family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
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

        main {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 80vh; /* ให้ main มีความสูง 80% ของหน้าจอ */
        }

        .product {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .product img {
            max-width: 500px;
            margin-right: 20px;
        }

        .product-details {
            text-align: left;
        }

        .product-details h2 {
            margin: 0 0 10px;
            font-size: 64px;
            font-weight: bold;
            text-transform: lowercase;
            font-family: 'Anton', sans-serif;
        }

        .product-details p {
            margin: 0 0 20px;
            font-size: 20px;
            font-family: 'Anton', sans-serif;
        }

        .price {
            color: #333;
            margin: 0 0 20px;
            font-size: 24px;
            font-weight: bold;
            font-family: 'Anton', sans-serif;
        }

        .quantity {
            display: flex;
            align-items: center;
            margin: 0 0 40px;
        }

        .quantity button {
            background-color: #ddd;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            font-size: 18px;
        }

        .quantity input {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            margin: 0 5px;
            font-size: 18px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .actions .add-to-cart,
        .actions .buy-now {
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 24px;
            text-transform: lowercase;
            font-family: 'Anton', sans-serif;
        }

        .add-to-cart {
            background-color: #FFC0CB;
            color: #333;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none; 
        }

        .buy-now {
            background-color: #FFC0CB;
            color: #333;
            font-size: 24px;
            font-weight: bold;
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
            <a href="Home.html" class="home-icon"> </a>
            <div class="icon-container">
                <a href="#" class="user-icon">
                    <img src="image/people.png" alt="User"> <!-- เปลี่ยน URL ตามที่คุณมี -->
                </a>
                <a href="#" class="cart-icon">
                    <img src="image/cart.png" alt="Cart"> <!-- เปลี่ยน URL ตามที่คุณมี -->
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
    </div>
    <div class="main-content">
    <div class="promo-box">
        <section class="product">
            <img src="data:image/jpeg;base64,<?php echo base64_encode($product['Image']); ?>" alt="<?php echo htmlspecialchars($product['Name']); ?>">
            <div class="product-details">
                <h2><?php echo htmlspecialchars($product['Name']); ?></h2>
                <p><?php echo htmlspecialchars($product['Description']); ?></p>
                <p class="price">฿<?php echo htmlspecialchars($product['Price']); ?></p>
                <div class="quantity">
                    <button onclick="decreaseQuantity()">-</button>
                    <input type="number" id="quantityInput" value="1" min="1">
                    <button onclick="increaseQuantity()">+</button>
                </div>
                <div class="actions">
                    <button class="add-to-cart">Add to Cart</button>
                    <form action="order.php" method="GET" id="orderForm">
                        <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
                        <input type="hidden" name="quantity" id="quantityInputHidden" value="1">
                        <button type="submit" class="buy-now">Buy Now</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    const stockQuantity = <?php echo $product['StockQuantity']; ?>;
    
    function decreaseQuantity() {
        const input = document.getElementById('quantityInput');
        let currentValue = parseInt(input.value);
        if (currentValue > 1) {
            input.value = currentValue - 1;
            document.getElementById('quantityInputHidden').value = input.value;
        }
    }
    
    function increaseQuantity() {
        const input = document.getElementById('quantityInput');
        let currentValue = parseInt(input.value);
        if (currentValue < stockQuantity) {
            input.value = currentValue + 1;
            document.getElementById('quantityInputHidden').value = input.value;
        }
    }
</script>
</body>
</html>
