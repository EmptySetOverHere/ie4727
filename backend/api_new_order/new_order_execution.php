<?php

function new_order(){
    require_once '../core/constants/Errorcodes.php';
    require_once '../core/NyanDateTime.php';
    require_once '../core/menu_packages.php';
    require_once '../core/NyanDB.php';

    ////check that session has a user_id and assign it
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    } else {
        throw new Exception('user not logged in', ERRORCODES::general_error['invalid_credentials']);
    }



    ////check for user timezone is valid and exists
    if(!isset($_SESSION['timezone'])){
        throw new Exception('missing timezone', ERRORCODES::general_error['missing_timezone']);
    }
    try{
        new DateTimeZone($_SESSION['timezone']);
        $timezone = $_SESSION['timezone'];
    } catch (Exception $e) {
        throw new Exception('invalid timezone', ERRORCODES::general_error['missing_timezone']);
    }



    ////verify and assign HTTP request values
    //check post request values
    $is_bad_request = !(
        isset($_POST['start_date']) &&
        isset($_POST['end_date']) &&
        isset($_POST['start_time']) &&
        isset($_POST['end_time'])
    );
    if ($is_bad_request) {
        throw new Exception('order must include start and end timings', ERRORCODES::general_error['bad_request']);
    }
    $start_datetime_str = (string)($_POST['start_date'] . ' ' . $_POST['start_time']);
    $end_datetime_str = (string)($_POST['end_date'] . ' ' . $_POST['end_time']);
    $datetimereg = '/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?$/';
    if (!preg_match($datetimereg, $start_datetime_str) || !preg_match($datetimereg, $end_datetime_str)) {
        throw new Exception('Invalid start and end timings', ERRORCODES::general_error['bad_request']);
        if((new DateTime($start_datetime_str) === false) or (new DateTime($end_datetime_str) === false)){
            echo 'you sneky bastart';
            return;
        }
    }



    ////assigning value for datetimes
    //helper function
    $start_datetime = (new NyanDateTime($start_datetime_str, $timezone))->value();
    $end_datetime   = (new NyanDateTime($end_datetime_str, $timezone))->value();
    $order_datetime = (new NyanDateTime())->value();
    if(new DateTime($start_datetime) > new DateTime($end_datetime)){
        throw new Exception('end time cannot be earlier than start time', ERRORCODES::general_error['bad_request']);
    }
    if(new DateTime($order_datetime) > new DateTime($start_datetime)){
        throw new Exception('start time cannot be earlier than order time', ERRORCODES::general_error['bad_request']);
    }



    //check cart validity
    $is_bad_request = (!isset($_SESSION['cart']) || empty($_SESSION['cart']));
    if ($is_bad_request) {
        throw new Exception('cart cannot be empty', ERRORCODES::general_error['bad_request']);
    }

    $is_bad_request = !isset($_POST['delivery_address']);
    if ($is_bad_request) {
        throw new Exception('order must include prefered address and time', ERRORCODES::general_error['bad_request']);
    }

    //assign cart if valid
    $order = [];
    $total_packages_count = 0;
    foreach ($_SESSION['cart'] as $item) {
        if (!isset($item['package_id']) || !isset($item['quantity'])) {
            throw new Exception('every cart item must have a package_id and quantity', ERRORCODES::general_error['bad_request']);
        }
        if (!filter_var($item['package_id'], FILTER_VALIDATE_INT) || !filter_var($item['quantity'], FILTER_VALIDATE_INT)) {
            throw new Exception('package_id and quantity must be numeric', ERRORCODES::general_error['bad_request']);
        }
        $order[$item['package_id']] = $item['quantity'];
        $total_packages_count += $item['quantity'];
        if ($total_packages_count > 1000) {
            throw new Exception('order quantity too large, please contact administrator', ERRORCODES::api_new_order['order_quantity_too_large']);
        }
    }



    ////check that there is a stated delivery address
    if(isset($_POST['delivery_address'])){
        $delivery_address  = $_POST['delivery_address'];
    } else {
        throw new Exception('missing delivery_address', ERRORCODES::general_error['bad_request']);
    }



    ////check that the cart packages are in stock by querying the database
    $result = MenuPackages::get_latest_instock_package_ids();
    $valid_package_ids = array_column($result, 'package_id');
    echo '<br>the existing packages: ';
    var_dump($valid_package_ids); 
    foreach ($order as $package_id => $quantity) {
        if (!in_array($package_id, $valid_package_ids)) {
            throw new Exception('some packages are no longer available', ERRORCODES::api_new_order['package_not_available']);
        }
    }



    ////query the database, insert new order
    //start a new order, obtain the assigned $order_id from database
    $sql = "
    INSERT INTO orders (
        user_id, order_datetime, start_datetime, end_datetime, delivery_address
    ) 
    VALUES (?, ?, ?, ?, ?);
    ";
    $params = [$user_id, $order_datetime, $start_datetime, $end_datetime, $delivery_address];
    $order_id = NyanDB::single_query($sql, $params);
    //insert order quantities into the $order_items table
    $sql = '
        INSERT INTO order_items (order_item_id, package_id, packages_number) 
        VALUES 
        ';
    $params = [];
    foreach ($order as $package_id => $quantity) {
        $sql .= '(?, ?, ?), ';
        $params = array_merge($params, [$order_id, (string)$package_id, (string)$quantity]);
    }
    $sql = rtrim($sql,", ") . ";";
    NyanDB::single_query($sql, $params);

//done
}

?>