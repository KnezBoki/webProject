<?php
include "db_config.php";

$conn = connect();

ob_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $filterDate = $data["filterDate"] ?? '';


    $stmt = $conn->prepare("SELECT * FROM reservations");
    $stmt->execute();

    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($reservations as $reservation) {
        if ($reservation['reservation_date'] === $filterDate || empty($filterDate)) {
            $reservationId = $reservation['reservation_id'];
            $accountId = $reservation['account_id'];
            $tableId = $reservation['table_id'];
            $status = $reservation['status'];
            $reservationDate = $reservation['reservation_date'];
            $reservationTime = $reservation['reservation_time'];
            $reservationEnd = $reservation['reservation_end'];
            $reservationCode = $reservation['reservation_code'];

            $stmt = $conn->prepare("SELECT first_name, last_name FROM accounts WHERE id =?");
            $stmt->execute([$accountId]);
            $result = $stmt->fetch();

            $fname = $result['first_name'];
            $lname = $result['last_name'];

            $name = $fname . ' ' . $lname;

            echo '<li class="col-sm-4 d-sm-inline-block"><div class="d-flex justify-content-center border border-dark reservation_item" style="min-height: 500px;">
        <form id="updateReservation_' . $reservationId . '" method="post" onsubmit="return false;" class="align-items-center">
            <input type="hidden" name="reservationId" value="' . $reservationId . '">
            <div class="row mb-1">
                <div class="col-sm-6 text-center">
                    <label class="font-weight-bold">Table:</label>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-8 text-center">
                    <h4 class="table-filter">' . $tableId . '</h4>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-6 text-center">
                    <label class="font-weight-bold">Made by:</label>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-8 text-center">
                    <h4>' . $name . '</h4>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-6 text-center">
                    <label class="font-weight-bold">Date:</label>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-8 text-center">
                    <h4 class="date-filter">' . $reservationDate . '</h4>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-6 text-center">
                    <label class="font-weight-bold">Time:</label>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-md-8 text-center">
                    <h4 class="time-filter">' . $reservationTime . '</h4>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-6 text-center">
                    <label class="font-weight-bold">End:</label>
                </div>
                <div class="col-md-8 text-center">
                    <h4 class="end-filter">' . $reservationEnd . '</h4>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-sm-6 text-center">
                    <label class="font-weight-bold">Status:</label>
                </div>
                <div class="col-md-8 text-center">
                    <h4 class="status-filter">' . $status . '</h4>
                </div>
            </div>
            <div class="row mb-1">
                <div class="col-sm-6 text-center">
                    <label class="font-weight-bold">Reservation Code:</label>
                </div>
                <div class="col-md-8 text-center" style="white-space: normal; word-wrap: break-word">
                    <h4 class="reservationCode-filter">' . $reservationCode . '</h4>
                </div>
            </div>
        </form>
    </div></li>';
        }
    }
}
$reservationList = ob_get_clean();

$response = [
    'reservationList' => $reservationList
];

header('Content-Type: application/json');
echo json_encode($response);