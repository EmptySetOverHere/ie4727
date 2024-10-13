<?php

enum payment_method_enum : int
{
    case mastercard = 1;
    case visa = 2;
    case paynow = 3;
    case paypal = 4;
}

// echo payment_method_enum::mastercard->value;
// echo '<br>'.payment_method_enum::tryfrom(2)->name;
?>