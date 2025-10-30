<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('config.php');

if (isset($_POST['login'])) {
    // Handle login logic
    $Email = $_POST['Email'];
    $password = $_POST['password'];

    // Retrieve hashed password, USER_ID, user_type, and validation status from the database
    $stmt = $conn->prepare("SELECT USER_ID, PASS, USER_TYPE, VALID FROM user WHERE EMAIL = ?");
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $stmt->bind_result($user_id, $hashed_password, $user_type, $valid_status);
    $stmt->fetch();
    $stmt->close();

    // Verify the password and check the user's validation status
    // Verify the password and check the user's validation status
    if (password_verify($password, $hashed_password)) {
        if ($valid_status == 'Y') {
            // Password is correct and user is validated

            // Set USER_ID and Email in the session
            $_SESSION['USER_ID'] = $user_id;
            $_SESSION['Email'] = $Email;
            $_SESSION['USER_TYPE'] = $user_type;

            // Redirect based on user_type
            if ($user_type == 'u') {
                header('Location: index.php'); // User page
            } elseif ($user_type == 'a') {
                header('Location: admin_index.php'); // Admin page
            } elseif ($user_type == 's') {
                header('Location: staff_index.php'); // Staff page
            } else {
                header('Location: index.php'); // Default to index.php
            }
            exit();
        } else {
            // User is not validated (not verified)
            $_SESSION['login_message'] = "Your account is not verified. Please check your email for verification instructions.";
        }
    } else {
        // Password is incorrect
        $_SESSION['login_message'] = "Invalid email or password";
    }

    // Redirect to login.php with error message
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<!-- Rest of your HTML code remains unchanged -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #C8A126;
        }

        .login-container {
            background-color: #fff;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            text-align: center;
            color: #333;
        }

        .login-container label {
            display: block;
            margin: 10px 0;
        }

        .login-container input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .login-container button {
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
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>

        <!-- Display error message if exists -->
        <?php if (isset($_SESSION['login_message'])): ?>
            <div class="error-message">
                <?php echo $_SESSION['login_message']; ?>
            </div>
            <?php unset($_SESSION['login_message']); ?>
        <?php endif; ?>

        <form action="index.php" method="post">
            <label for="Email">Email:</label>
            <input type="text" id="Email" name="Email" placeholder="Enter your email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
            <a href="Forget.php" class="Forget">Forget Password?</a>
            <button type="submit" name="login">Login</button>
        </form>



        <button class="create-account-btn" onclick="openRegisterModal()">Create Account</button>
    </div>
</body>

</html>