<?php
session_start();

$userRole = "";

if (isset($_SESSION['logged_in'])){
    $userRole = $_SESSION['userRole'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Table Reservation Website">
    <meta name="author" content="Bojan Knežević">
    <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lightbox2/dist/css/lightbox.min.css">

    <title>Man I Love Food - Web Site</title>

    <!-- Jquerry Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.0/jquery.min.js" integrity="sha512-qp27nuUylUgwBZJHsmm3W7klwuM5gke4prTvPok3X5zi50y3Mo8cgpeXegWWrdfuXyF2UdLWK/WCb5Mv7CKHcA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js" integrity="sha512-lzilC+JFd6YV8+vQRNRtU7DOqv5Sa9Ek53lXt/k91HZTJpytHS1L6l1mMKR9K6VVoDt4LiEXaa6XBrYk1YhGTQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="https://code.jquery.com/jquery-migrate-3.4.0.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css">

    <link rel="stylesheet" type="text/css" href="assets/css/font-awesome.css">

    <link rel="stylesheet" href="assets/css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" integrity="sha512-UTNP5BXLIptsaj5WdKFrkFov94lDx+eBvbKyoe1YAfjeRPC+gT5kyZ10kOHCfNZqEui1sxmqvodNUx3KbuYI/A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="assets/css/lightbox.css">

    <link href="assets/datepicker/dist/css/datepicker.min.css" rel="stylesheet" />

    <link href="assets/datepicker/dist/css/datepicker-bs4.min.css" rel="stylesheet" />

</head>
<body>
<!-- ***** Preloader Start ***** -->
<div id="preloader">
    <div class="jumper">
        <div></div>
        <div></div>
        <div></div>
    </div>
</div>
<!-- ***** Preloader End ***** -->

<header class="header-area header-sticky">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <nav class="main-nav">
                    <!-- ***** Logo Start ***** -->
                    <a href="index.php" class="logo">
                        <img src="assets/images/logo.png">
                    </a>
                    <!-- ***** Logo End ***** -->
                    <!-- ***** Menu Start ***** -->
                    <ul class="nav">
                        <li class="scroll-to-section"><a href="index.php">Home</a></li>
                        <li class="scroll-to-section"><a href="about.php">About</a></li>
                        <li class="scroll-to-section"><a href="contact.php">Contact Us</a></li>
                        <?php
                        //Menu item based on role
                        if ($userRole == 'admin') {
                            echo '<li class="scroll-to-section"><a href="admin.php">Admin panel</a></li>';
                        } elseif ($userRole == 'worker') {
                            echo '<li class="scroll-to-section"><a href="tables.php">Dashboard</a></li>';
                        } elseif($userRole == 'user') {
                            echo '<li class="scroll-to-section"><a href="profile.php">Profile</a></li>';
                            echo '<li class="scroll-to-section"><a href="reservation.php">Reservation</a></li>';
                        }
                        else{
                            echo '<li class="scroll-to-section"><a href="signup.php">Sign up</a></li>';
                        }

                        // login/logout
                        if ($userRole == 'admin' || $userRole == 'worker' || $userRole == 'user') {
                            echo '<li class="scroll-to-section"><a href="logout.php">Log out</a></li>';
                        } else {
                            echo '<li class="scroll-to-section"><a href="login.php">Log in</a></li>';
                        }
                        ?>
                    </ul>
                    <a class='menu-trigger'>
                        <span>Menu</span>
                    </a>
                    <!-- ***** Menu End ***** -->
                </nav>
            </div>
        </div>
    </div>
</header>