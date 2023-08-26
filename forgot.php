<?php
require "template/header.php";
require 'db_config.php';
require 'functions.php';

use PHPMailer\PHPMailer\PHPMailer;

require "PHPMailer-6.8.0/vendor/autoload.php";

$errorMessage = '';
$timeout = 60; //1 minute

$conn = connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recoveryEmail'])) {
    $recoveryEmail = filter_input(INPUT_POST, 'recoveryEmail', FILTER_SANITIZE_EMAIL);

    $address = getIPAddress();

    // Check if the email is in timeout
    $lastRequest = isset($_SESSION['last_request']) ? $_SESSION['last_request'] : 0;
    if (time() - $lastRequest < $timeout) {
        $errorMessage = "Please wait before making another request.";
    } else {
        $_SESSION['last_request'] = time();
        $stmt = $conn->prepare("INSERT INTO email_requests (email, last_request) VALUES (?, NOW()) ON DUPLICATE KEY UPDATE last_request = NOW()");
        $stmt->execute([$recoveryEmail]);

        try {
            $stmt = $conn->prepare("SELECT email FROM accounts");
            $stmt->execute();
            $emails = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $errorMessage = "If the email you've entered is associated with an account you will receive an email with a password reset link.";
            foreach ($emails as $email) {
                if ($recoveryEmail === $email['email']) {
                    $_SESSION['email'] = $recoveryEmail;
                    $stmt = $conn->prepare("SELECT first_name, last_name FROM accounts WHERE email=?");
                    $stmt->execute([$recoveryEmail]);
                    $result = $stmt->fetch();

                    $fname = $result['first_name'];
                    $lname = $result['last_name'];

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
                    $mail->addAddress($recoveryEmail, $fname . ' ' . $lname);

                    $resetCode = generateVerificationCode();
                    $resetLink = "https://milfirefox.stud.vts.su.ac.rs/reset.php?email=" . urlencode($recoveryEmail) . "&code=" . urlencode($resetCode);
                    $emailSubject = "MIL Food Account Recovery";
                    $emailBody = "Hi $fname $lname,\n\nA password reset has been requested for your account from this address:\n\n$address\n\n If it wasn't you, don't worry someone probably entered the wrong email. Otherwise, here is the password reset link:\n\n$resetLink\n\nSincerely,\nMan I Love Food Website Team";

                    // Send the email
                    try {
                        $mail->Subject = $emailSubject;
                        $mail->Body = $emailBody;
                        $mail->send();
                    } catch (Exception $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    break;
                }
            }
        } catch (PDOException $e) {
            $errorMessage = "Error: " . $e->getMessage();
        }
    }
}
?>
    <section class="mt-sm-5 p-sm-5">
        <div class="contact-form">
            <p><?php echo $errorMessage?></p>
            <form id="contact" method="post" class="contact-form">
                <div class="row">
                    <div class="col-lg-12">
                        <h4>Password reset</h4>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-sm-6 mb-2">
                            <fieldset>
                                <input name="recoveryEmail" type="email" id="recoveryEmail" placeholder="Email" required="" ">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-6">
                            <fieldset>
                                <button type="submit" id="reset-password" class="btnMain">Reset password!</button>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
<?php include "template/footer.php"; ?>