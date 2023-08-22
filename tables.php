<?php
require "template/header.php";
require "db_config.php";
require "functions.php";

if (!$_SESSION['logged_in']) {
    header("location: login.php");
    exit();
}
else {
    $email = $_SESSION['email'];

    $conn = connect();

    $stmt = $conn->prepare("SELECT first_name, last_name, gender, date_of_birth, phone FROM accounts WHERE email=?");
    $stmt->execute([$email]);
    $result = $stmt->fetch();

    $fname = $result['first_name'];
    $lname = $result['last_name'];
    $gender = $result['gender'];
    $rawDate = $result['date_of_birth'];
    $phone = $result['phone'];

    $dob = reverseFormatDate($rawDate);


    $sql = "SELECT COUNT(*) as row_count FROM tables";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $maxT = $result['row_count']; //NUMBER OF TABLES IN RESTAURANT FROM DB
}


?>
<section class="my-5 pt-md-4 pt-5 pb-5">
    <div class="container-fluid py-5">
        <div id="successPopup" class="popup hidden">Reservation successfully updated!</div>
        <div class="row">
            <!-- Side Menu -->
            <nav class="main-nav col-md-2 col-12 d-md-block d-lg-block d-inline-block sidebar">
                <div>
                    <ul class="nav d-flex flex-column flex-md-custom">
                        <li class="nav-item mb-2 mb-md-0">
                            <a class="nav-link" id="btnWorker">Worker Profile</a>
                        </li>
                        <li class="nav-item mb-2 mb-md-0">
                            <a class="nav-link" id="btnTables">Tables</a>
                        </li>
                        <li class="nav-item mb-2 mb-md-0">
                            <a class="nav-link" id="btnRes">Reservations</a>
                        </li>
                    </ul>

                </div>
            </nav>

            <!-- Main Content -->
            <main class="col-md-10 col-12 bg-light">
                <div class="container p-2" id="worker">
                    <div class="row ">
                        <div class="col-lg-12 text-center mb-4">
                            <h2>Worker Profile</h2>
                        </div>
                        <!-- *** Name *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Name:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $fname . ' ' . $lname; ?></h4>
                            </div>
                        </div>

                        <!-- *** Email *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Email:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $email; ?></h4>
                            </div>
                        </div>

                        <!-- *** Gender *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Gender:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $gender; ?></h4>
                            </div>
                        </div>

                        <!-- *** Date of birth *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Date of Birth:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $dob; ?></h4>
                            </div>
                        </div>

                        <!-- *** Phone *** -->
                        <div class="row mb-2">
                            <div class="col-md-5 text-sm-end">
                                <label class="font-weight-bold">Phone:</label>
                            </div>
                            <div class="col-md-7">
                                <h4><?php echo $phone; ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container p-2 d-none" id="tables">
                    <!-- *** List of tables and details about the tables *** -->
                    <div class="row d-flex justify-content-center">
                        <!-- *** Tables *** -->
                        <?php
                        for ($i = 1; $i <= $maxT; $i++) {
                            $stmt = $conn->prepare("SELECT num_seats FROM tables WHERE table_id = ?");
                            $stmt->execute([$i]);
                            $res = $stmt->fetch();

                            $t = $i;
                            $seats = $res['num_seats'];

                            echo '<div class="col-sm-4 border border-dark ">
                                    <div class="row text-center">
                                        <div class="row">
                                            <div class="col-md-3 text-sm-start">
                                                <label class="font-weight-bold">Table:</label>
                                            </div>
                                            <div class="col-md-3 text-md-start">
                                                <h4>' . $t . '</h4>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-md-3 text-sm-start">
                                                    <label class="font-weight-bold">Seats:</label>
                                                </div>
                                                <div class="col-md-3 text-md-start">
                                                    <h4>' . $seats . '</h4>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                   </div>';
                        }
                        ?>
                    </div>
                </div>
                <div class="container p-2 d-none" id="res">
                    <!-- *** List of reservation (on click show details?) *** -->
                    <div class="row d-flex justify-content-center">
                        <!-- *** Tables *** -->
                        <?php
                        $stmt = $conn->prepare("SELECT reservation_id, account_id, table_id, status, reservation_date, reservation_time FROM reservations");
                        $stmt->execute();

                        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($reservations as $reservation) {
                            $reservationId = $reservation['reservation_id'];
                            $accountId = $reservation['account_id'];
                            $tableId = $reservation['table_id'];
                            $status = $reservation['status'];
                            $reservationDate = $reservation['reservation_date'];
                            $reservationTime = $reservation['reservation_time'];

                            if($status != 'paid' && $status != 'cancelled') {
                                $stmt = $conn->prepare("SELECT first_name, last_name FROM accounts WHERE id =?");
                                $stmt->execute([$accountId]);
                                $result = $stmt->fetch();

                                $fname = $result['first_name'];
                                $lname = $result['last_name'];

                                $name = $fname . ' ' . $lname;

                                echo '<div class="col-sm-4 border border-dark ">
                                    <form id="updateReservation_'.$reservationId.'" method="post" onsubmit="return false;">
                                    <input type="hidden" name="reservationId" value="'.$reservationId.'">
                                        <div class="row text-center">
                                            <div class="row">
                                                <div class="col-md-4 text-sm-start">
                                                    <label class="font-weight-bold">Table:</label>
                                                </div>
                                                <div class="col-md-8 text-md-start">
                                                    <h4>' . $tableId . '</h4>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-4 text-sm-start">
                                                        <label class="font-weight-bold">Made by:</label>
                                                    </div>
                                                    <div class="col-md-8 text-md-start">
                                                        <h4>' . $name . '</h4>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-4 text-sm-start">
                                                        <label class="font-weight-bold">Date:</label>
                                                    </div>
                                                    <div class="col-md-8 text-md-start">
                                                        <h4>' . $reservationDate . '</h4>
                                                    </div>
                                                </div>
                                                <div class="row mb-1">
                                                    <div class="col-md-4 text-sm-start">
                                                        <label class="font-weight-bold">Time:</label>
                                                    </div>
                                                    <div class="col-md-8 text-md-start">
                                                        <h4>' . $reservationTime . '</h4>
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 text-sm-start">
                                                        <label class="font-weight-bold">Status:</label>
                                                    </div>
                                                    <div class="col-md-8 text-md-start">
                                                        <h4><select name="newStatus" onchange="updateReservationStatus('.$reservationId.', this.value)"> 
                                                        <option name="current" id="current">' . $status . '</option>
                                                        <option name="arriving" value="arriving">Arriving</option>
                                                        <option name="seated" value="seated">Seated</option>
                                                        <option name="ordered" value="ordered">Ordered</option>
                                                        <option name="served" value="served">Served</option>
                                                        <option name="paid" value="paid">Paid</option>
                                                        </select></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div> ';
                            }
                        }
                        ?>

                    </div>
                </div>
            </main>
        </div>
    </div>
</section>
    <script src="assets/js/reservation.js"></script>
<?php
require "template/footer.php";
