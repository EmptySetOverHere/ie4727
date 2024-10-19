<?php

require_once '../core/constants/Errorcodes.php';

try {
    require_once 'signin_execution.php';
    signin();
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        // Invalid HTTP parameters format
        case ERRORCODES::general_error['bad_request']:
            throw $e; // TODO
            break;

        // Email/phone number does not exist yet
        case ERRORCODES::api_signin['user_does_not_exist']:
            throw $e; // TODO
            break;

        // Wrong password
        case ERRORCODES::api_signin['wrong_password']:
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
}

?>