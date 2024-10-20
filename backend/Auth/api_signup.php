<?php

require_once '../core/constants/Errorcodes.php';

// $is_bad_request = !(
//     isset($_POST['email']) && 
//     isset($_POST['phone_number']) && 
//     isset($_POST['password'])
// );

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

try {
    require_once 'signup_execution.php';
    signup();
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        // Invalid HTTP parameters format
        case ERRORCODES::general_error['bad_request']:
            throw $e; // TODO
            break;

        // Email/phone number already exists
        // Error message contains two booleans saying whether email/phone number already exists
        case ERRORCODES::api_signup['email_or_phone_exists']:
            throw $e; // TODO
            break;
        

            
        // Database connection refused/query error
        case ERRORCODES::server_error['database_connection_error']:
            throw $e; // TODO
            break;

        // Database prepare error
        case ERRORCODES::server_error['database_prepare_error']:
            throw $e; // TODO
            break;

        // //undefined error
        // case 69xxx:
        //     //do something
        //     break;

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