<?php
require_once('config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Retrieve user info based on the reset token
    $stmt = $conn->prepare("SELECT EMAIL, reset_token_expiry FROM user WHERE reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($email, $reset_expiration);
    $stmt->fetch();
    $stmt->close();

    // Check if the token is valid and not expired
    if ($email && strtotime($reset_expiration) > time()) {
        // Handle the password reset form submission
        if (isset($_POST['reset'])) {
            $new_password = $_POST['new_password'];

            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the user's password and invalidate the reset token
            $stmt_update_password = $conn->prepare("UPDATE user SET PASS = ?, reset_token = NULL WHERE EMAIL = ?");
            $stmt_update_password->bind_param("ss", $hashed_password, $email);
            $stmt_update_password->execute();
            $stmt_update_password->close();

            echo "Password reset successful! You can now <a href='index.php'>login</a>.";
            exit();
        }

        // Display the password reset form
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Reset</title>
            <link rel="stylesheet" href="style.css">
        </head>

        <body>
            <div class="reset-password-container">
                <h2>Reset Password</h2>
                <form action="reset_password.php?token=<?php echo $token; ?>" method="post">
                    <label for="new_password">New Password:</label>
                    <input type="password" id="new_password" name="new_password" required>

                    <button type="submit" name="reset">Reset Password</button>
                </form>
            </div>
        </body>

        </html>
        <?php
    } else {
        echo "Invalid or expired reset token.";
    }
} else {
    echo "Invalid request.";
}
?>