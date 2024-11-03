<?php 
require_once "./components/templates.php";
require_once "./utilities/config.php";
require_once "./utilities/resource_aquisition.php";

session_status() === PHP_SESSION_NONE ? session_start(): null;

aquire_username_or_default(DEFAULT_USERNAME);


function generate_menu_page():string{
    $display_category=$_GET['display_category']??'buffet';
    ob_start(); //start buffer to collect generated html lines
    ?>
    
    <div class="main-content-container">
        <!-- for buffet -->
        <button class='display-category-toggle-container' <?=$display_category=='buffet'?'disabled':'';?>></button>

    
        <!-- for bento -->
        <button class='display-category-toggle-container' <?=$display_category=='bento'?'disabled':'';?>></button>

    </div>
    
    <?php
    return ob_get_clean(); //Stop the buffer and pass the collected html to page template
}


(new PageTemplate())
    ->set_footer()
    ->set_content(generate_menu_page())
    ->set_header(MENU_PAGE_STYLES)
    ->set_navibar(NAV_LINKS, $username="guest")
    ->set_outline(MENU_PAGE_SCRIPTS)
    ->render();
?>