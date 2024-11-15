<?php

function signup(){
    require_once '../core/constants/Errorcodes.php';
    require_once "..\core\NyanDateTime.php";
    
    if (session_status() === PHP_SESSION_NONE){
        Session_start();
    }
    require_once '../core/NyanDB.php'; //import class definition


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

    // print_r ([$email,$phone_number,$password])

    ////verify email formats, phone number and password.
    $is_valid_email = (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email)<=254);
    $phone_number_reg = '/^[\d]{8,14}$/';
    $is_valid_phone = (preg_match($phone_number_reg,$phone_number));
    if(!$is_valid_email){
        throw new Exception("email not valid", ERRORCODES::general_error['bad_request']);
    }
    if(!$is_valid_phone){
        throw new Exception("phone number not valid", ERRORCODES::general_error['bad_request']);
    }
    if(!$password){
        throw new Exception("password missing", ERRORCODES::general_error['bad_request']);
    }

    ////retrieve database phone nnumber/email/password
    $sql = "
    SELECT user_id, email, phone_number, hashed_password
    FROM user_auths 
    WHERE email = ? OR phone_number = ?
    LIMIT 1;
    ";
    $results = NyanDB::single_query($sql, [$email, $phone_number]);
    $result = $results->fetch_assoc();
    $results->free();
    if(!empty($result)){
        $error_message = "Account already exists. Please Sign in.";
        throw new Exception($error_message, ERRORCODES::api_signup['email_or_phone_exists']);
    }

    ////send confirmation email 
    null;//TODO

    
    ////insert new user
    $hashed_password = password_hash($password, PASSWORD_ARGON2ID);
    $time_now = NyanDateTime::now();
    $sql = "
    INSERT INTO user_auths (email, phone_number, hashed_password, account_creation_time, last_login_time) 
    VALUES (?, ?, ?, ?, ?);
    ";
    //if insert operation fails the NyanDB class will handle it by throwing a query error
    //also assigns the last inserted user_id to the variable $user_id
    $params = [$email, $phone_number, $hashed_password, $time_now, $time_now];
    $user_id = NyanDB::single_query($sql, $params);


    ////insert new user's user_preference
    $sql = "
    INSERT INTO user_preferences (user_id, is_notify_by_sms, is_notify_by_email, is_notify_by_whatsapp, is_notify_by_telegram)
    VALUES (?,FALSE, TRUE, FALSE, FALSE);
    ";
    NyanDB::single_query($sql, [$user_id]);



    ////signup successful, redirecting to relevant screen should be done outside this file
    $_SESSION['user_id'] = $user_id;
    header("Location: ../../frontend/account_setting_page.php?sign-up-success=true");
    // echo $_SESSION['user_id'];
}
?>