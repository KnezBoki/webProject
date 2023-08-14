<?php
require "template/header.php";

if (!isset($_SESSION['signup']) || !$_SESSION['signup']) {
    header("Location: signup.php");
    exit();
}
if($_SESSION['verified']){
    unset($_SESSION['verified']);
    header("Location: index.php");
    exit();
}

require 'db_config.php';

$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
$email = $_SESSION['email'];
$gender = $_SESSION['gender'];
$date = $_SESSION['date'];
$phone = $_SESSION['phone'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($password !== $confirmPassword) {
        echo "Password and confirm password do not match!";
    } else {
        try {
            $pdo = connect();

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // SQL statment
            $stmt = $pdo->prepare("INSERT INTO accounts (first_name, last_name, email, gender, date_of_birth, phone, password, created_at) 
                VALUES (:fname, :lname, :email, :gender, :date_of_birth, :phone, :password, :created_at)");
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':date_of_birth', $date);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));

?>
<section class="mt-5 p-5">
    <div class="contact-form">
        <?php
            if ($stmt->execute()) {
                unset($_SESSION['signup']);
                unset($_SESSION['fname']);
                unset($_SESSION['lname']);
                unset($_SESSION['email']);
                unset($_SESSION['gender']);
                unset($_SESSION['date']);
                unset($_SESSION['phone']);

                $_SESSION['verified'] = true;
                echo "Account created successfully!";
            } else {
                echo "Error: Unable to insert data into the database.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
        ?>
        <form id="contact" action="" method="post" class="contact-form">
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