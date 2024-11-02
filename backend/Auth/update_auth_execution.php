<?php

function update_auth(){
    require_once '../core/constants/Errorcodes.php';
    
    if (session_status() === PHP_SESSION_NONE){
        session_start();
    }
    require_once '../core/NyanDB.php'; //import class definition

    ////check that session has a user_id and set it
    $user_id = isset($_SESSION['user_id'])?  $_SESSION['user_id'] : throw new Exception('user not logged in', ERRORCODES::general_error['invalid_credentials']);



    ////assign HTTP request values
    $is_bad_request = !(
        isset($_POST['email']) && 
        isset($_POST['phone_number']) && 
        isset($_POST['password'])
    );
    $is_bad_request ? throw new Exception('bad request', ERRORCODES::general_error['bad_request']) : null;
    $email        = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $password     = $_POST['password'];


    ////check phone number is not taken by another user
    $sql = "
    SELECT user_id
    FROM user_auths
    WHERE user_id <> ? AND phone_number = ?;
    ";
    $params = [$user_id, $phone_number];
    $results = NyanDB::single_query($sql, $params);
    $result = $results->fetch_assoc();
    $results->free();
    if(!empty($result)){
        throw new Exception($error_message,ERRORCODES::api_update_auth['phone_number_already_exists']);
        exit(); //exit just in case lol 
    }

    ////check email is not taken by another user
    $sql = "
    SELECT user_id
    FROM user_auths
    WHERE user_id <> ? AND email = ?;
    ";
    $params = [$user_id, $email];
    $results = NyanDB::single_query($sql, $params);
    $result = $results->fetch_assoc();
    $results->free();
    if(!empty($result)){
        throw new Exception($error_message,ERRORCODES::api_update_auth['email_already_exists']);
        exit(); //exit just in case lol 
    }


    ////verify legitimate phone number/email by sending sms or email
    //TODO



    ////query database
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
    $sql = "
    UPDATE user_auths
    SET email = ?, phone_number, = ? hashed_password = ?
    WHERE user_id = ?;
    ";
    $params = [$email, $phone_number, $hashed_password, $user_id];
    NyanDB::single_query($sql, $params);
    //database errors will be raised by NyanDB



    ////finished execution
}
?>