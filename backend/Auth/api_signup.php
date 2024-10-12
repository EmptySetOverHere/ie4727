<?php

try{
    include 'api_signup_execution.php';
    null;//defines what happens if execution successful here 
} catch (Exception $e) {
    switch($e->getCode()){
        //invalid http parameters formatW
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

        //email/phone number already exists
        case 69003:
            throw $e;//TODO
            break;

        // //undefined error
        // case 69xxx:
        //     //do something
        //     break;

        // Catch-all for undefined error codes
        default:
            throw $e;
        break;


    }
}

?>