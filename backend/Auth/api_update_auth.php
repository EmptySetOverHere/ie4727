<?php

try {
    require_once 'update_auth_execution.php';
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        //invalid http parameters format
        case 69000:
            throw $e;//TODO
            break;

        //database connection refused/query error
        case 69001:
            throw $e;//TODO
            break;

        //database prepare error
        case 69002:
            throw $e;//TODO
            break;

        //session does not have a associated userID, because user not logged in
        case 69003:
            throw $e;//TODO
            break;
        
        //user does not exist in database
        case 69004:
            throw $e;//TODO
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