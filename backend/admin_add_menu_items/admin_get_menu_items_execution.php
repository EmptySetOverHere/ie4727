<?php

function admin_get_menu_items(){
    require_once '../core/get_menu_items.php';
    // require_once '../core/NyanDB.php';
    // require_once '../core/Image.php';
    // require_once '../core/constants/Errorcodes.php';

    //TODO dont know what this page needs
    $result = MenuItems::get_latest_menu_items();
}


?>