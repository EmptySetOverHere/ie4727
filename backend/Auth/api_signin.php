<?php

require_once '../core/constants/Errorcodes.php';
require_once 'signin_execution.php';

// input parameters:
// $_POST['email-phone_number']
// $_POST['password']

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

try {
    signin();
    //defines what happens if execution successful here
    header("Location: ../../frontend/home_page.php"); 
} catch (Exception $e) {
    $header_prefix = "Location: ../../frontend/sign_in_up_page.php?in-or-up=true";
    $error_code = $e->getCode();
    switch($error_code){
        // Invalid HTTP parameters format
        case ERRORCODES::general_error['bad_request']:
            $error_msg = "sign-in-error-msg=" . $e->getMessage();
            $error_code = "sign-in-error-code=" . $error_code;
            $method = "last-sign-in-method=" . $last_sign_in_method;
            header($header_prefix . "&" . $error_msg . "&" . $error_code . "&" . $method);
            break;
        
            // Email/phone number does not exist yet
        case ERRORCODES::api_signin["phone_number_does_not_exist"]:
            $error_msg = "sign-in-error-msg=" . $e->getMessage();
            $error_code = "sign-in-error-code=" . $error_code;
            $method = "last-sign-in-method=" . $last_sign_in_method;
            header($header_prefix . "&" . $error_msg . "&" . $error_code . "&" . $method);
            break;

        case ERRORCODES::api_signin["email_does_not_exist"]:
            $error_msg = "sign-in-error-msg=" . $e->getMessage();
            $error_code = "sign-in-error-code=" . $error_code;
            $method = "last-sign-in-method=" . $last_sign_in_method;
            header($header_prefix . "&" . $error_msg . "&" . $error_code . "&" . $method);
            break;

        case ERRORCODES::api_signin['user_does_not_exist']:
            $error_msg = "sign-in-error-msg=" . $e->getMessage();
            $error_code = "sign-in-error-code=" . $error_code;
            $method = "last-sign-in-method=" . $last_sign_in_method;
            header($header_prefix . "&" . $error_msg . "&" . $error_code . "&" . $method);
            break;

        case ERRORCODES::api_signin["wrong_password"]:
            $error_msg = "sign-in-error-msg=" . $e->getMessage();
            $error_code = "sign-in-error-code=" . $error_code;
            $method = "last-sign-in-method=" . $last_sign_in_method;
            header($header_prefix . "&" . $error_msg . "&" . $error_code . "&" . $method);
            break;

        // Database connection refused/query error
        case ERRORCODES::server_error['database_connection_error']:
            throw $e;
            break;
            
        // Database prepare error
        case ERRORCODES::server_error['database_prepare_error']:
            throw $e;
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

        break;
    }
}

?>