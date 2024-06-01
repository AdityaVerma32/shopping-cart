<?php

echo "Error ";

session_start();

if (!empty($_SESSION['cart_error'])) {
    $error_msg = $_SESSION['cart_error'];

    echo "<div style='margin: auto;
            width: 50%;
            border: 3px solid red;
            padding: 10px;'><h1 style='text-align:center'>" . $error_msg . "</h1></div><br><br>";

    unset($_SESSION['cart_error']);
}
