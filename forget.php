<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('config.php');
require 'vendor/autoload.php'; // Include the PHPMailer autoload file

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['forgot_password'])) {
    $Email = $_POST['Email'];

    // Check if the email exists in the database
    $stmt_check_email = $conn->prepare("SELECT COUNT(*) FROM user WHERE EMAIL = ?");
    $stmt_check_email->bind_param("s", $Email);
    $stmt_check_email->execute();
    $stmt_check_email->bind_result($email_count);
    $stmt_check_email->fetch();
    $stmt_check_email->close();

    if ($email_count > 0) {
        // Email exists, generate a reset token
        $reset_token = bin2hex(random_bytes(32));

        // Save the reset token and its expiration time in the database
        $reset_token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt_save_token = $conn->prepare("UPDATE user SET reset_token = ?, reset_token_expiry = ? WHERE EMAIL = ?");
        $stmt_save_token->bind_param("sss", $reset_token, $reset_token_expiry, $Email);
        $stmt_save_token->execute();
        $stmt_save_token->close();

        // Send the password reset email
        sendPasswordResetEmail($Email, $reset_token);

        // Inform the user
        $_SESSION['reset_message'] = "An email with instructions to reset your password has been sent to $Email.";
    } else {
        // Email does not exist in the database
        $_SESSION['reset_message'] = "Email not found. Please check your email and try again.";
    }

    // Redirect to index.php
    header('Location: index.php');
    exit();
}

function sendPasswordResetEmail($to, $token)
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
        $mail->Subject = 'Password Reset';
        $mail->Body = 'Click the following link to reset your password: <a href="http://localhost/eideticv/reset_password.php?token=' . $token . '">Reset Password</a>';

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
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="forgot-container">
        <h2>Forgot Password</h2>
        <form action="forget.php" method="post">
            <label for="Email">Email:</label>
            <input type="email" id="Email" name="Email" required>

            <button type="submit" name="forgot_password">Reset Password</button>
        </form>
    </div>
</body>

</html>