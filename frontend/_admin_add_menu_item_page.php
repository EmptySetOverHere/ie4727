<?php

require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

aquire_username_or_default(DEFAULT_USERNAME);   

ob_start(); // Start buffer to collect generated HTML lines
echo PageTemplate::getJavascriptAlertMessage();
?>
<div class='centered-items main-content-container'>
    <div class="grouped-input-container">
        <div class="section-header">Add Menu Item</div>
        <div class="section-divider"></div>
        <form id="add-menu-item-form" method="post" action="../backend/admin_add_menu_items/api_add_menu_item.php" enctype="multipart/form-data">
            <div class="text-input-container">
                <label for="item_name">Item Name</label>
                <input type="text" name="item_name" id="item_name">
            </div>
            <div class="text-input-container" >
                <label for="description">Description</label>
                <textarea type="description" name="description" id="description"></textarea>
            </div>
            <div class="text-input-container">
                <label for="price">Price</label>
                <input type="text" name="price" id="price">
            </div>
            <div class="text-input-container">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    <option value="main">Main</option>
                    <option value="side">Side</option>
                    <option value="dessert">Dessert</option>
                    <option value="drink">Drink</option>
                </select>
            </div>
            <div class="sub-header">Modifiers</div>
            <div class="checkbox-input-container">
                <table>
                    <tr>
                        <td><label for="is_in_stock">In Stock</label></td>
                        <td><input type="checkbox" name="is_in_stock" id="is_in_stock" checked></td>
                    </tr>
                    <tr>
                        <td><label for="is_vegetarian">Is Vegetarian</label></td>
                        <td><input type="checkbox" name="is_vegetarian" id="is_vegetarian"></td>
                    </tr>
                    <tr>
                        <td><label for="is_halal">Is Halal</label></td>
                        <td><input type="checkbox" name="is_halal" id="is_halal"></td>
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
        </form>
        <div class="submit-button-container">
            <button type="submit" onclick="submit('add-menu-item-form')">Insert</button>
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