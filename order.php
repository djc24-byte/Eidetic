<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['USER_ID'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit();
}

// Include your database connection file (replace with your actual connection code)
include("config.php");

// Fetch user information
$user_id = $_SESSION['USER_ID'];
$user_query = "SELECT * FROM user WHERE USER_ID = $user_id";
$user_result = mysqli_query($conn, $user_query);
$user = mysqli_fetch_assoc($user_result);

// Fetch user orders with medium information
$order_query = "SELECT po.*, s.MEDIUM, st.STATUS, st.DATE
                FROM printingorder po
                JOIN stocks s ON po.STOCKS_ID = s.STOCKS_ID
                JOIN status st ON po.REQUEST_ID = st.REQUEST_ID
                WHERE po.USER_ID = $user_id";

$order_result = mysqli_query($conn, $order_query);

// Display user information and orders
?>
<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="style.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Orders</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url("back.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            max-width: 100%;
            height: 100vh;
            /* Center the content horizontally */
            backdrop-filter: blur(10px);
            /* Adjust the blur radius as needed */
        }


        h1 {
            text-align: center;
            margin-top: 20px;
        }

        h2 {
            text-align: center;
        }

        table {
            width: 60%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            background-color: transparent;
            backdrop-filter: blur(10px);
            /* Adjust the blur radius as needed */
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: left;
        }

        th {
            background-color: Orange;
            color: white;
        }

        img {
            max-width: 100px;
            max-height: 100px;
        }

        .content-box {
            backdrop-filter: blur(10px);
            /* Adjust the blur radius as needed */
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.5);
            /* Adjust the alpha value for transparency */
        }
    </style>
</head>

<body>
    <?php include 'header.php' ?>
    <div class="content-box">
        <h1>Your Orders</h1>
        <h2>Welcome,
            <?php echo $user['Fname']; ?>
        </h2>

        <table>
            <tr>
                <th>Order ID</th>
                <th>Image</th>
                <th>Medium</th>
                <th>Quantity</th>
                <th>Comment</th>
                <th>Payment Status</th>
                <th>Date Requested</th>
                <th>Status</th>
            </tr>

            <?php
            // Display user orders in the table
            while ($order = mysqli_fetch_assoc($order_result)) {
                echo "<tr>";
                echo "<td>" . $order['REQUEST_ID'] . "</td>";
                echo "<td><img src='uploaded_img/" . $order['DOCUMENT'] . "' alt='Order Image'></td>";
                echo "<td>" . $order['MEDIUM'] . "</td>";
                echo "<td>" . $order['QUANTITY'] . "</td>";
                echo "<td>" . $order['COMMENT'] . "</td>";

                // Display payment status
                echo "<td>";
                if ($order['P_STATUS'] === 'N') {
                    echo "Not Paid";
                } else {
                    echo "Paid";
                }
                echo "</td>";

                // Display date and status from the status table
                echo "<td>" . $order['DATE'] . "</td>";
                echo "<td>" . $order['STATUS'] . "</td>";

                echo "</tr>";
            }
            ?>
        </table>
    </div>
</body>

</html>