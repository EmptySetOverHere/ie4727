<?php

require_once '../core/constants/Errorcodes.php';

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

//required inputs:
// $_SESSION['user_id']
// 
// $name                     = $_POST['name'] ?? null;
// $address                  = $_POST['address'] ?? null;
// $preferred_payment_method = $_POST['preferred_payment_method'] ?? null;
// $is_notify_by_sms         = isset($_POST['is_notify_by_sms']);
// $is_notify_by_email       = isset($_POST['is_notify_by_email']);
// $is_notify_by_whatsapp    = isset($_POST['is_notify_by_whatsapp']);
// $is_notify_by_telegram    = isset($_POST['is_notify_by_telegram']);

try {
    require_once 'update_profile_execution.php';
    update_profile();
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        //invalid http parameters format
        case ERRORCODES::general_error['bad_request']:
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

        //user does not exist in database
        case ERRORCODES::server_error['user_does_not_exist']:
            throw $e;//TODO
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