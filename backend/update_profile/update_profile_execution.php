<?php

function update_profile(){
    require_once '../core/NyanDB.php';
    require_once '../core/Image.php';
    require_once '../core/constants/Errorcodes.php';

    ////check that session user_id
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }
    if(!isset($_SESSION['user_id'])){
        throw new Exception('user not logged in', ERRORCODES::general_error['bad_request']);
    }



    ////assign HTTP request values

    $name                     = $_POST['name'] ?? null;
    $address                  = $_POST['address'] ?? null;
    $preferred_payment_method = $_POST['preferred_payment_method'] ?? null;
    $is_notify_by_sms         = isset($_POST['is_notify_by_sms']);
    $is_notify_by_email       = isset($_POST['is_notify_by_email']);
    $is_notify_by_whatsapp    = isset($_POST['is_notify_by_whatsapp']);
    $is_notify_by_telegram    = isset($_POST['is_notify_by_telegram']);



    ////verify values entered
    //verify address and payment method TODO




    ////update preferences
    $params = [$name, $address, $preferred_payment_method, ...$boolean_array];
    $sql = "
    UPDATE user_preferences
    SET name = ?, 
        address = ?, 
        preferred_payment_method = ?, 
        is_notify_by_sms = ?, 
        is_notify_by_email = ?, 
        is_notify_by_whatsapp = ?, 
        is_notify_by_telegram = ?
    WHERE user_id = ?;
    ";
    NyanDB::single_query($sql, [...$params,$_SESSION['user_id']]);



    ////update preferences successful, redirecting to relevant screen should be done outside this file
}
?>