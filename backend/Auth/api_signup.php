<?php

session_start();

require '../database/NyanDB.php';


// echo session_id();

// echo $_SESSION['bob']; //$_SESSION['bob'] is = to 'bobicus'
// $hashedPassword = password_hash($_SESSION['bob'], PASSWORD_ARGON2ID);
// echo '<br>';
// echo $hashedPassword;
// echo password_verify('bobicus',$hashedPassword) ? '<br>yay bob' : '<br>u not bob';

$email        = $_POST['email'];
$phone_number = $_POST['phone_number'];
$password     = $_POST['password'];

// print_r ([$email,$phone_number,$password])

////verify phone number and email formats.TODO

////retrieve database phone nnumber/email/password
$sql = "
SELECT hashed_password
FROM user_auths 
WHERE email = '$email' AND phone_number = '$phone_number'
LIMIT 1;
";
$results = NyanDB::single_query($sql);
$result = $results->fetch_assoc();
$results->free();
print_r($result);
if (!empty($result)){
    echo 'hey such account with same name/email already exists';
    //redirect to signin TODO
    exit();
} else {
    //its a new sign in, proceed
}

////send confirmation email TODO


////insert new user
$hashed_password = password_hash($_SESSION['bob'], PASSWORD_ARGON2ID);
$sql = "
INSERT INTO user_auths (email, phone_number, hashed_password) 
VALUES ('$email', '$phone_number', '$hashed_password');
";
NyanDB::single_query($sql); //if insert operation fails the class will handle it by throwing an error
echo 'yay signup done';

////signup successful redirect to relevant screen


?>