<?php
session_start();
require_once('config.php');

if (isset($_SESSION['USER_ID'])) {
    $user_id = $_SESSION['USER_ID'];
    // Now you can use $user_id in your code
} else {
    $_SESSION['print_message'] = "You need to Login First";
    header('Location: index.php');
    exit();
}
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
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">

    <title>Select Stocks</title>
</head>
<style>
    form {
    max-width: 600px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
}

select,
input,
textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    box-sizing: border-box;
    border: 1px solid #ccc;
    border-radius: 4px;
}

input[type="submit"] {
    background-color: #4caf50;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #45a049;
}  
.home {
    height: 100vh;
    display: flex;
    background-image: url("back.jpg");
    background-repeat: no-repeat;
    background-size: cover;
    background-size: 100% 100%;
    align-items: center;
    border-top: 1px solid;
    
}
    </style>

    </style>
</style>

<body>
    
    <?php include 'header.php' ?>
    <section class="home">
     <?php
    // Retrieve available stocks
    $query = "SELECT STOCKS_ID, MEDIUM, PRICE FROM stocks";
    $result = mysqli_query($conn, $query);
    ?>

    <form action="display_price.php" method="post" enctype="multipart/form-data">

        <label for="medium">Select Medium:</label>
        <select name="medium" id="medium" required>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['STOCKS_ID'] . "'>" . $row['MEDIUM'] . "</option>";
            }
            ?>
        </select>

        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" min="1" required>

        <label for="document">Upload Document:</label>
        <input type="file" name="document" accept=".pdf, .doc, .docx, .jpg, .png" required>

        <label for="comment">Comment:</label>
        <textarea name="comment" id="comment" rows="4" cols="50"></textarea>
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

        <input type="submit" value="Get Price">
    </form>

    <?php
    mysqli_close($conn);
    ?>   

</body>

</html>

