<?php
    error_reporting(E_ERROR | E_PARSE);

    $password = json_decode(file_get_contents("php://input"))->password;

// Validate password strength
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if (!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password) < 8) {
        $complexity = "Weak";
    } else {
        $complexity = "Strong";
    }

    header("Content-Type: application/json");
    echo json_encode(["complexity" => $complexity]);


