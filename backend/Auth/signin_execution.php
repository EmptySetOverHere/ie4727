<?php

function signin(){
    require_once '../core/constants/Errorcodes.php';
    require_once '../core/NyanDateTime.php';
    
    if (session_status() === PHP_SESSION_NONE){
        Session_start();
    }
    require_once '../core/NyanDB.php'; //import class definition



    ////assign HTTP request values
    $is_bad_request = !(
        isset($_POST['password']) &&
        (
            (isset($_POST['email']) && isset($_POST['phone_number'])) || 
            isset($_POST['email/phone_number'])
        )
    );
    $is_bad_request ? throw new Exception('bad request', ERRORCODES::general_error['bad_request']) : null;
    $email        = $_POST['email'] ?? $_POST['email/phone_number'];
    $phone_number = $_POST['phone_number'] ?? $_POST['email/phone_number'];
    $password     = $_POST['password'];
    // print_r ([$email,$phone_number,$password]);



    ////verify email formats, phone number and password.
    $is_valid_email = (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email)<=254);
    $phone_number_reg = '/^[\d]{8,14}$/';
    $is_valid_phone = (preg_match($phone_number_reg,$phone_number));
    if(!$is_valid_email && !$is_valid_phone){
        throw new Exception("not a valid email or phone number ", ERRORCODES::general_error['bad_request']);
    }
    if(empty($password)){
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
    if(empty($result)){
        throw new Exception("email / phone number does not exist yet", ERRORCODES::api_signin['user_does_not_exist']);
    }
    if(!password_verify($password , $result['hashed_password'])){
        throw new Exception("wrong password", ERRORCODES::api_signin['wrong_password']);
    }



    ////update last_login_time
    $sql = "
    UPDATE user_auths
    SET last_login_time = ? 
    WHERE user_id = ?;
    ";
    NyanDB::single_query($sql, [NyanDateTime::now(),$result['user_id']]);


    
    ////signin successful, redirecting to relevant screen should be done outside this file
    $_SESSION['user_id'] = $result['user_id'];;//successfull signin
    // echo 'successful sign in';
}
?>
