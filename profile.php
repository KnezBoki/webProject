<?php
require "template/header.php";
require "db_config.php";
require "functions.php";

if (!$_SESSION['logged_in']) {
    header("location: index.php");
    exit();
}
else{
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
}
?>
<section class="my-3 py-5">
    <div class="container mt-5">
        <div class="row ">
            <div class="col-lg-12 text-center mb-4">
                <h2>Profile</h2>
            </div>
            <!-- *** Name *** -->
            <div class="col-md-6 text-md-right"><h4 class="font-weight-bold">Name:</h4></div>
            <div class="col-md-6 mb-2"><h4><?php echo $fname . ' ' . $lname; ?></h4></div>
            <!-- *** Email *** -->
            <div class="col-md-6 text-md-right"><h4 class="font-weight-bold">Email:</h4></div>
            <div class="col-md-6 mb-2"><h4><?php echo $email; ?></h4></div>
            <!-- *** Gender *** -->
            <div class="col-md-6 text-md-right"><h4 class="font-weight-bold">Gender:</h4></div>
            <div class="col-md-6 mb-2"><h4><?php echo $gender; ?></h4></div>
            <!-- *** Date of birth *** -->
            <div class="col-md-6 text-md-right"><h4 class="font-weight-bold">Date of Birth:</h4></div>
            <div class="col-md-6 mb-2"><h4><?php echo $dob; ?></h4></div>
            <!-- *** Phone *** -->
            <div class="col-md-6 text-md-right"><h4 class="font-weight-bold">Phone:</h4></div>
            <div class="col-md-6 mb-2"><h4><?php echo $phone; ?></h4></div>
            <div class="col-md-4 text-center mt-4 mx-md-5">
                <button id="editProfileBtn" class="btnMain">Edit Profile</button>
            </div>
            <!-- *** Edit Profile Form ***-->
            <div class="col-lg-12 mt-5 justify-content-center text-center" id="editProfileForm" style="display: none;">
                <form id="updateProfileForm" class="contact-form" method="post" action="">
                    <div class="row">
                        <!-- *** First Name ***-->
                        <div class="col-md-6">
                            <label class="col-sm-4" for="newFname">First Name</label>
                            <input type="text"  id="newFname" name="newFname" class="col-sm-7" value="<?php echo $fname; ?>">
                        </div>
                        <!-- *** Last Name ***-->
                        <div class="col-md-6">
                            <label for="newLname" class="col-sm-4">Last Name</label>
                            <input type="text" id="newLname" name="newLname" class="col-md-7"  value="<?php echo $lname; ?>">
                        </div>
                        <!-- *** Email ***-->
                        <div class="col-md-6">
                            <label class="col-sm-4" for="newEmail">Email</label>
                            <input type="text"  id="newEmail" name="newEmail" class="col-sm-7" value="<?php echo $email; ?>">
                        </div>
                        <!-- *** Gender ***-->
                        <div class="col-md-6">
                            <label for="newGender" class="col-sm-4">Gender</label>
                            <select name="newGender" id="newGender" class="col-sm-7">
                                <?php
                                echo ($gender == 'Male') ? '<option name="Male" id="Male" selected>Male</option>' : '<option name="Male" id="Male">Male</option>';
                                echo ($gender == 'Female') ? '<option name="Female" id="Female" selected>Female</option>' : '<option name="Female" id="Female">Female</option>';
                                echo ($gender == 'Other') ? '<option name="Other" id="Other" selected>Other</option>' : '<option name="Other" id="Other">Other</option>';
                                ?>
                            </select>
                        </div>
                        <!-- *** Date of birth ***-->
                        <div class="col-md-6">
                            <label class="col-sm-4" for="newDate">Date of Birth</label>
                            <input type="text"  id="newDate" name="datepicker" class="col-sm-7" value="<?php echo $dob; ?>">
                        </div>
                        <!-- *** Phone ***-->
                        <div class="col-md-6">
                            <label class="col-sm-4" for="newPhone">Phone</label>
                            <input type="text"  id="newPhone" name="newPhone" class="col-sm-7" value="<?php echo $phone; ?>">
                        </div>

                    </div>
                    <div class="row d-flex justify-content-center mt-5">
                        <div class="col-lg-6">
                            <button type="submit" class="main-button-icon">Save Changes</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</section>
<script src="assets/datepicker/dist/js/datepicker.js"></script>
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
        format: 'dd/mm/y',
        showOnFocus:false,
        dateDelimiter: '/',
        weekStart: 1,
        updateOnBlur: false,
    });
</script>

<?php require "template/footer.php"; ?>
