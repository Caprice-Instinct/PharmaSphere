<?php
require_once('connect.php');

session_start();

// Check if the user is logged in as a pharmacist
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Pharmacist') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a pharmacist
    exit();
}

$userID = $_SESSION['userID']; // Retrieve the user ID from the session variable
$username = $_SESSION['username']; // Retrieve the username from the session variable

// Function to check if the drug is available in the selected pharmaceutical company
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pharmacist portal</title> 
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="username-header">
          Welcome, <?php echo $username; ?>!     
    </header>
    <br>
    <h1>Pharmacist Portal</h1>
    <a href="add_drugs_pharmacy.php"> Add drugs</a>
    <a href="manage_stock.php">Manage stock</a>
</body>
</html>
