<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

$username = aquire_username_or_default(DEFAULT_USERNAME);


ob_start(); //start buffer to collect generated html lines
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="order.css">
</head>
<body>
    <div class="container">
        <!-- Left Side: Order List -->
        <div class="order-list">
            <h2>Order List</h2>
            <div class="order-item" onclick="showOrderDetails('order1')">
                <p><strong>Order Time:</strong> August 25, 2024, 2:13pm</p>
                <p><strong>Order ID:</strong> 5789</p>
                <p><strong>Order Type:</strong> Deliver</p>
                <p><strong>Order Status:</strong> Cooking 😊</p>
                <button>Details</button>
                <button>Rate</button>
            </div>
            <div class="order-item" onclick="showOrderDetails('order2')">
                <p><strong>Order Time:</strong> August 24, 2024, 1:49pm</p>
                <p><strong>Order ID:</strong> 5619</p>
                <p><strong>Order Type:</strong> Deliver</p>
                <p><strong>Order Status:</strong> Completed ✅</p>
                <button>Details</button>
                <button>Rate</button>
            </div>
            <!-- Add more orders here as needed -->
        </div>

        <!-- Right Side: Order Details -->
        <div class="order-details" id="order-details">
            <h2>Order Details</h2>
            <div id="order1" class="order-detail-content">
                <p><strong>Order Time:</strong> August 25, 2024, 2:13pm</p>
                <p><strong>Order ID:</strong> 5789</p>
                <p><strong>Paid By:</strong> Visa (xxxx-5617)</p>
                <p><strong>Delivery Address:</strong> Wall Street 420 #6-90</p>
                <hr>
                <p><strong>Items:</strong></p>
                <ul>
                    <li>1x Shrimp Fried Rice
                    </li>
                    <li>2x Pork Fried Rice - $17.00</li>
                    <li>1x Chicken Chop Fried Rice Set - $16.00</li>
                    <li>2x Lemon Barley Drink - Included</li>
                    <li>1x Coca Cola 1L - $2.50</li>
                </ul>
                <hr>
                <p><strong>Total:</strong> $42.00</p>
                <p><strong>Delivery:</strong> $5.00</p>
                <p><strong>9% GST:</strong> $4.23</p>
                <p><strong>Grand Total:</strong> $51.23</p>
                <button class="rate-button">Rate</button>
                <button class="order-again-button">Order Again</button>
            </div>

            <!-- Additional order details can be added here with unique IDs -->
        </div>
    </div>

    <script src="order.js"></script>
</body>
</html>



<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(ORDER_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $username)
    ->set_outline(ORDER_PAGE_SCRIPTS)
    ->render();
?>