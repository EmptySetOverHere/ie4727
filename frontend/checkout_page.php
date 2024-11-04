<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

$username = aquire_username_or_default(DEFAULT_USERNAME);

ob_start(); //start buffer to collect generated html lines
?>

<form id="checkout-form" action="#" method="post">
<div class="grid-container">
    <div class="container">
        <section class="section">
            <h3>Order Summary</h3>
            <div class="summary-item">
                <span>Item 1</span>
                <span>$10.00</span>
            </div>
            <div class="summary-item">
                <span>Item 2</span>
                <span>$15.00</span>
            </div>
            <div class="summary-item total">
                <span>Total</span>
                <span>$25.00</span>
            </div>
        </section>

        <div class="submit-button">
            <button type="submit">Place Order</button>
        </div>
    </div>

    <div class="container">
            <h2>Checkout</h2>

            <section class="section">
                <h3>Shipping Information</h3>
                <div class="input-group">
                    <label for="full-name">Full Name</label>
                    <input type="text" id="full-name" name="full_name" required>
                </div>
                <div class="input-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" required>
                </div>
                <div class="input-group">
                    <label for="city">City</label>
                    <input type="text" id="city" name="city" required>
                </div>
                <div class="input-group">
                    <label for="state">State/Province</label>
                    <input type="text" id="state" name="state" required>
                </div>
                <div class="input-group">
                    <label for="zip">ZIP/Postal Code</label>
                    <input type="text" id="zip" name="zip" required>
                </div>
                <div class="input-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" required>
                </div>
            </section>

            <section class="section">
                <h3>Payment Information</h3>
                <div class="input-group">
                    <label for="card-number">Card Number</label>
                    <input type="text" id="card-number" name="card_number" required>
                </div>
                <div class="input-group">
                    <label for="expiry-date">Expiry Date (MM/YY)</label>
                    <input type="text" id="expiry-date" name="expiry_date" required>
                </div>
                <div class="input-group">
                    <label for="cvc">CVC</label>
                    <input type="text" id="cvc" name="cvc" required>
                </div>
            </section>
        </div>
    </div>
</form>

    
           

<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(CHECKOUT_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $username)
    ->set_outline(CHECKOUT_PAGE_SCRIPTS)
    ->render();
?>