<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";
require_once '../backend/core/constants/Errorcodes.php';
require_once '../backend/core/NyanDateTime.php';
require_once '../backend/core/menu_packages.php';
require_once '../backend/core/NyanDB.php';

session_status() === PHP_SESSION_NONE ? session_start(): null;

$username = aquire_username_or_default(DEFAULT_USERNAME);

$order_status = ["Cooking", "Completed", "Unsuccessful"];

$sql = "SELECT * from ";


ob_start(); //start buffer to collect generated html lines
?>  


<div class="main-container">
<div class="order-list">
    <div class="order-card" onclick="showOrderDetails(1)">
    <p>Order Time: August 25 2024, 2:13pm</p>
    <p>Order ID: 5789</p>
    <p>Order Type: Deliver</p>
    <p>Order Status: <span class="status cooking">Cooking</span></p>
    <button class="btn">Details</button>
    <button class="btn">Rate</button>
    </div>
    <div class="order-card" onclick="showOrderDetails(2)">
    <p>Order Time: August 24 2024, 1:49pm</p>
    <p>Order ID: 5619</p>
    <p>Order Type: Deliver</p>
    <p>Order Status: <span class="status completed">Completed</span></p>
    <button class="btn">Details</button>
    <button class="btn">Rate</button>
    </div>
    <div class="order-card" onclick="showOrderDetails(3)">
    <p>Order Time: August 23 2024, 2:23pm</p>
    <p>Order ID: 5431</p>
    <p>Order Type: Pick Up</p>
    <p>Order Status: <span class="status unsuccessful">Unsuccessful</span></p>
    <button class="btn">Details</button>
    <button class="btn disabled" disabled>Rate</button>
    </div>
</div>
<div class="order-details" id="order-details">
    <p>Order Time: August 25 2024, 2:13pm</p>
    <p>Order ID: 5789</p>
    <p>Order Type: Deliver</p>
    <p>Order Status: <span class="status cooking">Cooking</span></p>
    <hr style="margin-top: 20px;">
    <table>
    <tr>
        <th>Items</th>
        <th>Remarks</th>
        <th>Price</th>
    </tr>
    <tr>
        <td>1x Shrimp Fried Rice</td>
        <td>without shrimp</td>
        <td>8.50</td>
    </tr>
    <tr>
        <td>2x Pork Fried Rice</td>
        <td></td>
        <td>17.00</td>
    </tr>
    <tr>
        <td>1x Chicken Chop Fried Rice Set</td>
        <td>Chicken Chop Fried Rice x2 Lemon Barley Drink x2</td>
        <td>16.00</td>
    </tr>
    <tr>
        <td>1x Coca Cola 1L</td>
        <td></td>
        <td>2.50</td>
    </tr>
    </table>
    <hr style="margin-bottom: 20px;">
    <table>
        <tr>
            <td>
                <p>Sub Total: </p>
            </td>
            <td style="text-align: end;">$42.00</td>
        </tr>
        <tr>
            <td>
                <p>9% GST: </p>
            </td>
            <td style="text-align: end;">$2.78</td>
        </tr>
        <tr>
            <td>
                <p>Delivery: </p>
            </td>
            <td style="text-align: end">$5.00</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align: end;">
                <p><b>$51.23</b></p>
            </td>
        </tr>
    </table>

    <form action="./feedback_page.php" method="get">
        <input type="number" hidden>
        <button class="btn" onclick="">Rate</button>
    </form>
    <button class="btn" onclick="orderAgain()">Order Again</button>
</div>
</div>




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