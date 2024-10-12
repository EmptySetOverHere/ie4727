<?php

session_status() === PHP_SESSION_NONE ? session_start(): null;
require_once '../core/NyanDB.php'; //import class definition

////check that session has a user_id
isset($_SESSION['user_id'])? null : throw new Exception('user not logged in',69003);


////assign HTTP request values
$name                     = $_POST['name'] ?? null;
$address                  = $_POST['address'] ?? null;
$preferred_payment_method = $_POST['preferred_payment_method'] ?? null;
$is_notify_by_sms         = $_POST['is_notify_by_sms'];
$is_notify_by_email       = $_POST['is_notify_by_email'];
$is_notify_by_whatsapp    = $_POST['is_notify_by_whatsapp'];
$is_notify_by_telegram    = $_POST['is_notify_by_telegram'];



////verify values entered
//verify address and payment method TODO
$boolean_array = [$is_notify_by_sms ,$is_notify_by_email,$is_notify_by_whatsapp ,$is_notify_by_telegram];
foreach ($boolean_array as $value) {
    if(!is_bool($value)){
        throw new Exception('invalid input boolean parameters',69000);
    }
}



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
