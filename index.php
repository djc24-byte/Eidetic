<?php
session_start();
require_once('config.php');
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
<style>
    a {
        text-decoration: none;
        /* Optional: Use the default text color for links */
    }
</style>

<body>
    <?php include 'header.php' ?>


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
            <p>Your trusted digital printing service provider in Obando, Bulacan</p>
            <p>Printing your requirements is now easier than ever.</p>
            <a href="#print" class="btn">GET STARTED</a>
        </div>
    </section>

    <!-- Works -->

    <section class="works">
        <h1 style="font-size: 50px;">Here's how it works:</h1>
        <br>

        <p><b>Step 1:</b> Select a size<br><br>

            <b>Step 2:</b> Upload your file<br><br>

            <b>Step 3:</b> Place your order and wait for your document to print
        </p>

    </section>

    <!-- about us -->

    <section id="service" class="service">
        <h1>OUR PRODUCTS</h1>



        <div class="box-container">
            <?php
            // Assuming you have a MySQLi connection established earlier (replace $mysqli with your actual connection variable)
            $result = $conn->query("SELECT * FROM `stocks`");

            if ($result->num_rows > 0) {
                while ($fetch_products = $result->fetch_assoc()) {
                    ?>
                    <div class="box">
                        <div class="price">QTY
                            <?= $fetch_products['QUANTITY'] ?>
                        </div>
                        <img style="width: 100px; height:100px; " src="uploaded_img/<?= $fetch_products['IMAGE'] ?>" alt="">
                        <div class="name">
                            <?= $fetch_products['MEDIUM'] ?>
                        </div>
                        <a href="print.php" class="print">Get Started</a>
                    </div>
                    <?php
                }
            } else {
                echo "No products found.";
            }
            ?>
        </div>
    </section>

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
                <a href=""><img src=" facebook.png"></a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>2023 Eidetic. All rights reserved. | Designed by INC</p>
        </div>
    </section>



</body>
<script src="script.js"></script>

</html>