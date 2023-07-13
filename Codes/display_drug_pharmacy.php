<?php
require_once('connect.php');

session_start();

// Check if the user is logged in as a pharmacy
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Pharmacy') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a pharmacy
    exit();
}

$userID = $_SESSION['userID']; // Retrieve the user ID from the session variable
$username = $_SESSION['username']; // Retrieve the username from the session variable

// Retrieve the pharmacy ID for the logged-in pharmacy
$query = "SELECT pharmacyID FROM pharmacy WHERE userID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
$pharmacyID = $data['pharmacyID'];

// Retrieve the drugs in stock for the pharmacy
$query = "SELECT drug.drugID, drug.drugName, drug.quantity FROM drug INNER JOIN pharmacydrug ON drug.drugID = pharmacydrug.drugID WHERE pharmacydrug.pharmacyID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $pharmacyID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$drugs = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy Stock</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="username-header">
        <?php echo $username; ?>
    </header>
    <br>
    <div class="table-container">
        <h2>Drugs in Stock</h2>
        <table>
            <thead>
                <tr>
                    <th>Drug ID</th>
                    <th>Drug Name</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($drugs as $drug): ?>
                    <tr>
                        <td><?php echo $drug['drugID']; ?></td>
                        <td><?php echo $drug['drugName']; ?></td>
                        <td><?php echo $drug['quantity']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
