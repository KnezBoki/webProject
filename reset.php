<?php
require "template/header.php";
require "db_config.php";

$errorMessage = '';
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
    $conn = connect();
    $email = $_SESSION['email'];

    $stmt = $conn->prepare("SELECT password FROM accounts WHERE email=?");
    $stmt->execute([$email]);

    $oldHashedPassword = $stmt->fetch(PDO::FETCH_ASSOC);

    $oldHashedPassword = $oldHashedPassword['password'];
    $newPassword = $_POST['password'];
    $newHashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    if (password_verify($newPassword, $oldHashedPassword)) {
        $errorMessage = "Your new password can't be the same as the old one!";
    } else {
        $stmt = $conn->prepare("UPDATE accounts SET password=? WHERE email=?");
        $stmt->execute([$newHashedPassword, $email]);
        header("location: login.php");
        exit();
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
                                <input name="password" type="password" id="password" placeholder="New password" required="" onchange="checkPasswordStrength(this)">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-6 mb-2">
                            <fieldset>
                                <input name="confirm_password" type="password" id="confirm_password" placeholder="Confirm new password" required="">
                            </fieldset>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-6">
                            <fieldset>
                                <button type="submit" id="verify-account" class="btnMain">Reset password!</button>
                            </fieldset>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script src="assets/js/complexity_check.js"></script>
<?php
require "template/footer.php";
