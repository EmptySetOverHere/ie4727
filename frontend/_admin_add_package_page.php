<?php

require_once "../backend/core/menu_items.php";
require_once "../backend/core/Image.php";
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

aquire_username_or_default(DEFAULT_USERNAME);   

const MENU_ITEMS_PER_PAGE = 3;
$menu_item_name_filter    = $_GET['menu_item_name_filter']??null;
$offset                   = (int)$_GET['offset']??0;
$offset                   = ($offset<0) ?  (string)0 : (string)$offset;
$total_relevant_entries   = MenuItems::get_latest_valid_menu_items_count($menu_item_name_filter);
$displayitems             = MenuItems::get_latest_valid_menu_items($offset, MENU_ITEMS_PER_PAGE, $menu_item_name_filter);

$imageSources = [];
foreach ($displayitems as $menuItem) {
    $menu_item_id = $menuItem['menu_item_id'];
    $imageSource = MenuItems::get_associated_image_src($menu_item_id);
    $imageSources[$menu_item_id] = $imageSource; // Store the result indexed by menu_item_id
}

ob_start(); // Start buffer to collect generated HTML lines

echo PageTemplate::getJavascriptAlertMessage();
?>
<div class='centered-items main-content-container'>
    <div class="grouped-input-container">
        <div class="section-header">Add Package</div>
        <div class="section-divider"></div>
        <form id="add-menu-item-form" method="post" action="../backend/admin_add_package/api_add_package.php" enctype="multipart/form-data">
            <div class="text-input-container">
                <label for="item_name">Package Name</label>
                <input type="text" name="item_name" id="item_name">
            </div>
            <div class="text-input-container" >
                <label for="description">Description</label>
                <textarea type="description" name="description" id="description"></textarea>
            </div>
            <div class="text-input-container">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="buffet">Buffet</option>
                    <option value="bento">Bento</option>
                </select>
            </div>
            <div class="sub-header">Modifiers</div>
            <div class="checkbox-input-container">
                <table>
                    <tr>
                        <td><label for="is_available">Is Available</label></td>
                        <td><input type="checkbox" name="is_in_stock" id="is_in_stock" checked></td>
                    </tr>
                </table>
            </div>
            <div class="text-input-container">
                <label for="image">Image</label>
                <input type="file" name="image" id="image">
                <div class="image-container">
                    <img id="preview" src="./assets/image-placeholder.svg" alt="Image Preview">
                </div>
            </div>
            <br>
            <div class="sub-header">Select Components Below</div>
            <div class="text-input-container">
                <label for="main">Main (readonly)</label>
                <input type="text" name="main" id="main" readonly>
            </div>
            <div class="text-input-container">
                <label for="side">Side (readonly)</label>
                <input type="text" name="side" id="side" readonly>
            </div>
            <div class="text-input-container">
                <label for="dessert">Dessert (readonly)</label>
                <input type="text" name="dessert" id="dessert" readonly>
            </div>
            <div class="text-input-container">
                <label for="drink">Drink (readonly)</label>
                <input type="text" name="drink" id="drink" readonly>
            </div>
        </form>
        <div class="submit-button-container">
            <button type="submit" onclick="submit('add-menu-item-form')">Insert</button>
        </div>
    </div>
    <div class="grouped-input-container">
        <div class="section-header">Select Menu Items</div>
        <div class="sub-header" style= 'display: inline; margin-right: 5px'>filtering for:</div>
        <input type="text" name="name_filter_input_field" id="name_filter_input_field">
        <div class="submit-button-container">
            <button onclick="redirect_back()">filter</button>
        </div>

        <div class="menu-item-container">
            <div class="info-row">
                <?php foreach ($displayitems as $menuItem){
                    $image_data   = MenuItems::get_associated_image_src($menuItem['menu_item_id']);
                    $img_src      = $image_data??'./assets/image-placeholder.svg';
                    $description  = $menuItem['description'];

                    ?>
                <div class="info-item">
                    <img src=<?=$img_src?> alt="Menu Item" class="menu-item-image">
                    <div style="text-align: start;">
                        <p><strong>Menu Item ID:  <?=$menuItem['menu_item_id']?></strong>               <span id="menu_item_id"></span></p>
                        <p><strong>Description:   <?=$menuItem['description']?></strong>                 <span id="description"></span></p>
                        <p><strong>Price:         <?=$menuItem['price']?></strong>                             <span id="price"></span></p>
                        <p><strong>Category:      <?=$menuItem['category']?></strong>                       <span id="description"></span></p>
                        <p><strong>is vegetarian: <?=$menuItem['is_vegetarian']?'yes':'no';?></strong> <span id="description"></span></p>
                        <p><strong>is_halal:      <?=$menuItem['is_halal']?'yes':'no'?></strong>            <span id="description"></span></p>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>

        <div class="sub-header">Page <?=intdiv($offset, MENU_ITEMS_PER_PAGE) + 1?> of <?=intdiv($total_relevant_entries, MENU_ITEMS_PER_PAGE) + 1?></div>
        <div class="pagination-buttons">
            <button onclick="change_page(-1)">Previous</button>
            <button onclick="change_page(1)">Next</button>
        </div>
    </div>
</div>

<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById('preview');
                img.src = e.target.result;
                img.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    function submit(form_id, verification_function = null){
        if (verification_function === null || verification_function()) {
            document.getElementById(form_id).submit();
        }
    }
    function redirect_back(range_start = <?= $offset ?>){
        const offset = String(range_start);
        const name_filter = String(document.getElementById('name_filter_input_field').value)??null;
        params = "?offset=" + offset;
        if(name_filter){params += ("&menu_item_name_filter=" + name_filter);}
        window.location.href = "_admin_add_package_page.php" + params;
    }
    function change_page(change_amount){
        offset = <?= $offset ?> + change_amount * <?=MENU_ITEMS_PER_PAGE?>;
        redirect_back(offset);
    }
</script>

<?php
$content = ob_get_clean(); //Stop the buffer and pass the collected html to page template

(new PageTemplate())
    ->set_footer()
    ->set_content($content)
    ->set_header(_ADMIN_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $username="admin")
    ->set_outline(HOME_PAGE_SCRIPTS)
    ->render();
?>