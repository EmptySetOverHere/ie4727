<?php

require_once '../core/constants/Errorcodes.php';

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

try {
    require_once 'admin_get_menu_items_execution.php';
    admin_get_menu_items(); //TODO
    $result = null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        // Invalid HTTP parameters format
        case ERRORCODES::general_error['bad_request']:
            throw $e;//TODO
            break;

        //file wrong format or too large
        case ERRORCODES::api_add_menu_item['invalid_file_format']:
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