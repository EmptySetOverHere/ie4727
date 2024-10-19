<?php

require_once '../core/NyanDB.php';
require_once '../core/Image.php';
require_once '../core/menu_items.php';
require_once '../core/constants/Errorcodes.php';

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
$result = MenuItems::get_menu_item_by_id($main);
if ($result == null){
    if ($result['category'] != 'main'){
        throw new Exception('invalid main', ERRORCODES::general_error['bad_request']);
    }
    $price += $result['price'];
}
$result = MenuItems::get_menu_item_by_id($side);
if ($result == null){
    if ($result['category'] != 'side'){
        throw new Exception('invalid side', ERRORCODES::general_error['bad_request']);
    }
    $price += $result['price'];
}
$result = MenuItems::get_menu_item_by_id($dessert);
if ($result == null){
    if ($result['category'] != 'dessert'){
        throw new Exception('invalid dessert', ERRORCODES::general_error['bad_request']);
    }
    $price += $result['price'];
}
$result = MenuItems::get_menu_item_by_id($drink);
if ($result == null){
    if ($result['category'] != 'drink'){
        throw new Exception('invalid drink', ERRORCODES::general_error['bad_request']);
    }
    $price += $result['price'];
}



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
    ?, ?, ?, ?, ?, ?, ?, ?
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


?>
