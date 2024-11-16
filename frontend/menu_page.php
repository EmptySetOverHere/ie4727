<?php
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";
require_once "../backend/core/menu_packages.php";

session_status() === PHP_SESSION_NONE ? session_start() : null;

foreach($_POST as $package_id=>$quantity){
    if($quantity != 0){
        $_SESSION['cart'][$package_id] = $quantity;
    } else {
        unset($_SESSION['cart'][$package_id]);
    }
    if(isset($_GET['proceed_to_checkout'])){
        header("Location: checkout_page.php");
    }
}
if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}


function generate_menu_page(): string
{
    $display_category = $_GET['display_category'] ?? 'buffet';

    // $current_page = $_GET['current_page'] ?? '1';
    $display_items = [
        'bento' => [],
        'buffet' => [],
    ];

    $latest_packages = MenuPackages::get_latest_instock_package_information();

    foreach($latest_packages as $package){
        if($package['category']=='buffet'){
            $display_items['buffet'][$package['package_id']] = $package;
        }
        if($package['category']=='bento'){
            $display_items['bento'][$package['package_id']] = $package;
        }
    }

    $othercart_is_empty = true;
    foreach($_SESSION['cart'] as $package_id=>$quantity){
        // var_dump($latest_packages[$package_id]);
        $type_this_package_is = $latest_packages[$package_id]['category'];
        // var_dump($type_this_package_is);
        if($quantity !=0 && $type_this_package_is != $display_category){
            $othercart_is_empty = false;
        }
    }
    // var_dump($othercart_is_empty);

    // var_dump($display_items);

    ob_start(); //start buffer to collect generated html lines
?>

    <div class="main-content-container">
        <h1 class="title-our-menu">Our Menu</h1>        
        <div>
            <!-- for buffet -->
            <button
                class='display-category-toggle-container' <?= $display_category == 'buffet' ? 'disabled' : ''; ?>
                onclick="redirect_back(display_category='buffet')">
                <label>Buffet</label>
            </button>
            <!-- for bento -->
            <button
                class='display-category-toggle-container' <?= $display_category == 'bento' ? 'disabled' : ''; ?>
                onclick="redirect_back(display_category='bento')">
                <label>Bento</label>
            </button>
        </div>

        <form id='cart-form' method="post">
            <div class="packages-display-frame">
                <?php foreach ($display_items[$display_category] as $item): 
                    $package_id = $item['package_id'];
                    $cart_quantity = $_SESSION['cart'][$package_id]??0
                    ?>
                    <div class="package-item-unit-container">
                        <h3><?php echo htmlspecialchars($item['item_name']); ?></h3>
                        <div class="package-description"><?php echo htmlspecialchars($item['description']); ?></div>
                        <div class="package-price">$<?php echo number_format($item['price'], 2); ?> /pax</div>
                        <div class='package-main-name'><b>Main: </b><?php echo htmlspecialchars($item['main_name']); ?></div>
                        <div class='package-side-name'><b>Side: </b><?php echo htmlspecialchars($item['side_name']); ?></div>
                        <div class='package-dessert-name'><b>Dessert: </b><?php echo htmlspecialchars($item['dessert_name']); ?></div>
                        <div class='package-drink-name'><b>Drink: </b><?php echo htmlspecialchars($item['drink_name']); ?></div>
                        <div class="order-quantity-container">
                            <div class="quantity-controls">
                                <button type="button" onclick="adjust_quantity('input_quantity_package_<?=$package_id?>', -1)">-</button>
                                <input name = "<?=$package_id?>" type="number" id="input_quantity_package_<?=$package_id?>" value="<?=$cart_quantity?>" min="0" max="999">
                                <button type="button" onclick="adjust_quantity('input_quantity_package_<?=$package_id?>', 1)">+</button>
                            </div>
                            <div class="popup-buttons">
                                <button class="add-to-cart-btn"
                                    onclick="update_cart()">
                                    Update Cart
                                </button>
                                <button class="cancel-btn"     
                                    onclick="reset_quantity('input_quantity_package_<?=$package_id?>')">
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </form>
        
        <div class="cart-container" onclick="proceed_to_checkout()">
            <div class="cart-img-container">
                <img src="./assets/green_shop-pict-cart.png" alt="checkout-button">
                <span class="cart-text">Checkout</span>
            </div>
        </div>

    </div>

    <script>
        function redirect_back(display_category = <?= $display_category ?>) {
            update_cart(display_category);
        }

        function update_cart(display_category = '<?= $display_category ?>') {
            const cart_form = document.getElementById('cart-form');
            cart_form.action = 'menu_page.php?display_category=' + display_category;
            cart_form.submit();
        }

        function reset_quantity(id_to_reset) {
            const input = document.getElementById(id_to_reset);
            input.value = 0;
            update_cart();
        }

        function adjust_quantity(id_to_change,change) {
            const input = document.getElementById(id_to_change);
            let newValue = parseInt(input.value) + change;
            newValue = Math.max(0, Math.min(999, newValue));
            input.value = newValue;
        }

        function proceed_to_checkout(){
            if(<?=$othercart_is_empty?'true':'false'?> && isFormEmpty()){
                alert('cart is empty');
                return;
            }
            const cart_form = document.getElementById('cart-form');
            cart_form.action = 'menu_page.php?display_category=' + '<?= $display_category ?>'+'&proceed_to_checkout=true';
            cart_form.submit();
        }

        function isFormEmpty() {
            const cart_form = document.getElementById('cart-form');
            array = []
            for (let element of cart_form.elements) {
                // Skips buttons or other non-input elements
                if (element.type !== "submit" && element.type !== "button" && element.type !== "reset") {
                    array += element.value;
                    if(element.value != 0){
                        return false;
                    }
                }
            }
            return true;
        }

    </script>



<?php
    return ob_get_clean(); //Stop the buffer and pass the collected html to page template
}

$username = aquire_username_or_default(DEFAULT_USERNAME);

(new PageTemplate())
    ->set_footer()
    ->set_content(generate_menu_page())
    ->set_header(MENU_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $username)
    ->set_outline(MENU_PAGE_SCRIPTS)
    ->render();
?>