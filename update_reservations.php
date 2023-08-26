<?php
require_once "db_config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['reservationId']) && (isset($_POST['newStatus']) || isset($_POST['workerComment']))) {
        $reservationId = $_POST['reservationId'];
        $newStatus = $_POST['newStatus'];
        $workerComment = $_POST['workerComment'];

        $conn = connect();

        $stmt = $conn->prepare("UPDATE reservations SET status = ?, worker_comment = ? WHERE reservation_id = ?");
        $result = $stmt->execute([$newStatus, $workerComment, $reservationId]);
    }
}
else {
    header("Location: index.php");
    exit();
}