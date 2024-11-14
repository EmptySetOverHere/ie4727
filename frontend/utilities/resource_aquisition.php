<?php 
require_once "..\backend\core\NyanDB.php";

function aquire_username_or_default($default_username): string | null {
    if(!($_SERVER["REQUEST_METHOD"] === "GET" && 
        session_status() === PHP_SESSION_ACTIVE && 
        isset($_SESSION["user_id"]))
    ) {
        return null;
    } 

    if (isset($_SESSION["username"])) {
        return $_SESSION["username"];
    } 

    $sql = "SELECT name AS username FROM user_preferences WHERE user_id = ?";

    $result = NyanDB::single_query($sql, [$_SESSION["user_id"]]);

    if($result->num_rows > 1 || $result->num_rows === 0) {
        throw "More than one users are sharing the same user_id";
    }
    
    $result = $result->fetch_assoc();
    if($result["username"] === null) {
        return $default_username;
    } 

    $_SESSION["username"] = $result["username"]; 
    return $_SESSION["username"]; 
}
?>