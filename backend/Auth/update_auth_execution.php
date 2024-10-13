<?php

session_status() === PHP_SESSION_NONE ? session_start(): null;
require_once '../core/NyanDB.php'; //import class definition

////check that session has a user_id
isset($_SESSION['user_id'])? null : throw new Exception('user not logged in',69003);



////assign HTTP request values
$is_bad_request = !(
    isset($_POST['email']) && 
    isset($_POST['phone_number']) && 
    isset($_POST['password'])
);
$is_bad_request ? throw new Exception('bad request',69000) : null;
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
if (NyanDB::single_query($sql, [$email, $phone_number, $hashed_password, $_SESSION['user_id']])){
    null;//TODO
}