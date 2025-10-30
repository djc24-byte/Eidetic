<?php
session_start();
require_once('config.php');
if (!isset($_SESSION['USER_ID'])) {
    // Redirect to login page if not logged in
    header('Location: index.php');
    exit();
}

// Check if the user is an admin
if ($_SESSION['USER_TYPE'] !== 'a') {
    // Redirect to login page if not an admin
    header('Location: index.php');
    exit();
}

//echo $_SESSION['USER_TYPE'];
// Check if there's a registration message in the session
if (isset($_SESSION['registration_message'])) {
    echo '<div class="registration-message">' . $_SESSION['registration_message'] . '</div>';

    // Clear the session variable to avoid displaying the message on subsequent visits
    unset($_SESSION['registration_message']);
}

if (isset($_SESSION['registration_error'])) {
    echo '<div class="error-message">' . $_SESSION['registration_error'] . '</div>';
    unset($_SESSION['registration_error']); // Clear the session variable after displaying
}

if (isset($_SESSION['login_message'])) {
    echo '<p class="message">' . $_SESSION['login_message'] . '</p>';
    unset($_SESSION['login_message']); // Clear the session variable
}

if (isset($_SESSION['reset_message'])) {
    echo '<p class="reset_message">' . $_SESSION['reset_message'] . '</p>';
    unset($_SESSION['reset_message']); // Clear the session variable
}

if (isset($_SESSION['print_message'])) {
    echo '<p class="print_message">' . $_SESSION['print_message'] . '</p>';
    unset($_SESSION['print_message']); // Clear the session variable
}
//echo $_SESSION['USER_ID'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="designn.css">
    <title>Eidetic</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php include 'admin_header.php' ?>


    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeLoginModal()">&times;</span>
            <?php include('login.php'); ?>
        </div>
    </div>

    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRegisterModal()">&times;</span>
            <?php include('register.php'); ?>
        </div>
    </div>

    <!-- Home Upper -->

    <section class="home">
        <div class="content">
            <h1>WELCOME TO EIDETIC!</h1>
            <h2>Admin Page</h2>
            <p>Your trusted digital printing service provider in Obando, Bulacan</p>
            <p>Printing your requirements is now easier than ever.</p>
        </div>
    </section>

    <!-- Works -->



    <!-- about us -->

    <section class="foot">

        <div class="footer-content">
            <div class="footer-info">
                <h3>Contact Us</h3>
                <p>Email: info@eidetic.com</p>
                <p>Phone: +1 (123) 456-7890</p>
                <p>Address: 123 Main Street, Obando, Bulacan</p>
            </div>
            <div class="footer-social">
                <h3>FOLLOW US:</h3>
                <ul>
                    <li><a href=""><img src="facebook.png"></li></a>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>2023 Eidetic. All rights reserved. | Designed by INC</p>
        </div>
    </section>



</body>
<script src="script.js"></script>

</html>