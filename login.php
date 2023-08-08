<?php
include "auth.php";

include "template/header.php";
?>

<section class="login-bg pt-5">
    <div class="container my-5 py-5">
        <form id="contact" class="contact-form" action="profile.php" method="post">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12">
                    <h4>Login</h4>
                </div>
                <div class="col-lg-6 mr-1">
                    <fieldset>
                        <input name="email" type="text" id="email" pattern="[^ @]*@[^ @]*" placeholder="Your Email Address" required="">
                    </fieldset>
                </div>
                <div class="col-lg-6 mr-1">
                    <fieldset>
                        <input name="password" type="password" id="password" placeholder="Password" required="">
                    </fieldset>
                </div>
                <div class="col-lg-6 mr-1 mb-4">
                    <fieldset>
                        <a href="signup.php" class="text-md-center"><h6>Don't have an account? Click here!</h6></a>
                    </fieldset>
                </div>
                <div class="col-lg-6 mr-1">
                    <fieldset>
                        <button type="submit" id="login" class="main-button-icon">Login</button>
                    </fieldset>
                </div>
            </div>
        </form>
        <div>
            <?php if (isset($error_message)): ?>
                <p class="text-center mt-3 text-danger"><?php echo $error_message; ?></p>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php include "template/footer.php"; ?>
