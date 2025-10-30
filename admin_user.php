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
// Handle deleting user
if (isset($_POST['deleteUser'])) {
    $userIdToDelete = $_POST['user_id_delete'];

    // Delete user and related printing orders
    $deleteUserQuery = "DELETE FROM user WHERE USER_ID=$userIdToDelete";
    $deletePrintingOrdersQuery = "DELETE FROM printingorder WHERE USER_ID=$userIdToDelete";

    executeQuery($deleteUserQuery);
    executeQuery($deletePrintingOrdersQuery);

    // Refresh the page to reflect the updated user list
    header("Location: {$_SERVER['PHP_SELF']}");
    exit();
}

// Display users
$queryUsers = "SELECT * FROM user";
$resultUsers = executeQuery($queryUsers);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Admin - Users</title>
    <Style>
        body {
            font-family: Arial, sans-serif;
            background-color: rgba(255, 224, 178, 0.5);
            margin: 0;
            padding: 0;
        }

        h2 {
            color: darkorange;
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: orange;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #ecf0f1;
        }

        tr:hover {
            background-color: #bdc3c7;
        }

        form {
            display: inline-block;
        }

        input[type="submit"] {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 8px 12px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #c0392b;
        }
    </style>
    </style>
</head>

<body>
    <?php include 'admin_header.php' ?>
    <h2>User Information</h2>
    <table border="1">
        <tr>
            <th>User ID</th>
            <th>User Type</th>
            <th>Last Name</th>
            <th>Middle Name</th>
            <th>First Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>

        <?php
        // Display user information with delete option
        while ($rowUsers = $resultUsers->fetch_assoc()) {
            echo "<tr>";
            echo "<td>{$rowUsers['USER_ID']}</td>";
            echo "<td>{$rowUsers['USER_TYPE']}</td>";
            echo "<td>{$rowUsers['Lname']}</td>";
            echo "<td>{$rowUsers['Mname']}</td>";
            echo "<td>{$rowUsers['Fname']}</td>";
            echo "<td>{$rowUsers['EMAIL']}</td>";
            echo "<td>
                <form action=\"\" method=\"post\">
                    <input type=\"hidden\" name=\"user_id_delete\" value=\"{$rowUsers['USER_ID']}\">
                    <input type=\"submit\" name=\"deleteUser\" value=\"Delete\">
                </form>
              </td>";
            echo "</tr>";
        }
        ?>
    </table>

</body>

</html>

<?php
// Close the connection
$conn->close();

// Function to execute queries
function executeQuery($query)
{
    global $conn;
    $result = $conn->query($query);

    if (!$result) {
        die("Query failed: " . $conn->error);
    }

    return $result;
}
?>