<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['Email'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include your database connection here
include("config.php");

// Fetch user details from the database
$user_id = $_SESSION['USER_ID']; // Assuming you store user ID in the session
$query = "SELECT * FROM user WHERE USER_ID = $user_id";
// Execute query and fetch user data
// Example using MySQLi:
$result = mysqli_query($conn, $query);
$user_data = mysqli_fetch_assoc($result);



// Update user information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission

    // Example using MySQLi (replace with your database connection):
    $user_id = $_SESSION['USER_ID'];

    $new_email = isset($_POST['new_email']) ? trim($_POST['new_email']) : null;
    $new_num = isset($_POST['new_num']) ? trim($_POST['new_num']) : null;
    $new_password = isset($_POST['new_password']) ? trim($_POST['new_password']) : null;

    // Ensure values are not empty before updating
    if (!empty($new_email)) {
        $update_email_query = "UPDATE user SET EMAIL = '$new_email' WHERE USER_ID = $user_id";
        mysqli_query($conn, $update_email_query);
    }

    if (!empty($new_num)) {
        $update_num_query = "UPDATE user SET NUM = '$new_num' WHERE USER_ID = $user_id";
        mysqli_query($conn, $update_num_query);
    }

    if (!empty($new_password)) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update_password_query = "UPDATE user SET PASS = '$hashed_password' WHERE USER_ID = $user_id";
        mysqli_query($conn, $update_password_query);
    }

    // You may want to handle success/failure and provide feedback to the user
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>

<body>
    <h1>Welcome, <?php echo $user_data['Fname'] . ' ' . $user_data['Mname'] . ' ' . $user_data['Lname']; ?></h1>

    <h2>Your Profile</h2>
    <p>Email: <?php echo $user_data['EMAIL']; ?></p>
    <p>Phone Number: <?php echo $user_data['NUM']; ?></p>

<!-- Your HTML and session checking code above -->

<h2>Update Information</h2>
<form method="post" action="">
    <label for="new_email">New Email:</label>
    <input type="email" name="new_email" placeholder="New Email">

    <label for="new_num">New Phone Number:</label>
    <input type="text" name="new_num" placeholder="New Phone Number">

    <label for="new_password">New Password:</label>
    <input type="password" name="new_password" placeholder="New Password">

    <input type="submit" value="Update">
</form>


</body>

</html>
