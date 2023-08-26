<?php
require "template/header.php";
require "db_config.php";
require "functions.php";

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header("location: index");
    exit();
}

$conn = connect();

$timeout = 60; //1 minute

use PHPMailer\PHPMailer\PHPMailer;
require_once "PHPMailer-6.8.0/vendor/autoload.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Filtered inputs
    $fname = filter_input(INPUT_POST, 'fname', FILTER_UNSAFE_RAW);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_UNSAFE_RAW);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $gender = $_POST['gender'];
    $rawDate = $_POST['datepicker'];
    $phone = filter_input(INPUT_POST, 'phone', FILTER_UNSAFE_RAW);

    $errorMessage = '';

    $date = formatDate($rawDate);

    // Check if the email is in timeout (wip)
    $lastRequest = isset($_SESSION['last_request']) ? $_SESSION['last_request'] : 0;
    if (time() - $lastRequest < $timeout) {
        $errorMessage = "Please wait before making another request.";
    }
    else {
        $errorMessage ='';

        if (!validEmail($email)) {
            $errorMessage = "There is already an account registered under that email!";
        } elseif (!validDOB($date)) {
            $errorMessage = "You must be at least 13 years old to make an account!";
        } else {

            $stmt = $conn->prepare("INSERT INTO email_requests (email, last_request) VALUES (?, NOW()) ON DUPLICATE KEY UPDATE last_request = NOW()");
            $stmt->execute([$email]);

            $_SESSION['fname'] = $fname;
            $_SESSION['lname'] = $lname;
            $_SESSION['email'] = $email;
            $_SESSION['gender'] = $gender;
            $_SESSION['date'] = $date;
            $_SESSION['phone'] = $phone;

            $verificationCode = generateVerificationCode();

            $_SESSION['verificationCode'] = $verificationCode;

            //UPDATE EMAIL FOR SERVER! on reservation.php as well

            // PHPMailer settings
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = '144ca3a32ec68b';
            $mail->Password = '19c9a32187fb89';

            // Sender and recipient settings
            $mail->setFrom('noreply@milfirefox.com', 'Man I Love Food');
            $mail->addAddress($email, $fname . ' ' . $lname);

            // Email subject and body with verification link
            $mail->Subject = 'Account creation';
            $verificationLink = generateVerificationLink($email, $verificationCode);
            $mail->Body = "Hi $fname $lname,\n\nThank you for signing up! Please click on the following link to confirm your email and set your password:\n\n$verificationLink\n\nSincerely,\nMan I Love Food Website Team";

            if ($mail->send()) {
                $_SESSION['signup'] = true; // Set the signup flag
                $errorMessage = "Confirmation email sent successfully!";
            } else {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
        }
        $_SESSION['last_request'] = time();
    }
}
    ?>
<section class="mt-5 pt-5">
    <div class="container">
        <?php if(!empty($errorMessage)){
        echo '<p>'.$errorMessage.'</p>';
    }
    ?>
        <form id="signup" class="contact-form" action="signup.php" method="post">
            <div class="row d-flex flex-column align-items-center">
                <div class="col-6 text-lg-center m-2">
                    <h4>Sign up</h4>
                </div>
                <div class="col-lg-5 mb-3">
                    <fieldset>
                        <input name="fname" type="text" id="fname" placeholder="First Name" required="required">
                    </fieldset>
                </div>
                <div class="col-lg-5 mb-3">
                    <fieldset>
                        <input name="lname" type="text" id="lname" placeholder="Last Name" required="">
                    </fieldset>
                </div>
                <div class="col-lg-5 mb-3">
                    <fieldset>
                        <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email Address" required="">
                    </fieldset>
                </div>
                <div class="col-lg-5 mb-3">
                    <fieldset>
                        <select name="gender" id="gender">
                            <option value="gender">Gender</option>
                            <option name="Male" id="Male">Male</option>
                            <option name="Female" id="Female">Female</option>
                            <option name="Other" id="Other">Other</option>
                        </select>
                    </fieldset>
                </div>
                <div class="col-lg-5 mb-3">
                    <div id="filterDate2">
                        <div class="input-group date" data-date-format="dd/mm/yyyy">
                            <input  name="datepicker" id="date" type="text" class="form-control" placeholder="Date of birth" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 mb-3">
                    <fieldset>
                        <input name="phone" type="text" id="phone" placeholder="Phone" required="">
                    </fieldset>
                </div>
                <div class="col-lg-5 mb-3">
                    <fieldset>
                        <button type="submit" id="form-submit" class="main-button-icon">Create an account</button>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
</section>
<script src="assets/datepicker/dist/js/datepicker.js"></script>
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
<?php require "template/footer.php"; ?>
