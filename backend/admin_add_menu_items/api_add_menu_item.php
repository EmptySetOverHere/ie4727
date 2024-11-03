<?php

require_once '../core/constants/Errorcodes.php';

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

//expected inputs:
// $item_name     = $_POST['item_name'];
// $description   = $_POST['description'];
// $price         = (string)$_POST['price'];
// $category      = $_POST['category'];
// $is_in_stock   = isset($_POST['is_in_stock']);
// $is_vegetarian = isset($_POST['is_vegetarian']);
// $is_halal      = isset($_POST['is_halal']);
// if (!isset($_FILES['image'])){
//     return true;
// }

try {
    require_once 'add_menu_item_execution.php';
    add_menu_item();
    header('Location: ../../frontend/_admin_add_menu_item_page.php');
    exit();
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        // Invalid HTTP parameters format
        case ERRORCODES::general_error['bad_request']:
            $params = http_build_query(['alert_msg'=>'bad request']);
            header('Location: ../../frontend/_admin_add_menu_item_page.php?' . $params);
            break;
        
        //user not allowed, insufficient permissions
        case ERRORCODES::general_error['invalid_credentials']:
            $params = http_build_query(['alert_msg'=>'invalid credentials']);
            header('Location: ../../frontend/home_page.php?' . $params);
            break;

        //file wrong format or too large
        case ERRORCODES::api_add_menu_item['invalid_file_format']:
            $params = http_build_query(['alert_msg'=>'invalid file format']);
            header('Location: ../../frontend/_admin_add_menu_item_page.php?' . $params);
            break;

        // Database connection refused/query error
        case ERRORCODES::server_error['database_connection_error']:
        // Database prepare error
        case ERRORCODES::server_error['database_prepare_error']:
            $params = http_build_query(['alert_msg'=>'server error']);
            header('Location: ../../frontend/home_page.php?' . $params);
            break;

        // Catch-all for undefined error codes
        default:
            // if($error_code>= 69000 && $error_code<=69999){
            //     throw new Exception($e->getMessage(),0);
            // }
            $params = http_build_query(['alert_msg'=>'uncaught error in api add menu items: '. $e->getMessage()]);
            header('Location: ../../frontend/home_page.php?' . $params);
            break;
        break;
    }
}

?>