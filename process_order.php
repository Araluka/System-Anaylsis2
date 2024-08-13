<?php
include 'auth.php';
include 'db_connect.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// รับข้อมูลจาก POST
$product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

// ตรวจสอบค่า product_id
if ($product_id <= 0) {
    die("Invalid product ID.");
}

// ดึงข้อมูลผลิตภัณฑ์
$product_sql = "SELECT * FROM product WHERE ProductID = ?";
$stmt = $conn->prepare($product_sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// ดึงข้อมูลผู้ใช้จากเซสชัน
$customer_id = $_SESSION['customer_id'];
$customer_sql = "SELECT * FROM usercustomer WHERE CustomerID = ?";
$stmt = $conn->prepare($customer_sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$customer_result = $stmt->get_result();
$customer = $customer_result->fetch_assoc();

if (!$customer) {
    die("Customer not found.");
}

// คำนวณข้อมูลการสั่งซื้อ
$total_amount = $product['Price'] * $quantity;
$shipping_cost = 39.00;
$total_payment = $total_amount + $shipping_cost;
$order_date = date('Y-m-d H:i:s');
$order_status = 'Pending';

// บันทึกข้อมูลการสั่งซื้อ
$order_sql = "INSERT INTO orderproduct (OrderDate, Count, TotalAmount, ShippingAddress, OrderStatus, payment_method) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($order_sql);
$stmt->bind_param("sissss", $order_date, $quantity, $total_amount, $customer['Address'], $order_status, $payment_method);
$stmt->execute();

// ปิดการเชื่อมต่อ
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OrderComplete</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Anton&family=Bungee+Shade&family=Bungee+Spice&family=Concert+One&family=Kalam:wght@300;400;700&family=Lilita+One&family=Luckiest+Guy&family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .promo-box {
            background-color: #F6F6F6;
            border-radius: 12px;
            padding: 40px;
            width: 60%;
            height: 60%;
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
            width: 200px;
            height: 200px;
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

        main {
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 80vh;
        }
    </style>
</head>
<body>
<div class="header-container">
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
</div>
    <div class="nav">
        <div class="nav-item">
            <a href="#"><h1>คำสั่งซื้อของคุณ<h1></a>
        </div>
    </div>
    <div class="main-content">
        <div class="promo-box">
            <section class="product">
                <?php if ($product['image']): ?>
                    <img src="data:image/png;base64,<?php echo base64_encode($product['image']); ?>" alt="<?php echo htmlspecialchars($product['Name']); ?>">
                <?php else: ?>
                    <img src="image/default.png" alt="Default Image">
                <?php endif; ?>
                <div class="product-details">
                    <h2><?php echo htmlspecialchars($product['Name']); ?></h2>
                    <p><?php echo htmlspecialchars($product['Description']); ?></p>
                </div>
            </section>
            <p>คำสั่งซื้อของคุณได้ถูกบันทึกเรียบร้อยแล้ว!</p>
        </div>
    </div>
</body>
</html>
