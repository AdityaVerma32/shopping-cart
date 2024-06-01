<?php

session_start();

// $hash_string = "gtKFFx|1234567890|120|products|first|first@gmail.com|||||||||||4R38IvwiV57FwVpsgOvTXBdLE4tHUXFW";

// $hash = strtolower(hash('sha512', $hash_string));

// echo "<pre>";
// print_r($hash);
// echo "</pre>";
// unset($_SESSION['cart']);

if (!empty($_SESSION)) {
    echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";
} else {
    $_SESSION['cart_error'] = "Nothing in Session";
    header("Location: http://localhost/shopping-cart/error-msg");
}
