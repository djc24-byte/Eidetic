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
    <title>Admin - Display Printing Request</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: rgba(255, 224, 178, 0.5);
            text-align: center;
            /* Center align all content */
        }

        h2 {
            color: orange;
        }

        p {
            margin-bottom: 10px;
        }

        form {
            margin-top: 20px;
            display: inline-block;
            /* Align the form horizontally */
        }

        input[type="submit"] {
            background-color: orange;
            color: #fff;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: black;
        }

        .pdf-message {
            margin-top: 20px;
            padding: 15px;
            background-color: #dff0d8;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
            color: #3c763d;
            text-align: center;

        }

        .info-box {
            position: relative;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px auto;
            /* Center the box horizontally */
            background-color: rgba(255, 255, 255, 0.8);
            /* Set the background color with alpha for transparency */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Optional: add a subtle box shadow */
            width: 50%;
            /* Set the width to 50% of the viewport */
            overflow: hidden;
            /* Ensure that the blur effect stays within the box boundaries */
        }

        .info-box::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: inherit;
            filter: blur(5px);
            /* Adjust the blur value as needed */
            z-index: -1;
            /* Place the blurred background behind the content */
        }
    </style>

</head>

<body>
    <?php include 'admin_header.php' ?>


    <?php
    // Include the database connection file
    include('config.php'); // Replace with your actual connection file
    
    // Check if a printing request is selected
    if (isset($_POST['select_request'])) {
        $selected_request_id = $_POST['request_id'];

        // Fetch printing request details including user information
        $query = "SELECT printingorder.*, stocks.MEDIUM, status.DATE, user.Fname, user.Lname, user.EMAIL
                  FROM printingorder
                  JOIN stocks ON printingorder.STOCKS_ID = stocks.STOCKS_ID
                  JOIN status ON printingorder.REQUEST_ID = status.REQUEST_ID
                  JOIN user ON printingorder.USER_ID = user.USER_ID
                  WHERE printingorder.REQUEST_ID = $selected_request_id";

        $result = mysqli_query($conn, $query);

        if ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="info-box">';
            echo '<h2>' . 'Admin - Display Printing Request Details' . '</h2>';
            echo '<p>User: ' . $row['Fname'] . ' ' . $row['Lname'] . '</p>';
            echo '<p>Email: ' . $row['EMAIL'] . '</p>';
            echo '<p>Medium: ' . $row['MEDIUM'] . '</p>';
            echo '<p>Total Price: ₱' . $row['TOTAL_PRICE'] . '</p>';
            echo '<p>Quantity: ' . $row['QUANTITY'] . '</p>';
            echo '<p>Date: ' . $row['DATE'] . '</p>';



            // Add a button to generate PDF
            echo '<form action="generate_pdf.php" method="post">';
            echo '<input type="hidden" name="request_id" value="' . $selected_request_id . '">';
            echo '<input type="submit" name="generate_pdf" value="Generate PDF">';
            echo '</form>';
            echo '</div>';
        } else {
            echo '<p>No printing request found with the selected ID.</p>';
        }
    } else {
        echo '<p>Please select a printing request.</p>';
    }
    ?>
</body>

</html>