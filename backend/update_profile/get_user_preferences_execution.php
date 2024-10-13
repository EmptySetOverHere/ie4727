<?php

session_status() === PHP_SESSION_NONE ? session_start(): null;
require_once '../core/NyanDB.php'; //import class definition

////check that session has a user_id
isset($_SESSION['user_id'])? null : throw new Exception('user not logged in',69003);

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
if(empty($result)){
    throw new Exception('user does not exist',69004);
}
$result = $results->fetch_assoc();
$results->free();

////successfully retreived $the user_preference information



?>
