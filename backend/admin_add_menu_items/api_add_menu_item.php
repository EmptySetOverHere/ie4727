<?php

try {
    require_once 'add_menu_item_execution.php';
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    $error_code = $e->getCode();
    switch($error_code){
        //invalid parameters
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
        
        //user not allowed, insufficient permissions
        case 69004:
            throw $e;//TODO
            break;

        //file wrong format or too large
        case 69005:
            throw $e;//TODO
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