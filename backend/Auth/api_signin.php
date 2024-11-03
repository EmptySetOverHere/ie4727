<?php

require_once '../core/constants/Errorcodes.php';

// input parameters:
// $_POST['email-phone_number']
// $_POST['password']

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

try {
    require_once 'signin_execution.php';
    signin();
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        // Invalid HTTP parameters format
        case ERRORCODES::general_error['bad_request']:
            $_SESSION["sign-in-error-msg"] = "Bad Request"; 
            break;
            // Email/phone number does not exist yet
        case ERRORCODES::api_signin['user_does_not_exist']:
            $_SESSION["sign-in-error-msg"] = "User does not exist"; 
            break;

        // Wrong password
        case ERRORCODES::api_signin['wrong_password']:
            $_SESSION["sign-in-error-msg"] = "Wrong password"; 
            break;
        // Database connection refused/query error
        case ERRORCODES::server_error['database_connection_error']:
            $_SESSION["sign-in-error-msg"] = "Database connection error"; 
            break;
            
        // Database prepare error
        case ERRORCODES::server_error['database_prepare_error']:
            $_SESSION["sign-in-error-msg"] = "Database prepare error"; 
            break;
        // //undefined error
        // case 69xxx:
        //     throw $e;//TODO
        //     break;

        // Catch-all for undefined error codes
        default:
            if($error_code>= 69000 && $error_code<=69999){
                throw new Exception($e->getMessage(),0);
            }
            else throw $e;
        break;
    }

    header("Location: ../../frontend/home_page.php");
}

?>