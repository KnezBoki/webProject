<?php
session_start();
include "db_config.php";

function getUserRoleFromDB($email, $password)
{
    $conn = connect();

    $stmt = $conn->prepare("SELECT id, password FROM accounts WHERE email = ?");
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    if ($result && password_verify($password, $result['password'])) {
        $stmt = $conn->prepare("SELECT role_id FROM user_role WHERE account_id = ?");
        $stmt->execute([$result['id']]);
        $role = $stmt->fetch();

        switch ($role['role_id']){
            case 1: $userRole = "admin"; header("Location: admin.php");
            break;
            case 2: $userRole = "worker"; header("Location: tables.php");
            break;
            case 3: $userRole = "user"; header("Location: profile.php");
        }

        return $userRole;
    } else {
        return false;
    }
}




