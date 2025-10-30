<?php
require_once('config.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the verification token exists in the database
    $stmt_check_token = $conn->prepare("SELECT COUNT(*) FROM user WHERE verification_token = ?");
    $stmt_check_token->bind_param("s", $token);
    $stmt_check_token->execute();
    $stmt_check_token->bind_result($token_count);
    $stmt_check_token->fetch();
    $stmt_check_token->close();

    if ($token_count > 0) {
        // Update user's status as verified
        $stmt_verify_user = $conn->prepare("UPDATE user SET VALID = 'Y', verification_token = NULL WHERE verification_token = ?");
        $stmt_verify_user->bind_param("s", $token);
        $stmt_verify_user->execute();
        $stmt_verify_user->close();

        // Set a session variable with the success message
        session_start();
        $_SESSION['verification_message'] = "Email verification successful! You can now log in.";

        // Redirect to login page or any other desired location
        header('Location: index.php');
        exit();
    } else {
        echo "Invalid verification token.";
    }
} else {
    echo "Invalid request.";
}
?>