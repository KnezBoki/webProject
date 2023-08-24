<?php
error_reporting(E_ERROR | E_PARSE);

include "db_config.php";
include "functions.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $data = json_decode(file_get_contents("php://input"), true);

    $conn = connect();

    $first_name = $data["first_name"];
    $last_name = $data["last_name"];
    $email = $data["email"];
    $gender = $data["gender"];
    $date_of_birth = $data["date_of_birth"];
    $phone = $data["phone"];

    $stmt = $conn->prepare("UPDATE accounts SET first_name=?, last_name=?, email=?, gender=?, date_of_birth=?, phone=? WHERE id=?");

    if($data["worker_id"]){
        $worker_id = $data["worker_id"];
        $stmt->execute([$first_name, $last_name, $email, $gender, $date_of_birth, $phone, $worker_id]);
    }
    else{
        $user_id = $data["user_id"];
        $stmt->execute([$first_name, $last_name, $email, $gender, $date_of_birth, $phone, $user_id]);
    }

    $response = array("status" => "success", "message" => "Worker information updated successfully");
    echo json_encode($response);
}

