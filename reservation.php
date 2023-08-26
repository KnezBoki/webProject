<?php
require "template/header.php";
require "db_config.php";
require "functions.php";

require_once "db_config.php";
require_once "PHPMailer-6.8.0/vendor/autoload.php";

use PHPMailer\PHPMailer\PHPMailer;

$errorMessage = '';
$freeTable = '';

if(!$_SESSION['logged_in']){
    header("location:login.php");
    exit();
}

$conn = connect();

$email = $_SESSION['email'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $res_date = formatDate($_POST['datepicker']);
    $guestNum = $_POST['number-guests'];
    $res_time = $_POST['time'];
    $res_end = $_POST['time_end'];
    $location = $_POST['location'];
    $smoking = $_POST['smoking'];

    $seatsNeeded = getSeats($guestNum);

    $reservationTimeTimestamp = strtotime($res_time);
    $reservationEndTimestamp = strtotime($res_end);

    if($reservationTimeTimestamp > $reservationEndTimestamp){$errorMessage = 'Please select a valid reservation time!';}
    elseif (($reservationTimeTimestamp + 6 * 3600) < $reservationEndTimestamp){$errorMessage = 'Longest possible reservation period is 6 hours!';}
    else{
        if (!validDate($res_date)) {
            $errorMessage = "Please select a future date!";
        } else {
            $stmt = $conn->prepare("SELECT id FROM accounts WHERE email=?");
            $stmt->execute([$email]);
            $result = $stmt->fetch();

            $id = $result['id'];

            $stmt = $conn->prepare("SELECT table_id FROM tables WHERE num_seats = ? AND location = ? AND smoking =? ");
            $stmt->execute([$seatsNeeded, $location, $smoking]);
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

            foreach ($tables as $table) {
                $stmt = $conn->prepare("SELECT reservation_id FROM reservations WHERE table_id = :table AND status NOT IN ('paid', 'cancelled') AND reservation_date = :res_date AND ((UNIX_TIMESTAMP(reservation_time) <= :res_time AND UNIX_TIMESTAMP(reservation_end) >= :res_time) OR (UNIX_TIMESTAMP(reservation_time) <= :res_end AND UNIX_TIMESTAMP(reservation_end) >= :res_end))");
                $stmt->bindParam(':res_date', $res_date);
                $stmt->bindParam(':res_time', $reservationTimeTimestamp);
                $stmt->bindParam(':res_end', $reservationEndTimestamp);
                $stmt->bindParam(':table', $table);
                $stmt->execute();
                $overlaps = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (empty($overlaps)) {
                    $freeTable = $table;
                    break;
                }
            }

            if (empty($freeTable)) {
                $errorMessage = 'All tables for that time and date have been booked.';
            } else {
                $reservationCode = generateVerificationCode();

                $status = 'arriving';

                $stmt = $conn->prepare("INSERT INTO reservations (account_id, table_id, status, reservation_date, reservation_time, reservation_end, reservation_code) VALUES (:account_id, :table_id, :status,  :reservation_date, :reservation_time,:reservation_end, :reservation_code)");
                $stmt->bindParam(':account_id', $id);
                $stmt->bindParam(':table_id', $freeTable);
                $stmt->bindParam(':reservation_date', $res_date);
                $stmt->bindParam(':status', $status);
                $stmt->bindParam(':reservation_time', $res_time);
                $stmt->bindParam(':reservation_end', $res_end);
                $stmt->bindParam(':reservation_code', $reservationCode);

                if ($stmt->execute()) {

                    $stmt = $conn->prepare("SELECT first_name, last_name, email FROM accounts WHERE id=?");
                    $stmt->execute([$id]);
                    $result = $stmt->fetch();
                    $fname = $result['first_name'];
                    $lname = $result['last_name'];
                    $email = $result['email'];

                    // PHPMailer settings
                    $mail = new PHPMailer();
                    $mail->isSMTP();
                    $mail->Host = 'sandbox.smtp.mailtrap.io';
                    $mail->SMTPAuth = true;
                    $mail->Port = 2525;
                    $mail->Username = '144ca3a32ec68b';
                    $mail->Password = '19c9a32187fb89';

                    // Sender and recipient settings
                    $mail->setFrom('noreply@milfirefox.stud.su.ac.rs', 'Man I Love Food Restaurant');
                    $mail->addAddress($email, $fname . ' ' . $lname);

                    // Email subject and body with verification link
                    $mail->Subject = 'Table reservation';

                    $mail->Body = "Hi $fname $lname,\n\nyou have successfully reserved a table at Man I Love Food. Once you arrive at our restaurant you will need to confirm your reservation by showing this code:\n\n$reservationCode\n\nSincerely,\nMan I Love Food Website Team";

                    if ($mail->send()) {
                        $_SESSION['signup'] = true; // Set the signup flag
                        $errorMessage = "Email with reservation code has been sent!";
                    } else {
                        echo 'Mailer Error: ' . $mail->ErrorInfo;
                    }
                } else {
                    $errorMessage = "Error making reservation: " . $stmt->errorInfo()[2];
                }
            }
        }
    }
}
?>
<section class="mt-5 pt-5 d-flex justify-content-center">
    <?php
        echo '<p>'.$errorMessage.'</p>';
    ?>
    <div class="col-lg-8 pt-3 ">
        <div class="contact-form">
            <form id="contact" action="" method="post">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-12">
                        <h4>Table Reservation</h4>
                    </div>
                    <div class="col-lg-5 mx-5">
                        <div id="filterDate2">
                            <div class="input-group ">
                                <input name="datepicker" id="datepicker" type="text" placeholder="dd-mm-yyyy" required="" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5 col-sm-12 mx-5">
                        <fieldset>
                            <select value="number-guests" name="number-guests" id="number-guests" required="">
                                <option value="" selected disabled>Select number of guests</option>
                               <?php
                               for($i = 1; $i <= 8; $i++){
                                   echo "<option value='$i'>$i</option>";
                               }
                               ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-md-5 mx-5">
                        <fieldset>
                            <select name="time" id="time" required="">
                                <?php
                                for($i = 7; $i < 24; $i++){
                                    echo '<option value="' . $i . ':00:00">' . $i . ' : 00</option>';
                                }
                                ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-md-5 mx-5">
                        <fieldset>
                            <select name="time_end" id="time_end" required="">
                                <?php
                                for($i = 7; $i < 24; $i++){
                                    echo '<option value="' . $i . ':00:00">' . $i . ' : 00</option>';
                                }
                                ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-md-5 mx-5">
                        <fieldset>
                            <select name="location" class="time" id="location" required="">
                                <?php
                                $stmt = $conn->prepare("SELECT DISTINCT location FROM tables");
                                $stmt->execute();
                                $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($locations as $location) {
                                    echo '<option value="' . $location['location'] . '">' . $location['location'] . '</option>';
                                }

                                ?>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-md-5 mx-5">
                        <fieldset>
                            <select name="smoking" class="smoking" id="smoking" required="">
                                <option value="0">No smoking allowed</option>
                                <option value="1">Smoking allowed</option>
                            </select>
                        </fieldset>
                    </div>
                    <div class="col-lg-6 mt-4 mx-5">
                        <fieldset>
                            <button type="submit" id="form-submit" class="main-button-icon">Make A Reservation</button>
                        </fieldset>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
    <script src="assets/datepicker/dist/js/datepicker.min.js"></script>
    <script>
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
<?php

require "template/footer.php";

?>