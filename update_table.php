<?php
error_reporting(E_ERROR | E_PARSE);

include "db_config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);

    $conn = connect();

    $tableId = $data["tableId"];
    $newSeats = $data["newSeats"];
    $location = $data["newLocation"];
    $smoking = $data["newSmoking"];

    try {
        $stmt = $conn->prepare("UPDATE tables SET num_seats = ?, location=?, smoking=? WHERE table_id = ?");
        $stmt->execute([$newSeats,$location, $smoking, $tableId]);

        $response = [
            "status" => "success",
            "newSeatsValue" => $newSeats
        ];
        echo json_encode($response);
    } catch (PDOException $e) {
        $response = [
            "status" => "error",
            "message" => "Error updating table data"
        ];
        echo json_encode($response);
    }
}

