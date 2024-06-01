<?php

/*

This function is adding the seleted product into cart(Session)
Also check if the product quantity limit has reached or not

 */

session_start();

require_once 'include/global.inc.php';

// $_SESSION['Hello'] = "Aditya";

echo "<pre>";
if (!empty($_SESSION)) {
    print_r($_SESSION);
}
echo "</pre>";
// die();

if (!empty($_GET['product_id'])) {
    $id = $_GET['product_id'];

    $query_get_details =
        "SELECT
    `product_id`,
    `product_name`,
    `product_description`,
    `product_price`,
    `product_image`,
    `product_quantity`,
    `product_status`,
    `product_date_available`
    FROM
    `products`
    WHERE product_id = " . $id . "";

    $result = mysqli_query($conn, $query_get_details);

    if (!$result) {
        echo mysqli_error($conn);

    }
    $details = mysqli_fetch_all($result);

    if (!empty($_SESSION['cart'])) { // if cart is already defined

        if (!empty($_SESSION['cart'][$id])) { // check if the product id exists in the SESSION(cart)

            $previous_qty = $_SESSION['cart'][$id][0];
            if ($details[0][5] > $previous_qty) {
                $_SESSION['cart'][$id] = array($previous_qty + 1, $details[0][0], $details[0][1], $details[0][2], $details[0][3], $details[0][4]);
                $_SESSION['cart_error'] = $details[0][1] . " added to cart";
                header("Location: http://localhost/shopping-cart/error-msg");
            } else {
                $_SESSION['cart_error'] = $details[0][1] . " out of Stock";
                header("Location: http://localhost/shopping-cart/error-msg");

            }
        } else { // if the product id does not exist in the SESSION(cart)

            if ($details[0][5] > 0) {
                $_SESSION['cart'][$id] = array(1, $details[0][0], $details[0][1], $details[0][2], $details[0][3], $details[0][4]);
                $_SESSION['cart_error'] = $details[0][1] . " added to cart";
                header("Location: http://localhost/shopping-cart/error-msg");
            } else {
                $_SESSION['cart_error'] = $details[0][1] . " out of Stock";
                header("Location: http://localhost/shopping-cart/error-msg");
            }

        }
    } else {
        $_SESSION['cart'][$id] = array(1, $details[0][0], $details[0][1], $details[0][2], $details[0][3], $details[0][4]);
        $_SESSION['cart_error'] = $details[0][1] . " added to cart";
        header("Location: http://localhost/shopping-cart/error-msg");
    }

} else {
    header("Location: index.php");
}
