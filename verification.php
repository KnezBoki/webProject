<?php
session_start();
if (!isset($_SESSION['signup'])) {
    header("Location: signup.php");
    exit();
}

require_once 'db_config.php';

$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
$email = $_SESSION['email'];
$gender = $_SESSION['gender'];
$date = $_SESSION['date'];
$phone = $_SESSION['phone'];
$verificationCode = $_SESSION['verificationCode'];
$expiryTime = $_SESSION['expiryTime'];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
$password = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];

// Check if the password and confirm password match
if ($password !== $confirmPassword) {
    echo "Password and confirm password do not match!";
} else {
    try {
        $pdo = connect();

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO accounts (first_name, last_name, email, gender, date_of_birth, phone, password, verification_code, verification_expiry) VALUES (:fname, :lname, :email, :gender, :date, :phone, :password, :verificationCode, :expiryTime)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters and execute the statement
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':verificationCode', $verificationCode);
        $stmt->bindParam('expiryTime', $expiryTime);
        $stmt->execute();

        echo "Account created successfully!";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
}
include "template/header.php";
?>
<section class="p-5">
    <div class="contact-form p-3">
        <form id="contact" action="profile.php" method="post">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12">
                    <h4>Sign up</h4>
                </div>
                <div class="col-lg-6 mr-1">
                    <fieldset>
                        <input name="password" type="password" id="password" placeholder="Password" required="">
                    </fieldset>
                </div>
                <div class="col-lg-6 mr-1">
                    <fieldset>
                        <input name="confirm_password" type="password" id="confirm_password" placeholder="Confirm Password" required="">
                    </fieldset>
                </div>
                <div class="col-lg-12">
                </div>
                <div class="col-lg-6">
                    <fieldset>
                        <button type="submit" id="verify-account" class="main-button-icon">Verify account</button>
                    </fieldset>
                </div>
            </div>
        </form>
    </div>
</section>
<?php include "template/footer.php"; ?>