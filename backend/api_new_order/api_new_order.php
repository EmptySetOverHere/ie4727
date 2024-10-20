<?php

require_once '../core/constants/Errorcodes.php';

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

////required inputs:
//$_SESSION['cart'],$_SESSION['timezone'],$_SESSION['user_id']
//$_POST['start_date'], $_POST['end_date'], $_POST['start_time'], $_POST['end_time']
//$_POST['delivery_address']

//testcase before frontend finishes. delete this TODO
// echo ($_SESSION['user_id']);
// $_SESSION['cart'] = [['package_id'=>1 , 'quantity' => 4] ,['package_id'=>2, 'quantity' => 7]];
// $_SESSION['timezone'] = 'Asia/Singapore';
// print_r($_SESSION['cart']);

try {
    require_once 'new_order_execution.php';
    new_order();
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        // Invalid HTTP parameters format
        case ERRORCODES::general_error['bad_request']:
            throw $e;//TODO
            break;
        
        //user not allowed, insufficient permissions
        case ERRORCODES::general_error['invalid_credentials']:
            throw $e;//TODO
            break;

        // Database connection refused/query error
        case ERRORCODES::server_error['database_connection_error']:
            throw $e; // TODO
            break;

        // Database prepare error
        case ERRORCODES::server_error['database_prepare_error']:
            throw $e; // TODO
            break;
        
        // package no longer available
        case ERRORCODES::api_new_order['package_not_available']:
            throw $e; // TODO
            break;

        // order size too large
        case ERRORCODES::api_new_order['order_quantity_too_large']:
            throw $e; // TODO
            break;

        // Catch-all for undefined error codes
        default:
            if($error_code>= 69000 && $error_code<=69999){
                throw new Exception($e->getMessage(),0);
            }
            else throw $e;
        break;
    }
}

?>