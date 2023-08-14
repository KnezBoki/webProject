<?php
require "template/header.php";
require "db_config.php";

if(!$_SESSION['logged_in']){
    header("location:login.php");
    exit();
}

$conn = connect();

$id = $_SESSION['id'];

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $res_date = $_POST['datepicker'];
    $guests = $_POST['number-guests'];
    $time = $_POST['time'];
}
?>
<section class="mt-5 pt-5 d-flex justify-content-center">
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
                               for($i = 1; $i <= 12; $i++){
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
            format: 'dd/mm/yyyy',
            showOnFocus:false,
            dateDelimiter: '/',
            weekStart: 1,
            updateOnBlur: false,
        });
    </script>
<?php

require "template/footer.php";

?>