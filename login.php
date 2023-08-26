<?php
require "template/header.php";
require "functions.php";

require "db_config.php";

if ($_SESSION['logged_in']) {
    header("location:profile");
    exit();
}
else {
    $_SESSION['logged_in'] = false;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

        $_SESSION['email'] = $email;
        $userRole = getUserRoleFromDB($email, $password);

        if ($userRole !== null) {
            $_SESSION['userRole'] = $userRole;
            $_SESSION['logged_in'] = true;
            if ($userRole == 'admin') {
                header("Location:admin");
                exit();
            } elseif ($userRole == 'worker') {
                header("Location:tables");
                exit();
            } else {
                header("Location:profile");
                $_SESSION['email'] = $email;
                exit();
            }
        } else {
            $error_message = "Invalid credentials";
        }
    }
}
?>

<section class="login-bg pt-sm-5">
    <div class="container my-5 py-5">
        <form id="contact" class="contact-form" action="" method="post">
            <div class="row d-flex flex-column align-items-center">
                <div class="col-lg-12 mb-3">
                    <h4>Login</h4>
                </div>
                <div class="col-lg-6 mb-3">
                    <fieldset>
                        <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email Address" required="">
                    </fieldset>
                </div>
                <div class="col-lg-6 mb-3">
                    <fieldset>
                        <input name="password" type="password" id="password" placeholder="Password" required="">
                    </fieldset>
                </div>
                <div class="col-lg-12 mb-3 text-md-center">
                    <fieldset>
                        <a href="forgot"><h6>Forgot your password? Click here!</h6></a>
                    </fieldset>
                </div>
                <div class="col-lg-6">
                    <fieldset>
                        <button type="submit" id="login" class="main-button-icon">Login</button>
                    </fieldset>
                </div>
            </div>
            <?php if (isset($error_message)) {
                echo '<div class="bg-light"><p class="text-center mt-3 text-danger">'.$error_message.'</p></div>';
            }
            ?>
        </form>
    </div>
</section>
<?php require "template/footer.php"; ?>
