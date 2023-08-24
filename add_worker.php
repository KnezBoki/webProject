<?php
error_reporting(E_ERROR | E_PARSE);
header("Content-Type: application/json");

include "db_config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data === null) {
        echo json_encode(array("status" => "error", "message" => "Invalid JSON data"));
        exit;
    }

    $conn = connect();

    $added = false;

    $workerId = $data['workerId'];

    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $gender = $data['gender'];
    $date_of_birth = $data['date_of_birth'];
    $phone = $data['phone'];
    $password = $data['password'];

    // Hash the password before storing it
    $hashedPass = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO accounts (first_name, last_name, email, gender, date_of_birth, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?)");

    if ($stmt->execute([$first_name, $last_name, $email, $gender, $date_of_birth, $phone, $hashedPass])) {
        $added = true;

        if (isset($workerId)) {
            $id = $conn->lastInsertId();

            $stmt = $conn->prepare("UPDATE user_role SET role_id = 2 WHERE account_id=?");
            $stmt->execute([$id]);
        }

        // Fetch the newly inserted user data
        $newUserId = $conn->lastInsertId();
        $stmt = $conn->prepare("SELECT * FROM accounts WHERE id = ?");
        $stmt->execute([$newUserId]);
        $newUser = $stmt->fetch(PDO::FETCH_ASSOC);

        $response = array("status" => "success", "message" => "Worker added successfully", "newUser" => $newUser);
    } else {
        $response = array("status" => "failed", "message" => "Worker couldn't be added!");
    }

    echo json_encode($response);
}

