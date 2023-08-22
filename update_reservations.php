<?php
require_once "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reservationId']) && isset($_POST['newStatus'])) {
        $reservationId = $_POST['reservationId'];
        $newStatus = $_POST['newStatus'];

        $conn = connect();

        $stmt = $conn->prepare("UPDATE reservations SET status = ? WHERE reservation_id = ?");
        $stmt->execute([$newStatus, $reservationId]);
    }
}
else{
    header("Location: index.php");
    exit();
}
