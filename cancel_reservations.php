<?php
require_once "db_config.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["reservationId"])) {
    $reservationId = $_POST["reservationId"];

    $conn = connect();

    $stmt = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE reservation_id = ?");
    $stmt->execute([$reservationId]);
}
else{
    header("Location: index.php");
    exit();
}