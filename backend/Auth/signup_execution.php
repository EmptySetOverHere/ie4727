<?php

session_status() === PHP_SESSION_NONE ? session_start(): null;
require_once '../core/NyanDB.php'; //import class definition



////assign HTTP request values
$email        = $_POST['email'];
$phone_number = $_POST['phone_number'];
$password     = $_POST['password'];
// print_r ([$email,$phone_number,$password])



////verify email formats, phone number and password.
$is_valid_email = (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($email)<=254);
$phone_number_reg = '/^[\d]{8,14}$/';
$is_valid_phone = (preg_match($phone_number_reg,$phone_number));
if(!$is_valid_email){
    throw new Exception("email not valid",69000);
}
if(!$is_valid_phone){
    throw new Exception("phone number not valid",69000);
}
if(!$password){
    throw new Exception("password missing",69000);
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
    if(password_verify($password , $result['hashed_password'])){
        require 'api_signin.php';
        exit();
    }
    $error_message = ($email == $result['email'] ? '1' : '0') . ($phone_number == $result['phone_number'] ? '1' : '0');
    throw new Exception($error_message,69003);
    exit(); //exit just in case lol 
}



////send confirmation email 
null;//TODO



////insert new user
$hashed_password = password_hash($password, PASSWORD_ARGON2ID);
$sql = "
INSERT INTO user_auths (email, phone_number, hashed_password, account_creation_time, last_login_time) 
VALUES (?, ?, ?, NOW(), NOW());
";
//if insert operation fails the NyanDB class will handle it by throwing a query error
//also assigns the last inserted user_id to the variable $user_id
$user_id = NyanDB::single_query($sql, [$email, $phone_number, $hashed_password]);



////signup successful, redirecting to relevant screen should be done outside this file
$_SESSION['user_id'] = $user_id;
// echo $_SESSION['user_id'];


?>