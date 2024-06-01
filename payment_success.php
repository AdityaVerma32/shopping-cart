<?php

require_once 'include/global.inc.php';

if (!empty($_POST)) {
    ini_set('session.gc_maxlifetime', 604800);

    // each client should remember their session id for EXACTLY 1 week
    session_set_cookie_params(604800);

    session_start();

    /*Raw Query

    INSERT INTO `payment_details`(
    `order_id`,
    `transaction_id`,
    `payment_status`,
    `payment_amount`,
    `additional_charges`,
    `net_amount`,
    `payment_timing`,
    `user_name`,
    `payment_address`,
    `payment_city`,
    `payment_state`,
    `payment_country`,
    `payment_zipcode`,
    `payment_email`,
    `payment_phone`,
    `Payment_source`,
    `bank_refer_numb`,
    `card_type`
    )
    VALUES(
    23,
    '403993715531656392',
    'success',
    1234.90,
    23.90,
    1258.8,
    '2024-05-31 17:29:33',
    'adi',
    'dasna',
    'GZB',
    'UP',
    'India',
    '123456',
    'adi@gmail.com',
    '9090909090',
    'payU',
    '231098845701670000',
    'MAST')

    Array
    (
    [mihpayid] => 403993715531656392
    [status] => success
    [key] => gtKFFx
    [txnid] => 2
    [amount] => 30000.00
    [additionalCharges] => 4025.41
    [net_amount_debit] => 34025.41
    [addedon] => 2024-05-31 17:29:33
    [productinfo] => Products
    [firstname] => Jason
    [lastname] => Moon
    [address1] => 157 Milton Court
    [address2] =>
    [city] => Consequat Ut odit e
    [state] => Do autem temporibus
    [country] => AF
    [zipcode] => 690481
    [email] => hozo@mailinator.com
    [payment_source] => payu
    [pa_name] => PayU
    [bank_ref_num] => 231098845701670000
    [card_type] => MAST
    )

     */

    $query_to_payment_details =
        "INSERT INTO `payment_details`(
        `orders_unique_id`,
        `transaction_id`,
        `payment_status`,
        `payment_amount`,
        `additional_charges`,
        `net_amount`,
        `payment_timing`,
        `user_name`,
        `payment_address`,
        `payment_city`,
        `payment_state`,
        `payment_country`,
        `payment_zipcode`,
        `payment_email`,
        `payment_phone`,
        `Payment_source`,
        `bank_refer_numb`,
        `card_type`
    )
    VALUES(
        " . $_POST['txnid'] . ",
        '" . $_POST['mihpayid'] . "',
        '" . $_POST['status'] . "',
        " . $_POST['amount'] . ",
        " . $_POST['additionalCharges'] . ",
        " . $_POST['net_amount_debit'] . ",
        '" . $_POST['addedon'] . "',
        '" . $_POST['firstname'] . "',
        '" . $_POST['address1'] . "',
        '" . $_POST['city'] . "',
        '" . $_POST['state'] . "',
        '" . $_POST['country'] . "',
        '" . $_POST['zipcode'] . "',
        '" . $_POST['email'] . "',
        '" . $_POST['phone'] . "',
        '" . $_POST['payment_source'] . "',
        '" . $_POST['bank_ref_num'] . "',
        '" . $_POST['card_type'] . "')";

    $result = mysqli_query($conn, $query_to_payment_details);
    if (!$result) {
        echo "Nothign could be entered into the db" . mysqli_error($conn);
    }
    $inserted_id = mysqli_insert_id($conn);

    if (!empty($inserted_id)) {
        echo "Order Made Successfull.";
        // echo "<pre>";
        // print_r($_POST);
        // echo "</pre>";
    }
} else {
    header("Location: index.php");
}
