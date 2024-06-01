<?php

// Read the post data from PayPal
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2) {
        $myPost[$keyval[0]] = urldecode($keyval[1]);
    }

}

// Build the request string to validate with PayPal
$req = 'cmd=_notify-validate';
foreach ($myPost as $key => $value) {
    $value = urlencode($value);
    $req .= "&$key=$value";
}

// Post back to PayPal for validation
$ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
$res = curl_exec($ch);
curl_close($ch);

// Check if the IPN message is verified
if (strcmp($res, "VERIFIED") == 0) {
    // Verified IPN
    // Store the IPN data into an array
    $ipn_data = array();
    foreach ($_POST as $key => $value) {
        $ipn_data[$key] = $value;
    }

    // Convert the array to a JSON string
    $json_data = json_encode($ipn_data, JSON_PRETTY_PRINT);

    // Write the JSON string to a file
    $file = 'IPN_data/ipn_data.txt';
    file_put_contents($file, $json_data . PHP_EOL, FILE_APPEND);

    // You can also perform additional actions like updating your database here

    echo "<pre>";
    print_r($ipn_data);
    echo "</pre>";

} else {
    // Invalid IPN, log for manual investigation
    error_log('Invalid IPN: ' . $res);
}
