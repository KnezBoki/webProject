<?php
require "template/header.php";
require 'db_config.php';

$errorMessage = '';
if (!isset($_SESSION['signup']) || !$_SESSION['signup']) {
    header("Location: signup.php");
    exit();
}

if(isset($_SESSION['verified']) && $_SESSION['verified']){
    header("Location: login.php");
    exit();
}

$_SESSION['verified'] = false;


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
        $errorMessage = "Password and confirm password do not match!";
    } else {
        try {
            $conn = connect();

            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

            // SQL statment
            $stmt = $conn->prepare("INSERT INTO accounts (first_name, last_name, email, gender, date_of_birth, phone, password, created_at) 
                VALUES (:fname, :lname, :email, :gender, :date_of_birth, :phone, :password, :created_at)");
            $stmt->bindParam(':fname', $fname);
            $stmt->bindParam(':lname', $lname);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':gender', $gender);
            $stmt->bindParam(':date_of_birth', $date);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindValue(':created_at', date('Y-m-d H:i:s'));

            $stmt->execute();

            $statement = $conn->prepare("SELECT id FROM accounts WHERE email=?");
            $statement->execute([$email]);

            $resultAccountId = $statement->fetch(PDO::FETCH_ASSOC);

            $id = $resultAccountId['id'];
            $stmt = $conn->prepare("INSERT INTO user_role SET account_id=?, role_id=3");

            if ($stmt->execute([$id])) {
                unset($_SESSION['signup']);
                unset($_SESSION['fname']);
                unset($_SESSION['lname']);
                unset($_SESSION['email']);
                unset($_SESSION['gender']);
                unset($_SESSION['date']);
                unset($_SESSION['phone']);

                $_SESSION['verified'] = true;
                $errorMessage = "Account successfully verified!";
            } else {
                $_SESSION['verified'] = false;
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
                    <h4>Acount verification</h4>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-sm-6 mb-2">
                        <fieldset>
                            <input name="password" type="password" id="password" placeholder="Password" required="" onchange="checkPasswordStrength(this)">
                        </fieldset>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6 mb-2">
                        <fieldset>
                            <input name="confirm_password" type="password" id="confirm_password" placeholder="Confirm Password" required="">
                        </fieldset>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-6">
                        <fieldset>
                            <button type="submit" id="verify-account" class="btnMain">Verify account!</button>
                        </fieldset>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
    <script src="assets/js/complexity_check.js"></script>
<?php include "template/footer.php"; ?>