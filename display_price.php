<?php
session_start();
require_once('config.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
            text-align: center;
        }

        p {
            margin-bottom: 20px;
            color: #555;
        }

        .back-button {
            display: inline-block;
            background-color: #4caf50;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
            margin-top: 20px;
            display: block;
            margin: 0 auto;
        }

        .back-button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: #ff0000;
            font-weight: bold;
            text-align: center;
        }

        .success-message {
            color: #008000;
            font-weight: bold;
            text-align: center;
        }
    </style>
</head>

<body>

    <body>
        <div class="container">
            <h2>Order Details</h2>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $user_id = $_POST['user_id'];
                $stocksId = $_POST['medium'];
                $quantity = $_POST['quantity'];
                $comment = $_POST['comment'];

                // Handle file upload
                $targetDirectory = "uploaded_img/"; // Adjust the directory as needed
                $documentPath = $targetDirectory . basename($_FILES["document"]["name"]);

                // Check if the available quantity is sufficient
                $checkQuantityQuery = "SELECT QUANTITY, PRICE FROM stocks WHERE STOCKS_ID = $stocksId";
                $checkQuantityResult = mysqli_query($conn, $checkQuantityQuery);

                if ($checkQuantityResult && $row = mysqli_fetch_assoc($checkQuantityResult)) {
                    $availableQuantity = $row['QUANTITY'];
                    $pricePerUnit = $row['PRICE'];

                    if ($availableQuantity >= $quantity) {
                        // Sufficient quantity, proceed with the order
                        if (move_uploaded_file($_FILES["document"]["tmp_name"], $documentPath)) {
                            // Calculate total price
                            $totalPrice = $quantity * $pricePerUnit;

                            // Use basename() to get only the filename
                            $documentFilename = basename($_FILES["document"]["name"]);

                            // Insert into printingorder table
                            $insertQuery = "INSERT INTO printingorder (STOCKS_ID, USER_ID, QUANTITY, COMMENT, TOTAL_PRICE, DOCUMENT) VALUES ($stocksId, $user_id, $quantity, '$comment', $totalPrice, '$documentFilename')";

                            if (mysqli_query($conn, $insertQuery)) {
                                // Insert into status table with default status 'D' (Denied)
                                $statusQuery = "INSERT INTO status (REQUEST_ID, STATUS, DATE) VALUES (LAST_INSERT_ID(), 'D', NOW())";
                                mysqli_query($conn, $statusQuery);

                                echo "<p>Medium: " . $stocksId . "</p>";
                                echo "<p>Quantity: " . $quantity . "</p>";
                                echo "<p>Comment: " . $comment . "</p>";
                                echo "<p>Total Price: $" . $totalPrice . "</p>";
                                echo "<p>Status: Denied</p>";  // Display default status
                                echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";
                                echo "<p class='success-message'>Order successfully placed!</p>";

                                // Update the stocks table with the new quantity
                                $updateQuery = "UPDATE stocks SET QUANTITY = QUANTITY - $quantity WHERE STOCKS_ID = $stocksId";
                                mysqli_query($conn, $updateQuery);
                            } else {
                                echo "<p class='error-message'>Sorry, something went wrong. Please try again later.</p>";
                            }
                        } else {
                            echo "<p class='error-message'>Invalid file upload. Please try again.</p>";
                        }
                    } else {
                        // Insufficient quantity
                        echo "<p class='error-message'>Sorry, the requested quantity is not available. Available quantity: $availableQuantity.</p>";
                    }
                }
            } else {
                echo "<p class='error-message'>Error checking available quantity. Please try again.</p>";
            }

            ?>

            <a href="index.php" class="back-button">Go Back to Index</a>
        </div>
    </body>

</html>