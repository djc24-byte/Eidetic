<?php
ob_start(); // Add this line
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

function executeQuery($query)
{
    global $conn;
    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    return $result;
}

$queryStocks = "SELECT * FROM stocks";
$resultStocks = executeQuery($queryStocks);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="style.css">
</head>
<style>
    body {
        background-color: rgba(255, 224, 178, 0.5);
        /* Place the overlay behind the content */
    }


    /* Style for     the stock information table */
    table {
        background-color: white;
        width: 80%;
        max-width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        margin-left: auto;
        margin-right: auto;
    }

    th,
    td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: orange;
        color: white;
    }

    /* Rest of your styles remain unchanged */


    /* Style for the forms */
    form {
        width: 80%;
        max-width: 100%;
        margin: 20px auto;
        /* Center the form horizontally */
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    /* Rest of your styles remain unchanged */


    label {
        display: block;
        margin-bottom: 8px;
        font-weight: bold;
    }

    /* Container for centering the block */
    .form-container {
        background-color: white;
        width: 80%;
        max-width: 100%;
        margin: 20px auto;

        /* Center the block horizontally */
        text-align: center;
        /* Center align the contents */
    }

    /* Styles for the form elements */
    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container input[type="file"] {
        width: 40%;
        padding: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 3px;
        box-sizing: border-box;
    }

    .form-container input[type="submit"] {
        background-color: orange;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .form-container input[type="submit"]:hover {
        background-color: black;
    }

    /* Styles for the update and delete forms in the stock information table */
    .form-container td form {
        display: inline-block;
        margin-bottom: 5px;
    }

    /* Styles for the delete button in the stock information table */
    .form-container input[name="deleteStock"] {
        background-color: #ff3333;
    }

    .form-container input[name="deleteStock"]:hover {
        background-color: #cc0000;
    }


    h2 {
        text-align: center;
    }

    /* Media query for smaller screens */
    @media (max-width: 600px) {
        table {
            font-size: 12px;
            /* Adjust the font size */
        }
    }
</style>

<body>
    <?php include 'admin_header.php' ?>



    <!-- ... (previous code) -->

    <h2>Stock Information</h2>
    <table border="1">
        <tr>
            <th>Stock ID</th>
            <th>Medium</th>
            <th>Quantity</th>
            <th>Image</th>
            <th>Price</th>
        </tr>

        <?php
        // Display stock information
        while ($rowStocks = $resultStocks->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$rowStocks['STOCKS_ID']}</td>";
            echo "<td>{$rowStocks['MEDIUM']}</td>";
            echo "<td>{$rowStocks['QUANTITY']}</td>";
            echo "<td>{$rowStocks['IMAGE']}</td>";
            echo "<td>{$rowStocks['PRICE']}</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <div class="form-container">
        <!-- Add a form to add new stocks -->
        <h2>Add New Stock</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <label for="medium">Medium:</label>
            <input type="text" name="medium" required>
            <br>
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" required>
            <br>
            <label for="image">Image:</label>
            <input type="file" name="image" accept="image/*" required>
            <br>
            <label for="price">Price:</label>
            <input type="text" name="price" required>
            <br>
            <input type="submit" name="addStock" value="Add Stock">
        </form>
    </div>
    <?php

    if (isset($_POST['addStock'])) {
        $medium = $_POST['medium'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        // Check if a file is selected
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $targetDir = 'uploaded_img/';
            $targetFile = $targetDir . basename($_FILES['image']['name']);

            // Check if the file size is within the limit (10MB)
            if ($_FILES['image']['size'] <= 10 * 1024 * 1024) {
                // Move the uploaded file to the target directory
                move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
                $image = basename($_FILES['image']['name']); // Store only the filename
    
                // Insert the new stock record
                $addStockQuery = "INSERT INTO stocks (MEDIUM, QUANTITY, IMAGE, PRICE) VALUES ('$medium', $quantity, '$image', $price)";
                executeQuery($addStockQuery);

                // Refresh the page to display the updated stock information
                header("Location: {$_SERVER['PHP_SELF']}");
                exit();
            } else {
                echo "Error: File size exceeds the limit (10MB).";
            }
        } else {
            echo "Error: Please select an image file.";
        }
    }

    // Handle updating stock
    if (isset($_POST['updateStock'])) {
        $stockIdToUpdate = $_POST['stock_id'];
        $mediumToUpdate = $_POST['medium_update'];
        $quantityToUpdate = $_POST['quantity_update'];
        $priceToUpdate = $_POST['price_update'];

        // Check if a file is selected for update
        if (isset($_FILES['image_update']) && $_FILES['image_update']['error'] === 0) {
            $targetDir = 'uploaded_img/';
            $targetFile = $targetDir . basename($_FILES['image_update']['name']);
            $imageToUpdate = basename($_FILES['image_update']['name']); // Store only the filename
    
            // Check if the file size is within the limit (10MB)
            if ($_FILES['image_update']['size'] <= 10 * 1024 * 1024) {
                // Move the uploaded file to the target directory
                if (move_uploaded_file($_FILES['image_update']['tmp_name'], $targetFile)) {
                    // Update the stock record with the new image
                    $updateStockQuery = "UPDATE stocks SET MEDIUM='$mediumToUpdate', QUANTITY=$quantityToUpdate, IMAGE='$imageToUpdate', PRICE=$priceToUpdate WHERE STOCKS_ID=$stockIdToUpdate";
                    executeQuery($updateStockQuery);

                    // Refresh the page to display the updated stock information
                    header("Location: {$_SERVER['PHP_SELF']}");
                    exit();
                } else {
                    echo "Error: Failed to move the uploaded file.";
                }
            } else {
                echo "Error: File size exceeds the limit (10MB).";
            }
        } else {
            // Update the stock record without changing the image
            $updateStockQuery = "UPDATE stocks SET MEDIUM='$mediumToUpdate', QUANTITY=$quantityToUpdate, PRICE=$priceToUpdate WHERE STOCKS_ID=$stockIdToUpdate";
            executeQuery($updateStockQuery);

            // Refresh the page to display the updated stock information
            header("Location: {$_SERVER['PHP_SELF']}");
            exit();
        }

    }

    // Handle stock deletion
    if (isset($_POST['deleteStock'])) {
        $stockIdToDelete = $_POST['delete_stock_id'];

        // Delete the stock record
        $deleteStockQuery = "DELETE FROM stocks WHERE STOCKS_ID=$stockIdToDelete";
        executeQuery($deleteStockQuery);

        // Refresh the page to reflect the changes
        header("Location: {$_SERVER['PHP_SELF']}");
        exit();
    }
    ?>

    <!-- ... (previous code) -->

    <!-- Display stocks with update form -->
    <h2>Update Stock</h2>
    <table border="1">
        <tr>
            <th>Stock ID</th>
            <th>Medium</th>
            <th>Quantity</th>
            <th>Image</th>
            <th>Price</th>
            <th>Action</th>
        </tr>

        <?php
        // Display stock information with update form
        $resultStocks = executeQuery($queryStocks); // Fetch stock data again to include updates
        while ($rowStocks = $resultStocks->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$rowStocks['STOCKS_ID']}</td>";
            echo "<td>{$rowStocks['MEDIUM']}</td>";
            echo "<td>{$rowStocks['QUANTITY']}</td>";
            echo "<td>{$rowStocks['IMAGE']}</td>";
            echo "<td>{$rowStocks['PRICE']}</td>";
            echo "<td>
        <!-- Update form -->
        <form action=\"\" method=\"post\"enctype=\"multipart/form-data\">
            <input type=\"hidden\" name=\"stock_id\" value=\"{$rowStocks['STOCKS_ID']}\">
            <label for=\"medium_update\">Medium:</label>
            <input type=\"text\" name=\"medium_update\" value=\"{$rowStocks['MEDIUM']}\" required>
            <br>
            <label for=\"quantity_update\">Quantity:</label>
            <input type=\"number\" name=\"quantity_update\" value=\"{$rowStocks['QUANTITY']}\" required>
            <br>
            <label for=\"image_update\">New Image:</label>
            <input type=\"file\" name=\"image_update\" accept=\"image/*\">
            <br>
            <label for=\"price_update\">Price:</label>
            <input type=\"text\" name=\"price_update\" value=\"{$rowStocks['PRICE']}\" required>
            <br>
            <input type=\"submit\" name=\"updateStock\" value=\"Update\">
            <input type=\"hidden\" name=\"delete_stock_id\" value=\"{$rowStocks['STOCKS_ID']}\">
            <input type=\"submit\" name=\"deleteStock\" value=\"Delete\" onclick=\"return confirm('Are you sure you want to delete this stock?');\">
        </form>
        <br>
    </td>";

            echo "</tr>";
        }
        ?>
    </table>
</body>

</html>