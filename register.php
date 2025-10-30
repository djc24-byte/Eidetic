<?php
require_once('config.php');
require 'vendor/autoload.php'; // Include the PHPMailer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['register'])) {
    // Handle registration logic
    $password = $_POST['password'];
    $Lname = $_POST['Lname'];
    $Mname = $_POST['Mname'];
    $Fname = $_POST['Fname'];
    $Email = $_POST['Email'];
    $number = $_POST['number'];

    // Check if the email already exists
    $stmt_check_email = $conn->prepare("SELECT COUNT(*) FROM user WHERE EMAIL = ?");
    $stmt_check_email->bind_param("s", $Email);
    $stmt_check_email->execute();
    $stmt_check_email->bind_result($email_count);
    $stmt_check_email->fetch();
    $stmt_check_email->close();

    if ($email_count > 0) {
        // Email already exists, set a session variable with the error message
        session_start();
        $_SESSION['registration_error'] = "Email already exists. Please choose a different email.";

        // Redirect back to index.php or the registration page
        header('Location: index.php');
        exit();
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Email verification token
    $verification_token = bin2hex(random_bytes(32));

    // Insert user into the database with the verification token
    $stmt_insert_user = $conn->prepare("INSERT INTO user (USER_TYPE, Lname, Mname, Fname, EMAIL, PASS, NUM, VALID, verification_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $user_type = 'u'; // Assuming 'u' is for regular users
    $valid_status = 'N'; // Initial status is not validated
    $stmt_insert_user->bind_param("sssssssss", $user_type, $Lname, $Mname, $Fname, $Email, $hashed_password, $number, $valid_status, $verification_token);
    $stmt_insert_user->execute();
    $stmt_insert_user->close();

    // Send verification email
    sendVerificationEmail($Email, $verification_token);

    // Set a session variable with the success message
    session_start();
    $_SESSION['registration_message'] = "Registration successful! Please check your email to verify your account.";

    // Redirect to verify.php after successful registration
    header('Location: index.php');
    exit();
}

function sendVerificationEmail($to, $token)
{
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'natashamyoui@gmail.com';                     //SMTP username
        $mail->Password = 'pwxp spin umgt piyq';                               //SMTP password
        $mail->SMTPSecure = 'ssl';            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('natashamyoui@gmail.com', 'Mina');

        // Recipient
        $mail->addAddress($to);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Email Verification';
        $mail->Body = 'Click the following link to verify your email: <a href="http://localhost/eideticv/verify.php?token=' . $token . '">Verify</a>';

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    .register-container {
        background-color: #fff;
        max-width: 500px;
        margin: 50px auto;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .register-container h2 {
        text-align: center;
        color: #333;
    }

    .register-container label {
        display: block;
        margin: 10px 0;
    }

    .register-container input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .register-container button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
    }

    .Forget {
        display: block;
        color: #555;
        text-decoration: none;
        margin-bottom: 10px;
    }

    .create-account-btn {
        background-color: #3498db;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        width: 100%;
        margin-top: 25px;
    }
</style>

<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="index.php" method="post">
            <label for="Lname">Last Name:</label>
            <input type="text" id="Lname" name="Lname" required>
            <label for="Mname">Middle Name:</label>
            <input type="text" id="Mname" name="Mname" required>
            <label for="Fname">First Name:</label>
            <input type="text" id="Fname" name="Fname" required>
            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <label for="number">Phone Number:</label>
            <input type="text" id="number" name="number" pattern="09\d{9}" placeholder="Enter your phone number"
                required>

            <button type="submit" name="register">Register</button>
        </form>
    </div>
</body>

</html>