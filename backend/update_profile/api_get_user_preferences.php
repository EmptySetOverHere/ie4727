<?php

require_once '../core/constants/Errorcodes.php';

if (session_status() === PHP_SESSION_NONE){
    session_start();
}

//required inputs:
// $_SESSION['user_id'] thats all i should make this a getter file
//this file is probably useless TODO

try {
    require_once 'get_user_preferences_execution.php';
    $result = get_user_preferences(); // $result is an assoc array containing the relevant information
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        //session does not have a associated userID, because user not logged in
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