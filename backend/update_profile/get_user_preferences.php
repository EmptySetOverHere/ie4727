<?php

require_once '../core/NyanDB.php';
require_once '../core/constants/Errorcodes.php';

function get_user_preferences(){

    ////check that session user_id
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['user_id'])){
        throw new Exception('user not logged in', ERRORCODES::general_error['bad_request']);
    }



    ////query database
    $sql = "
    SELECT name, 
        address, 
        preferred_payment_method, 
        is_notify_by_sms, 
        is_notify_by_email, 
        is_notify_by_whatsapp, 
        is_notify_by_telegram
    FROM user_preferences
    WHERE user_id = ?;
    ";
    $results = NyanDB::single_query($sql, [$_SESSION['user_id']]);
    if(!empty($result)){
        throw new Exception('user does not exist', ERRORCODES::server_error['user_does_not_exist']);
    }
    $result = $results->fetch_assoc();
    $results->free();



    ////successfully retreived $the user_preference information
    return $result;
}


?>
