<?php

class ERRORCODES 
{
    //errorcodes: 
    //general user errors -> 69300 - 69599 //unique
    //serverside errrors  -> 69600 - 69999 //unique
    //api specific errors -> 69000 - 69299 //different api can have same error codes


    //usage ERRORCODES::api_update_auth['phone_number_already_exists']

    public const server_error = [
        "database_connection_error" => 69601,
        "database_prepare_error" => 69602,
        "user_does_not_exist" => 69603,
    ];

    public const general_error = [
        "bad_request" => 69300,
        "invalid_credentials" => 69301,
        "missing_timezone" => 69302,
    ];

    public const api_signup = [
        "email_or_phone_taken" => 69003,
    ];

    public const api_signin = [
        "user_does_not_exist" => 69002,
        "email_does_not_exist" => 69003,
        "phone_number_does_not_exist" => 69004,
        "wrong_password" => 69005,
    ];

    public const api_update_auth = [
        "email_already_exists" => 69003,
        "phone_number_already_exists" =>69004,
    ];

    public const api_add_menu_item = [
        "invalid_file_format" => 69005,
    ];

    public const api_add_package = [
        "invalid_file_format" => 69005,
    ];

    public const api_new_order = [
        "order_quantity_too_large" => 69006,
        'package_not_available'=> 69007,
    ];

}

?>