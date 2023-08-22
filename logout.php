<?php
session_start();

// Unset and destroy all session variables
$_SESSION = [];

session_destroy();

header("Location: login.php");
exit();

