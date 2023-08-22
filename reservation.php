<?php
require "template/header.php";
require "db_config.php";
require "functions.php";

$errorMessage = '';

if(!$_SESSION['logged_in']){
    header("location:login.php");
    exit();
}

$conn = connect();

$email = $_SESSION['email'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $res_date = formatDate($_POST['datepicker']);
    $guests = $_POST['number-guests'];
    $res_time = $_POST['time'];

    if(!validDate($res_date)){
        $errorMessage = "Please select a future date!";
    }
    else {
        $stmt = $conn->prepare("SELECT id FROM accounts WHERE email=?");
        $stmt->execute([$email]);
        $result = $stmt->fetch();

        $acc = $result['id'];

        $seatsNeeded = 0;

        switch ($guests) {
            case $guests < 3:
                $seatsNeeded = 2;
                break;
            case 3 < $guests && $guests < 5:
                $seatsNeeded = 4;
                break;
            case 5 < $guests:
                $seatsNeeded = 8;
                break;
        }

        $stmt = $conn->prepare("SELECT table_id FROM tables WHERE num_seats = ?");
        $stmt->execute([$seatsNeeded]);
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        //Goes through all tables based on amount of seats need and checks if they are booked
        foreach ($tables as $table) {
            $stmt = $conn->prepare("SELECT reservation_id FROM reservations WHERE reservation_date = :res_date AND reservation_time = :res_time AND table_id = :tab AND status != 'paid'");
            $stmt->bindParam(':res_date', $res_date);
            $stmt->bindParam(':res_time', $res_time);
            $stmt->bindParam(':tab', $table);
            $stmt->execute();
            $result = $stmt->fetch();

            if (!$result) {
                $tab = $table; // Found an available table
                break;
            }
        }

        if (empty($tab)) {
            $errorMessage = 'All tables for that time and date have been booked.';
        } else {
            $stmt = $conn->prepare("INSERT INTO reservations (account_id, table_id, reservation_date, reservation_time) VALUES (:account_id, :table_id, :reservation_date, :reservation_time)");
            $stmt->bindParam(':account_id', $acc);
            $stmt->bindParam(':table_id', $tab);
            $stmt->bindParam(':reservation_date', $res_date);
            $stmt->bindParam(':reservation_time', $res_time);

            if ($stmt->execute()) {
                $errorMessage = "Reservation successfully inserted.";
            } else {
                $errorMessage = "Error inserting reservation: " . $stmt->errorInfo()[2];
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
                                <input name="datepicker" id="date" type="text" placeholder="dd/mm/yyyy" required="" autocomplete="off">
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
                            <select name="time" class="time" id="time" required="">
                                <option value="" selected disabled>Select reservation time</option>
                                <option value="Breakfast">Breakfast (7h - 11h)</option>
                                <option value="Lunch">Lunch (12h - 16h)</option>
                                <option value="Dinner">Dinner (18h - 22h)</option>
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