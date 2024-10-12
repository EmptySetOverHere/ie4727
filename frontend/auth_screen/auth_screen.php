<?php

session_status() === PHP_SESSION_NONE ? session_start(): null;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Registration page</title>
</head>
<body>		
    <h1><font color="blue">Registration Page</font></h1>
    <!-- action="../../backend/auth/api_signup.php"  -->
     <!-- action="../../backend/auth/api_signin.php"  -->
    <form action="../../backend/auth/api_signup.php" method=POST>
    email:<br />
    <input type=email name=email><br /><br />
    phone number:<br />
    <input type=text name=phone_number><br /><br />
    Password:<br />
    <input type=password name=password><br /><br />
    Password confirmation:<br /> 
    <input type=password><br /><br />

    <input type=submit name=submit value=Submit>
    <input type=reset name=reset value="Reset">
    </form>
</body>
</html>