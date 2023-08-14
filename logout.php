<?php
session_start();

// Unset or destroy the session variables as needed
unset($_SESSION['logged_in']);
unset($_SESSION['userRole']);

session_destroy();

header("Location: login.php");
exit();
?>
