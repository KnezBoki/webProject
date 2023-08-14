<?php
require "template/header.php";
require "functions.php";

if (isset($_SESSION['logged_in'])) {
    header("location: index.php");
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;

require_once "db_config.php";
require_once "PHPMailer-6.8.0/vendor/autoload.php";

// Function to generate a random verification code
function generateVerificationCode($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

// Function to generate a verification link
function generateVerificationLink($email, $verificationCode) {
    return "https://localhost:63342/WebProject/verification.php?email=" . urlencode($email) . "&code=" . urlencode($verificationCode);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //Filtered inputs
    $fname = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $gender = $_POST['gender'];
    $rawDate = $_POST['datepicker'];
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);



$conn = connect();

    $errorMessage = NULL;

    $stmt = $conn->prepare("SELECT email FROM accounts WHERE email=?");
    $stmt -> execute([$email]);
    $result = $stmt->fetch();
    if($result != NULL){$errorMessage = "There is already an account registered under that email!";}
    else {
        $date = formatDate($rawDate);

        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['email'] = $email;
        $_SESSION['gender'] = $gender;
        $_SESSION['date'] = $date;
        $_SESSION['phone'] = $phone;

        $verificationCode = generateVerificationCode();

        $_SESSION['verificationCode'] = $verificationCode;

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

?>
<section class="my-5 py-5">
    <?php
        if ($mail->send()) {
            $_SESSION['signup'] = true; // Set the signup flag
            echo '<p>Confirmation email sent successfully!</p>';
        } else {
            echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
    }
    if(!empty($errorMessage)){
        echo '<p>'.$errorMessage.'</p>';
    }
}
    ?>
    <div class="container mt-5 p-5">
        <form id="signup" class="contact-form" action="signup.php" method="post">
            <div class="row d-flex justify-content-center">
                <div class="col-6 mr-1 text-lg-center m-2">
                    <h4>Sign up</h4>
                </div>
                <div class="col-lg-6 mr-1">
                    <fieldset>
                        <input name="fname" type="text" id="fname" placeholder="First Name" required="required">
                    </fieldset>
                </div>
                <div class="col-lg-6 mr-1">
                    <fieldset>
                        <input name="lname" type="text" id="lname" placeholder="Last Name" required="">
                    </fieldset>
                </div>
                <div class="col-lg-6 mr-1">
                    <fieldset>
                        <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email Address" required="">
                    </fieldset>
                </div>
                <div class="col-md-6 mr-1">
                    <fieldset>
                        <select value="gender" name="gender" id="gender">
                            <option value="gender">Gender</option>
                            <option name="Male" id="Male">Male</option>
                            <option name="Female" id="Female">Female</option>
                            <option name="Other" id="Other">Other</option>
                        </select>
                    </fieldset>
                </div>
                <div class="col-lg-6 mr-1 mb-3">
                    <div id="filterDate2">
                        <div class="input-group date" data-date-format="dd/mm/yyyy">
                            <input  name="datepicker" id="date" type="text" class="form-control" placeholder="Date of birth" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 mr-1">
                    <fieldset>
                        <input name="phone" type="text" id="phone" placeholder="Phone" required="">
                    </fieldset>
                </div>
                <div class="col-lg-12">
                </div>
                <div class="col-lg-6">
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
        format: 'dd/mm/yyyy',
        showOnFocus:false,
        dateDelimiter: '/',
        weekStart: 1,
        updateOnBlur: false,
    });
</script>
<?php require "template/footer.php"; ?>
