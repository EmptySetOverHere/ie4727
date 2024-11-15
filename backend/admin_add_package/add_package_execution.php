<?php

function add_package(){
    require_once '../core/NyanDB.php';
    require_once '../core/Image.php';
    require_once '../core/menu_items.php';
    require_once '../core/constants/Errorcodes.php';

    ////check that session user_id
    if (session_status() === PHP_SESSION_NONE){
        session_start();
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
        isset($_POST['category']) &&
        isset($_POST['main']) &&
        isset($_POST['side']) &&
        isset($_POST['dessert']) &&
        isset($_POST['drink'])
    );
    $is_bad_request ? throw new Exception('bad request', ERRORCODES::general_error['bad_request']) : null;

    $item_name     = $_POST['item_name'];
    $description   = $_POST['description'];
    $price         = 0.0;
    //$price will be calculated serverside.
    $category      = $_POST['category'];
    $is_available  = isset($_POST['is_available']);
    $main    = $_POST['main']; //this is the menu_item_id of the item
    $side    = $_POST['side'];
    $dessert = $_POST['dessert'];
    $drink   = $_POST['drink'];
    //$_POST['is_in_stock']=='on' returns true if checkbox selected
    //this file also receives a $_FILES['image] value
    // $result = MenuItems::get_menu_item_by_id($main);
    // if ($result == null){
    //     if ($result['category'] != 'main'){
    //         throw new Exception('invalid main', ERRORCODES::general_error['bad_request']);
    //     }
    //     $price += $result['price'];
    // }
    // $result = MenuItems::get_menu_item_by_id($side);
    // if ($result == null){
    //     if ($result['category'] != 'side'){
    //         throw new Exception('invalid side', ERRORCODES::general_error['bad_request']);
    //     }
    //     $price += $result['price'];
    // }
    // $result = MenuItems::get_menu_item_by_id($dessert);
    // if ($result == null){
    //     if ($result['category'] != 'dessert'){
    //         throw new Exception('invalid dessert', ERRORCODES::general_error['bad_request']);
    //     }
    //     $price += $result['price'];
    // }
    // $result = MenuItems::get_menu_item_by_id($drink);
    // if ($result == null){
    //     if ($result['category'] != 'drink'){
    //         throw new Exception('invalid drink', ERRORCODES::general_error['bad_request']);
    //     }
    //     $price += $result['price'];
    // }



    ////verify the values
    $is_item_name_valid = preg_match('/^[\w\',()&_" -]+$/', $item_name);
    if(!$is_item_name_valid){
        throw new Exception('wrong format', ERRORCODES::general_error['bad_request']);
    }
    if(strlen($item_name) > 240){
        throw new Exception('name too long', ERRORCODES::general_error['bad_request']);
    }
    if(strlen($description) > 480){
        throw new Exception('description too long', ERRORCODES::general_error['bad_request']);
    }
    if(strlen($category) > 30){
        throw new Exception('category name too long', ERRORCODES::general_error['bad_request']);
    }

    ////verify the image(if applicable)
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
    INSERT INTO packages (			
        item_name, 
        description, 
        price, 
        category, 
        is_available, 
        main, 
        side,
        dessert,
        drink
    ) 
    VALUES (
        ?, ?, ?, ?, ?, ?, ?, ?, ?
    );
    ";
    $params = [
        $item_name, 
        $description, 
        $price, 
        $category, 
        $is_available, 
        $main, 
        $side,
        $dessert,
        $drink,
    ];
    $menu_item_id = NyanDB::single_query($sql, $params);

    ////if no image submitted, function finished execution
    if (!isset($_FILES['image'])){
        return true;
    }

    ////add image to database
    $image = $_FILES['image'];
    $sql = "
    INSERT INTO package_images (package_id, image_name, image_data, image_type)
    VALUES (?, ?, ?, ?);
    ";
    NyanDB::single_query($sql, array_merge([$menu_item_id], Image::explode($image)));
}

?>
