<?php

function update_auth(){
    if (session_status() === PHP_SESSION_NONE){
        throw new Exception('unregistered session', ERRORCODES::general_error['bad_request']);
    }
    require_once '../core/NyanDB.php'; //import class definition

    ////check that session has a user_id
    isset($_SESSION['user_id'])? null : throw new Exception('user not logged in', ERRORCODES::general_error['invalid_credentials']);



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



    ////verify legitimate phone number/email by sending sms or email
    //TODO



    ////query database
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
    $sql = "
    UPDATE user_auths
    SET email = ?, phone_number, = ? hashed_password = ?
    WHERE user_id = ?;
    ";
    NyanDB::single_query($sql, [$email, $phone_number, $hashed_password, $_SESSION['user_id']]);
    //database errors will be raised by NyanDB



    ////finished execution
}
?>