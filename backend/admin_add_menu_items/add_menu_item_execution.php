<?php

function add_menu_item(){
    require_once '../core/NyanDB.php';
    require_once '../core/Image.php';
    require_once '../core/Errorcodes.php'; 

    ////check that session user_id
    if (session_status() === PHP_SESSION_NONE){
        throw new Exception('unregistered session', ERRORCODES::general_error['bad_request']);
    }
    if(!isset($_SESSION['user_id'])){
        throw new Exception('user not logged in', ERRORCODES::general_error['bad_request']);
    }
    if($_SESSION['user_id'] != 0){
        throw new Exception('insufficient access rights', ERRORCODES::general_error['invalid_credentials']);
    }



    ////assign HTTP request values
    $is_bad_request = !(
        isset($_POST['item_name']) && 
        isset($_POST['description']) && 
        isset($_POST['price']) && 
        isset($_POST['category'])
    );
    $is_bad_request ? throw new Exception('bad request', ERRORCODES::general_error['bad_request']) : null;

    $item_name     = $_POST['item_name'];
    $description   = $_POST['description'];
    $price         = (string)$_POST['price'];
    $category      = $_POST['category'];
    $is_in_stock   = isset($_POST['is_in_stock']);
    $is_vegetarian = isset($_POST['is_vegetarian']);
    $is_halal      = isset($_POST['is_halal']);
    //$_POST['is_in_stock']=='on' returns true if checkbox selected
    //this file also receives a $_FILES['image] value



    ////verify the values
    $is_item_name_valid = preg_match('/^[\w\',()&_" -]+$/', $item_name);
    $is_price_valid     = preg_match('/^\d+(\.\d{1,2})?$/', $price);
    $is_category_valid  = in_array($category, ['main', 'side', 'dessert', 'drink']);
    if(!($is_item_name_valid && $is_price_valid && $is_category_valid)){
        throw new Exception('wrong format', ERRORCODES::general_error['bad_request']);
    }
    if (isset($_FILES['image'])) {
        $file_mime = mime_content_type($_FILES['image']['tmp_name']);
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file_mime, $allowed_mimes)) {
            throw new Exception('file type invalid', ERRORCODES::api_add_menu_item['invalid_file_format']);
        }
        $max_size = 10 * 1024 * 1024; // 10MB in bytes
        if ($_FILES['image']['size'] > $max_size) {
            throw new Exception('file too large', ERRORCODES::api_add_menu_item['invalid_file_format']);
        }
    }



    ////query the table
    $sql = "
    INSERT INTO menu_items (
        item_name, 
        description, 
        price, 
        category, 
        is_in_stock, 
        is_vegetarian, 
        is_halal
    ) 
    VALUES (
        ?, ?, ?, ?, ?, ?, ?
    );
    ";
    $params = [
        $item_name, 
        $description, 
        $price, 
        $category, 
        $is_in_stock, 
        $is_vegetarian, 
        $is_halal
    ];
    $menu_item_id = NyanDB::single_query($sql, $params);

    ////if no image submitted, function finished execution
    if (!isset($_FILES['image'])){
        return true;
    }



    ////add image to database
    $image = $_FILES['image'];
    $sql = "
    INSERT INTO menu_item_images (menu_item_id, image_name, image_data, image_type)
    VALUES (?, ?, ?, ?);
    ";
    NyanDB::single_query($sql, array_merge([$menu_item_id], Image::explode($image)));



    ////done execution

}

?>