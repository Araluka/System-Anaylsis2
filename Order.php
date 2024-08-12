<?php
include 'auth.php';
include 'db_connect.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit(); // ใช้ exit() หลังจาก header() เพื่อหยุดการทำงานของสคริปต์
}

// รับค่า product_id และ quantity จาก GET
$product_id = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
$quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1; // ค่าเริ่มต้นเป็น 1 หากไม่ได้ส่ง

if ($product_id <= 0) {
    die("Invalid product ID.");
}

// รับค่า payment_method จาก POST (หากมีการส่ง POST)
$payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';

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

// หากมีการส่ง POST, บันทึกข้อมูลการสั่งซื้อ
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $order_sql = "INSERT INTO orders (CustomerID, ProductID, Quantity, PaymentMethod) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($order_sql);
    $stmt->bind_param("iiis", $customer_id, $product_id, $quantity, $payment_method);
    $stmt->execute();
    
    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
    
    // ยืนยันการสั่งซื้อ
    echo "Order placed successfully!";
    exit();
}

// ปิดการเชื่อมต่อก่อนเริ่มการแสดงผล HTML
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Acme&family=Anton&family=Bungee+Shade&family=Bungee+Spice&family=Concert+One&family=Kalam:wght@300;400;700&family=Lilita+One&family=Luckiest+Guy&family=Sriracha&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/order.js"></script>
    <style>
        .main-content {
            display: flex;
            flex-direction: column;
            padding: 10px;
            font-weight: bold;
            margin: 0 10%;
        }

        .main-content h1 {
            font-size: 48px;
            margin: 0;
            padding: 20px 0;
            align-self: flex-start;
        }

        .promo-box {
            background-color: #F6F6F6;
            border-radius: 12px;
            padding: 40px;
            width: 80%;
            margin-bottom: 40px;
            position: relative;
        }

        .product {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .product img {
            width: 350px;
            height: 350px;
            object-fit: cover;
            border-radius: 12px;
        }

        .product-details {
            text-align: left;
            color: #333;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-details h2 {
            font-size: 64px;
            font-weight: bold;
            text-transform: lowercase;
            font-family: 'Anton', sans-serif;
        }

        .product-details p {
            margin: 10px 0;
            font-size: 20px;
            font-family: 'Anton', sans-serif;
        }

        .price {
            color: #FFC0CB;
            font-size: 24px;
            font-weight: bold;
            font-family: 'Anton', sans-serif;
        }

        .quantity {
            display: flex;
            align-items: center;
            margin: 20px 0;
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

        .payment-section {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            gap: 20px;
        }

        .payment-options {
            display: flex;
            flex-direction: column;
            gap: 10px;
            flex: 1;
        }

        .payment-options .option {
            background-color: #F0F0F0;
            border-radius: 8px;
            padding: 10px;
            text-align: left;
            font-size: 24px;
        }

        .payment-options .note {
            font-size: 14px;
            margin-top: 5px;
            color: #555;
        }

        .payment-options button {
            background-color: #FFC0CB;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 18px;
            color: #333;
            border-radius: 8px;
            margin-top: 10px;
        }

        .shipping-info {
            text-align: left;
            font-size: 18px;
            margin-top: 20px;
            flex: 1;
        }

        .shipping-info h3 {
            margin: 0;
            color: #004d00;
            font-size: 24px;
        }

        .order-summary {
            text-align: left;
            font-size: 18px;
            margin-top: 20px;
            position: absolute;
            bottom: 100px;
            right: 20px;
        }

        .order-summary div {
            margin-bottom: 10px;
        }

        .order-button {
            background-color: #FFC0CB;
            border: none;
            padding: 15px 30px;
            cursor: pointer;
            font-size: 24px;
            color: #333;
            border-radius: 8px;
            text-transform: uppercase;
            position: absolute;
            bottom: 20px;
            right: 20px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }
                .shipping-info .address-line {
            display: flex; /* Arrange elements in a row */
            gap: 10px; /* Space between elements */
        }

        .shipping-info p {
            margin: 0; /* Remove default margin */
        }   
        </style>
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
    
    <div class="main-content">
        <h1>Order</h1>
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

            <!-- Payment Section -->
            <div class="payment-section">
                <div class="payment-options">
                    <div class="option">
                        <strong>Price per unit:</strong>
                        ฿<?php echo number_format($product['Price'], 2); ?>
                    </div>
                    <div class="option">
                        <strong>Count:</strong>
                        <?php echo number_format($quantity); ?>
                    </div>
                    <div class="option">
                        <strong>Shipping Option:</strong>
                        International Express
                        <div class="note">
                            You will receive the product between August 11 - August 15.
                        </div>
                        <div>
                            ฿39.00
                        </div>
                    </div>
                    
                    <!-- Payment Buttons -->
                    <form id="payment-form" action="Order.php" method="POST">
                        <!-- Payment Buttons -->
                        <button type="button" id="credit-debit-card" onclick="setPaymentMethod('Credit/Debit Card', this)">Credit/Debit Card</button>
                        <button type="button" id="cash-on-delivery" onclick="setPaymentMethod('Cash on Delivery', this)">Cash on Delivery</button>
                        <input type="hidden" name="payment_method" id="payment-method">
                        <a href="#" id="order-button" class="order-button">Order Products</a>
                    </form>

                    
                </div>

                <!-- Shipping Address -->
                <div class="shipping-info">
                    <h3>Shipping Address</h3>
                    <p><?php echo htmlspecialchars($customer['Address']); ?></p>
                    <div class="address-line">
                    <p><?php echo htmlspecialchars($customer['City']); ?></p>
                    <p><?php echo htmlspecialchars($customer['Province']); ?></p>
                    <p><?php echo htmlspecialchars($customer['Zip Code']); ?></p>
                    </div>
                    <p><?php echo htmlspecialchars($customer['Phone']); ?></p>
                </div>
                <!-- Order Summary -->
                <div class="order-summary">
                    <div>Total Order    : ฿<?php echo number_format($product['Price'] * $quantity, 2); ?></div>
                    <div>Total Shipping : ฿39.00</div>
                    <div>Total Payment  : ฿<?php echo number_format(($product['Price'] * $quantity) + 39.00, 2); ?></div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>