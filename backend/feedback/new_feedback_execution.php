<?php

// function new_feedback(){ TODO
require_once '../core/constants/Errorcodes.php';
require_once '../core/NyanDateTime.php';
require_once '../core/menu_packages.php';
require_once '../core/NyanDB.php';

////check that session has a user_id and assign it
if (session_status() === PHP_SESSION_NONE){
    session_start();
}
if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
} else {
    throw new Exception('user not logged in', ERRORCODES::general_error['invalid_credentials']);
}



////check for user timezone is valid and exists
if(!isset($_SESSION['timezone'])){
    throw new Exception('missing timezone', ERRORCODES::general_error['missing_timezone']);
}
try{
    new DateTimeZone($_SESSION['timezone']);
    $timezone = $_SESSION['timezone'];
} catch (Exception $e) {
    throw new Exception('invalid timezone', ERRORCODES::general_error['missing_timezone']);
}



////verify HTTP request values
////check post request values exist
//check exists
$is_bad_request = !(
    isset($_POST['order_id']) &&
    isset($_POST['app_experience_rating']) &&
    isset($_POST['wait_time_rating']) &&
    isset($_POST['food_quality_rating'])
);
if ($is_bad_request) {
    throw new Exception('missing inputs', ERRORCODES::general_error['bad_request']);
}
//check feedback values valid
$feedbacks = [$_POST['app_experience_rating'], $_POST['wait_time_rating'], $_POST['food_quality_rating']];
foreach ($feedbacks as $feedback){
    try{
        if (!filter_var($feedback, FILTER_VALIDATE_INT) && is_int((int)$feedback) && (int)$feedback >= 1 && (int)$feedback <= 5) {
            throw new Exception('feedback value must be 1-5', ERRORCODES::general_error['bad_request']);
        }
    } catch (Exception $e) {
        throw new Exception('feedback value must be 1-5', ERRORCODES::general_error['bad_request']);
    }    
}
//check that the orderID is valid and the userID corresponds to the orderID
$sql = //TODO

//TODO
////finished execution 

?>