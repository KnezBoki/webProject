<?php
session_start();

// Unset and destroy all session variables
$_SESSION['logged_in'] = false;

unset($_SESSION['signup']);
unset($_SESSION['verified']);
unset($_SESSION['userRole']);

session_destroy();

header("Location: login.php");
exit();

