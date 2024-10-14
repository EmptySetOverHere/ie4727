<?php

require_once '../core/Errorcodes.php';

try {
    require_once 'update_auth_execution.php';
    update_auth();
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        //invalid http parameters format
        case ERRORCODES::general_error['bad_request']:
            throw $e; // TODO
            break;

        //session does not have a associated userID, because user not logged in
        case ERRORCODES::general_error['invalid_credentials']:
            throw $e;//TODO
            break;

        //user does not exist in database
        case ERRORCODES::server_error['user_does_not_exist']:
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