<?php
require "template/header.php";
require "db_config.php";
require "functions.php";

$errorMessage = "";

if (!$_SESSION['logged_in']) {
    header("location: login.php");
    exit();
}
else{
    $email = $_SESSION['email'];

    $conn = connect();

    $stmt = $conn->prepare("SELECT id, first_name, last_name, gender, date_of_birth, phone FROM accounts WHERE email=?");
    $stmt->execute([$email]);
    $result = $stmt->fetch();


    $fname = $result['first_name'];
    $lname = $result['last_name'];
    $gender = $result['gender'];
    $rawDate = $result['date_of_birth'];
    $phone = $result['phone'];
    $id = $result['id'];

    $dob = reverseFormatDate($rawDate);
}

if($_SERVER['REQUEST_METHOD'] === "POST"){

    $nf = $_POST['newFname'];
    $nl = $_POST['newLname'];
    $ne = $_POST['newEmail'];
    $ng = $_POST['newGender'];
    $nd = $_POST['datepicker'];
    $np = $_POST['newPhone'];

    if(!validEmail($ne) && $ne != $email){$errorMessage = "There is already an account registered under that email!";}
    elseif(!validDOB($nd)){$errorMessage = "You must be at least 13 year old to make an accout!";}
    else {
        $nd = formatDate($nd);
        $conn = connect();

        $stmt = $conn->prepare("UPDATE accounts SET first_name = ?, last_name = ?, email = ?, gender = ?, date_of_birth = ?, phone = ? WHERE email = ?");
        $stmt->execute([$nf, $nl, $ne, $ng, $nd, $np, $email]);

        header("Location: profile.php");
        exit();
    }
}

?>
<div id="successPopup" class="popup hidden">Reservation successfully updated!</div>
<div id="failPopup" class="popup hidden">Reservation can only be cancelled within 4 hours before the reservation time.</div>
<section id="profileData" class="my-3 py-5">
    <div class="container mt-5">
        <?php if(!empty($errorMessage)){
            echo '<p>'.$errorMessage.'</p>';
        }
        ?>
        <div class="row justify-content-center">
            <div class="col-lg-12 text-center mb-4">
                <h2>Profile</h2>
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

            <div class="col-md-12 d-flex justify-content-center mt-4">
                <div class="col-md-4">
                    <button id="editProfileBtn" class="btnMain">Edit Profile</button>
                </div>
            </div>

            <!-- *** Edit Profile Form ***-->
            <div class="col-lg-12 mt-5 text-center" id="editProfileForm" style="display: none;">
                <form id="updateProfileForm" class="contact-form" method="post" action="profile.php">
                    <div class="row justify-content-center">
                        <!-- *** First Name ***-->
                        <div class="col-md-5 mb-3">
                            <label for="newFname">First Name</label>
                            <input type="text" id="newFname" name="newFname" class="form-control" value="<?php echo $fname; ?>">
                        </div>
                        <!-- *** Last Name ***-->
                        <div class="col-md-5 mb-3">
                            <label for="newLname">Last Name</label>
                            <input type="text" id="newLname" name="newLname" class="form-control" value="<?php echo $lname; ?>">
                        </div>
                        <!-- *** Email ***-->
                        <div class="col-md-5 mb-3">
                            <label for="newEmail">Email</label>
                            <input type="text" id="newEmail" name="newEmail" class="form-control" value="<?php echo $email; ?>">
                        </div>
                        <!-- *** Gender ***-->
                        <div class="col-md-5 mb-3">
                            <label for="newGender">Gender</label>
                            <select name="newGender" id="newGender" class="form-control">
                                <?php
                                echo ($gender == 'Male') ? '<option name="Male" id="Male" selected>Male</option>' : '<option name="Male" id="Male">Male</option>';
                                echo ($gender == 'Female') ? '<option name="Female" id="Female" selected>Female</option>' : '<option name="Female" id="Female">Female</option>';
                                echo ($gender == 'Other') ? '<option name="Other" id="Other" selected>Other</option>' : '<option name="Other" id="Other">Other</option>';
                                ?>
                            </select>
                        </div>
                        <!-- *** Date of birth ***-->
                        <div class="col-md-5 mb-3">
                            <label for="newDate">Date of Birth</label>
                            <input type="text" id="newDate" name="datepicker" class="form-control" value="<?php echo $dob; ?>">
                        </div>
                        <!-- *** Phone ***-->
                        <div class="col-md-5 mb-3">
                            <label for="newPhone">Phone</label>
                            <input type="text" id="newPhone" name="newPhone" class="form-control" value="<?php echo $phone; ?>">
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-12 d-flex justify-content-center">
                            <div class="col-md-6">
                                <button type="submit" class="btnMain">Save Changes</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
<section id="profileReservations">
    <div class="container">
        <div class="row d-flex justify-content-center p-5">
            <div class="col-lg-12 text-center mb-4">
                <h2 class="text-success">Live Reservations</h2>
            </div>
            <?php
            $stmt = $conn->prepare("SELECT reservation_id, status, reservation_date, reservation_time FROM reservations WHERE account_id = $id");
            $stmt->execute();

            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($reservations as $reservation) {
                $reservationId = $reservation['reservation_id'];
                $status = $reservation['status'];
                $reservationDate = $reservation['reservation_date'];
                $reservationTime = $reservation['reservation_time'];

                $currentDateTime = new DateTime();
                $reservationDateTime = new DateTime($reservationDate . ' ' . getReservationTime($reservationTime));
                $fourHoursBefore = clone $reservationDateTime;
                $fourHoursBefore->modify('-4 hours');

                if($status != 'paid' && $status !='cancelled' && $currentDateTime > $fourHoursBefore && $currentDateTime->format('Y-m-d') == $reservationDate) {
                    $stmt = $conn->prepare("SELECT first_name, last_name FROM accounts WHERE id =?");
                    $stmt->execute([$id]);
                    $result = $stmt->fetch();

                    $fname = $result['first_name'];
                    $lname = $result['last_name'];

                    $name = $fname . ' ' . $lname;

                    echo '<div class="col-sm-4 border border-dark">
                            <div class="row text-center">
                                <div class="row">
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
                                </div>
                            </div>
                        </div> ';
                }
            }
            ?>

        </div>
        <div class="row d-flex justify-content-center p-5">
            <div class="col-lg-12 text-center mb-4">
                <h2>Reservations</h2>
            </div>
            <?php
            $stmt = $conn->prepare("SELECT reservation_id, status, reservation_date, reservation_time FROM reservations WHERE account_id = $id");
            $stmt->execute();

            $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($reservations as $reservation) {
                $reservationId = $reservation['reservation_id'];
                $status = $reservation['status'];
                $reservationDate = $reservation['reservation_date'];
                $reservationTime = $reservation['reservation_time'];

                $currentDateTime = new DateTime();
                $reservationDateTime = new DateTime($reservationDate . ' ' . getReservationTime($reservationTime));
                $fourHoursBefore = clone $reservationDateTime;
                $fourHoursBefore->modify('-4 hours');


                if($status != 'paid' && $status !='cancelled' && $currentDateTime < $fourHoursBefore) {
                    $stmt = $conn->prepare("SELECT first_name, last_name FROM accounts WHERE id =?");
                    $stmt->execute([$id]);
                    $result = $stmt->fetch();

                    $fname = $result['first_name'];
                    $lname = $result['last_name'];

                    $name = $fname . ' ' . $lname;

                    echo '<div class="col-sm-4 border border-dark">
                            <form id="cancelReservation_'.$reservationId.'" method="post" onsubmit="return false;">
                            <input type="hidden" name="reservationId" value="'.$reservationId.'">
                                <div class="row text-center">
                                    <div class="row ">
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
                                        <div class="row my-2 d-flex justify-content-center">
                                            <div class="col-md-8">
                                               <input type="submit" class="btnMain" value="Cancel" onclick="cancelReservation('.$reservationId.', \''.$reservationDate.'\', \''.$reservationTime.'\')">

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
</section>
<script src="assets/datepicker/dist/js/datepicker.js"></script>
<script src="assets/js/reservation.js"></script>
<script>
    //Show and hide Edit Profile form
    document.addEventListener("DOMContentLoaded", function () {
        const editBtn = document.getElementById("editProfileBtn");
        const editForm = document.getElementById("editProfileForm");

        editBtn.addEventListener("click", function () {
            editForm.style.display = editForm.style.display === "none" ? "flex" : "none";
        });
    });

    //Datepicker required DO NO CHANGE!
    let elem = document.querySelector('input[name="datepicker"]');
    let datepicker = new Datepicker(elem, {
        autohide: true,
        format: 'dd-mm-yyyy',
        showOnFocus:false,
        dateDelimiter: '/',
        weekStart: 1,
        updateOnBlur: false,
    });
</script>
<?php require "template/footer.php"; ?>
