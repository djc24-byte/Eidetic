<?php
// ... (previous code)
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Select Printing Request</title>
    <link rel="stylesheet" href="style.css">
    <style>
        form {
            max-width: 400px;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: white;

        }

        /* Style for labels */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        h2 {
            text-align: center;
            margin-top: 10%;
        }

        /* Style for the select dropdown */
        select {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 3px;
            box-sizing: border-box;
        }

        /* Style for the submit button */
        input[type="submit"] {
            background-color: orange;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: black;
        }
    </style>
</head>


<body style=" background-color: rgba(255, 224, 178, 0.5);">
    <?php include 'admin_header.php' ?>


    <h2>Admin - Select Printing Request</h2>
    <?php
    // Include the database connection file
    include('config.php'); // Replace with your actual connection file
    
    // Fetch and display a list of printing requests for the admin to choose from
    $query_all_requests = "SELECT REQUEST_ID FROM status";
    $result_all_requests = mysqli_query($conn, $query_all_requests);

    echo '<form method="post" action="admin_display_request.php">';
    echo '<label for="request_id">Select Printing Request:</label>';
    echo '<select name="request_id">';
    while ($row_request = mysqli_fetch_assoc($result_all_requests)) {
        echo '<option value="' . $row_request['REQUEST_ID'] . '">' . $row_request['REQUEST_ID'] . '</option>';
    }
    echo '</select>';
    echo '<input type="submit" name="select_request" value="Select">';
    echo '</form>';
    ?>
</body>

</html>