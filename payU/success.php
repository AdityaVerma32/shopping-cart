<?php
ini_set('session.gc_maxlifetime', 604800);

// each client should remember their session id for EXACTLY 1 week
session_set_cookie_params(604800);

session_start(); // start the session

echo "<pre>";
print_r($_POST);
print_r($_SESSION);

echo "</pre>"
?>
