<?php

require_once '../core/constants/Errorcodes.php';

//expected inputs:
// $is_bad_request = !(
//     isset($_POST['item_name']) && 
//     isset($_POST['description']) && 
//     isset($_POST['category']) &&
//     isset($_POST['main']) &&
//     isset($_POST['side']) &&
//     isset($_POST['dessert']) &&
//     isset($_POST['drink'])
// );
// $is_available  = isset($_POST['is_available']);

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

try {
    require_once 'add_package_execution.php';
    add_package();
    $params = http_build_query(['alert_msg'=>'insert successful']);
    header('Location: ../../frontend/_admin_add_package_page.php?' . $params);
    exit();
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        // Invalid HTTP parameters format
        case ERRORCODES::general_error['bad_request']:
            $params = http_build_query(['alert_msg'=>'bad request']);
            header('Location: ../../frontend/_admin_add_package_page.php?' . $params);
            break;
        
        //user not allowed, insufficient permissions
        case ERRORCODES::general_error['invalid_credentials']:
            $params = http_build_query(['alert_msg'=>'invalid_credentials']);
            header('Location: ../../frontend/_admin_add_package_page.php?' . $params);
            break;

        //file wrong format or too large
        case ERRORCODES::api_add_package['invalid_file_format']:
            $params = http_build_query(['alert_msg'=>'invalid_file_format']);
            header('Location: ../../frontend/_admin_add_package_page.php?' . $params);
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
            if($error_code>= 69000 && $error_code<=69999){
                throw new Exception($e->getMessage(),0);
            }
            else throw $e;
        break;
    }
}

?>