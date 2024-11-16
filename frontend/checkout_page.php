<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";
require_once "../backend/core/menu_packages.php";


session_status() === PHP_SESSION_NONE ? session_start(): null;

$username = aquire_username_or_default(DEFAULT_USERNAME);

//var_dump($_SESSION['cart']);

$packages_ordered_info =[];
foreach($_SESSION['cart'] as $package_id=>$quantity){
    if($quantity != 0 && $quantity !='0'){
        $package_info = MenuPackages::get_package_information_by_id($package_id);
        $package_info['quantity_order'] = $quantity; 
        $packages_ordered_info[] = $package_info;
    }
}
$total_price = 0;

// var_dump($packages_ordered_info);

$is_user_logged_in = isset($_SESSION['user_id']);
if($is_user_logged_in){
    function get_user_preferences(){
        ////check that session user_id
        if (session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if(!isset($_SESSION['user_id'])){
            throw new Exception('user not logged in', ERRORCODES::general_error['bad_request']);
        }
        ////query database
        $sql = "
        SELECT name, 
            address, 
            preferred_payment_method, 
            is_notify_by_sms, 
            is_notify_by_email, 
            is_notify_by_whatsapp, 
            is_notify_by_telegram
        FROM user_preferences
        WHERE user_id = ?;
        ";
        $results = NyanDB::single_query($sql, [$_SESSION['user_id']]);
        if(!empty($result)){
            throw new Exception('user does not exist', ERRORCODES::server_error['user_does_not_exist']);
        }
        $result = $results->fetch_assoc();
        $results->free();
    
    
    
        ////successfully retreived $the user_preference information
        return $result;
    }
    $user_preferences = get_user_preferences();
    // var_dump($user_preferences);
    $user_name                 = $user_preferences['name'];
    $address                   = $user_preferences['address'];
    $preferred_payment_method  = $user_preferences['preferred_payment_method'];
}


ob_start(); //start buffer to collect generated html lines
?>

<div class="grid-container">
    <div class="container">
        <section class="section">
            <h2>Order Summary</h2>
                <?php foreach ($packages_ordered_info as $item): 
                    $package_id = $item['package_id'];
                    $package_total_guest_cost = $item['quantity_order']*$item['price'];
                    $total_price += $package_total_guest_cost;
                    ?>
                    <br>
                    <h3><?=$item['item_name']?></h3>
                    <div class="summary-item">
                        <span><?=$item['main_name']?></span>
                    </div>
                    <div class="summary-item">
                        <span><?=$item['side_name']?></span>
                    </div>
                    <div class="summary-item">
                        <span><?=$item['dessert_name']?></span>
                    </div>
                    <div class="summary-item">
                        <span><?=$item['drink_name']?></span>
                    </div>
                    <div class="summary-item total">
                        <span><?=$item['quantity_order']?> pax</span>
                        <span>$<?=$package_total_guest_cost?></span>
                    </div>
                    <hr>
                <?php endforeach; ?>
            <br>
        </section>
        <h3>
            Total Price: $<?=$total_price?>
        </h3>
    </section>
</div>
    
<form id="checkout-form" action="#" method="post">
    <div class="container">
            <h2>Checkout</h2>

            <section class="section">
                <h3>Shipping Information</h3>
                <div class="input-group">
                    <label for="full-name">Full Name</label>
                    <input class="input-disable" type="text" id="full-name" name="full_name" value='<?=$user_name?>' readonly>
                </div>
                <div class="input-group">
                    <label for="address">Address</label>
                    <input class="input-disable" type="text" id="address" name="address" value='<?=$address?>' readonly>
                </div>
            </section>

            <section class="section">
                <h3>Payment Information</h3>
                New user? Edit your payment methods <a href="account_setting_page.php">here</a>
                <br><br>
                <div class="input-group">
                    <label for="card-number">Card Number</label>
                    <input class="input-disable" type="text" id="card-number" name="card_number" value='<?=$preferred_payment_method?>' readonly>
                </div>
            </section>

            <section class="section">
                <h3>Shipping Information</h3>
                <div class="input-group">
                    <label for="start_datetime">Start time</label>
                    <input class="input-date" type="datetime-local" id="start_datetime" name="full_name">
                </div>
                <div class="input-group">
                    <label for="end_datetime">Start time</label>
                    <input class="input-date" type="datetime-local" id="end_datetime" name="full_name">
                </div>
            </section>

            <div class="submit-button">
            <button type="button">Place Order</button>
        </div>
        </div>
    </div>
</form>
<script>
    window.onload = function() {
        if(!<?=$is_user_logged_in?>){
            alert('please make an account before making an order')
            window.location.href = "sign_in_up_page.php";
        }
    };
</script>

    
           

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