<?php 

function aquire_username_or_default($default_username): string | null {
    if($_SERVER["REQUEST_METHOD"] === "GET" && session_status() === PHP_SESSION_ACTIVE && isset($_SESSION["username"])) {
        return isset($_SESSION["username"]) ? $_SESSION["username"] : $default_username;         
    } else {
        return null;
    }
}
?>