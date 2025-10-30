<?php
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

$queryPrintingOrders = "SELECT * FROM printingorder";
$resultPrintingOrders = executeQuery($queryPrintingOrders);

$userIdsWithOrders = [];
while ($rowPrintingOrders = $resultPrintingOrders->fetch_assoc()) {
    $userIdsWithOrders[] = $rowPrintingOrders['USER_ID'];
}

$userIdsWithOrders = array_unique($userIdsWithOrders);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Printing Orders</title>
    <link rel="stylesheet" href="style.css">

</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: rgba(255, 224, 178, 0.5);
        /* Light orange background with transparency */
        margin: 0;
        padding: 0;

    }

    table {
        width: 60%;
        margin: 20px auto;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 10px;
        text-align: center;
    }

    th {
        background-color: #ecb000;
        color: #fff;
    }

    tr:nth-child(even) {
        background-color: #ecf0f1;
    }

    tr:hover {
        background-color: #bdc3c7;
    }

    h2 {
        text-align: center;
        color: #ecb000;
    }

    p {
        color: #555;
        text-align: center;
    }

    /* Responsive styles for mobile */
    @media only screen and (max-width: 600px) {
        table {
            width: 20%;
        }

        /* Add more styles as needed */
</style>


<body>
    <?php include 'admin_header.php' ?>
    <?php
    foreach ($userIdsWithOrders as $userIdWithOrders) {
        $queryUserDetails = "SELECT Fname, Lname, EMAIL FROM user WHERE USER_ID = $userIdWithOrders";
        $resultUserDetails = executeQuery($queryUserDetails);
        $rowUserDetails = $resultUserDetails->fetch_assoc();
        $firstName = $rowUserDetails['Fname'];
        $lastName = $rowUserDetails['Lname'];
        $email = $rowUserDetails['EMAIL'];

        echo "<h2>$firstName $lastName's Printing Orders</h2>";
        echo "<p>Email: $email</p>";

        $resultPrintingOrders->data_seek(0);

        echo "<table border=\"1\">";
        echo "<tr>
            <th>Request ID</th>
            <th>Stocks ID</th>
            <th>Print Status</th>
            <th>Total Price</th>
            <th>Document</th>
            <th>Quantity</th>
            <th>Comment</th>
            <th>DateTime</th>
            <th>Status</th>
          </tr>";

        while ($rowPrintingOrders = $resultPrintingOrders->fetch_assoc()) {
            if ($rowPrintingOrders['USER_ID'] == $userIdWithOrders) {
                $requestId = $rowPrintingOrders['REQUEST_ID'];

                // Fetch status information
                $queryStatus = "SELECT DATE, STATUS FROM status WHERE REQUEST_ID = $requestId";
                $resultStatus = executeQuery($queryStatus);
                $rowStatus = $resultStatus->fetch_assoc();

                echo "<tr>";
                echo "<td>{$rowPrintingOrders['REQUEST_ID']}</td>";
                echo "<td>{$rowPrintingOrders['STOCKS_ID']}</td>";
                echo "<td>{$rowPrintingOrders['P_STATUS']}</td>";
                echo "<td>{$rowPrintingOrders['TOTAL_PRICE']}</td>";
                echo "<td>{$rowPrintingOrders['DOCUMENT']}</td>";
                echo "<td>{$rowPrintingOrders['QUANTITY']}</td>";
                echo "<td>{$rowPrintingOrders['COMMENT']}</td>";
                echo "<td>{$rowStatus['DATE']}</td>";
                echo "<td>{$rowStatus['STATUS']}</td>";
                echo "</tr>";
            }
        }

        echo "</table><hr>";
    }
    ?>

</body>

</html>

<?php
// Close the connection
$conn->close();
?>