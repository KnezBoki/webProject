<?php
error_reporting(E_ERROR | E_PARSE);

include "db_config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $conn = connect();

    $stmt = $conn->prepare("DELETE FROM accounts WHERE id = ?");

    if(isset($data["worker_id"])){
        $worker_id = $data["worker_id"];
        $stmt->execute([$worker_id]);
    }
    else{
        $user_id = $data["user_id"];
        $stmt->execute([$user_id]);
    }

    $response = array("status" => "success", "message" => "Worker information deleted successfully");
    echo json_encode($response);
}