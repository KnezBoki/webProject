<?php
session_start();
$_SESSION['signup'] = 'signup';

use PHPMailer\PHPMailer\PHPMailer;

require_once "PHPMailer-6.8.0/vendor/autoload.php";
require 'PHPMailer-6.8.0/src/PHPMailer.php';

// Generates a random verification code
function generateVerificationCode($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $code = '';
    for ($i = 0; $i < $length; $i++) {
        $code .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $code;
}

// Generates verification link
function generateVerificationLink($email, $verificationCode) {
    require "db_config.php";

    $expiryTime = time() + 900; // 15 min
    $sql = "INSERT INTO verification_codes (email, code, expiry_time) VALUES ('$email', '$verificationCode', $expiryTime)";

    $_SESSION['expieryTime'] = $expiryTime;

    return "https://localhost:63342/WebProject/verification.php?email=" . urlencode($email) . "&code=" . urlencode($verificationCode);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $gender = $_POST['gender'];
    $rawDate = $_POST['date'];
    $phone = $_POST['phone'];

    $date = date("Y-m-d", strtotime($rawDate));

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
    $mail->setFrom('noreply@example.com', 'Your Website');
    $mail->addAddress($email, $fname . ' ' . $lname);

    // Email subject and body with verification link
    $mail->Subject = 'Email Confirmation';
    $verificationLink = generateVerificationLink($email, $verificationCode);
    $mail->Body = "Hi $fname $lname,\n\nThank you for signing up! Please click on the following link to confirm your email and set your password:\n\n$verificationLink\n\nSincerely,\nMan I Love Food Website Team";
    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        echo 'A verification code has been sent to your email. Please check your inbox and enter the code below to continue with the sign-up process.';
    }
}

include "template/header.php";
?>
<section class="my-5 py-5">
    <div class="container p-5">
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
                            <input  name="date" id="date" type="text" class="form-control" placeholder="Date of birth">
                            <div class="input-group-addon" >
                                <span class="glyphicon glyphicon-th"></span>
                            </div>
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
<?php include "template/footer.php"; ?>
