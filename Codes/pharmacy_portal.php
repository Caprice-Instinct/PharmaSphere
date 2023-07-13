<?php
session_start();
// Check if the user is logged in as a pharmacy
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Pharmacy') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a pharmacy
    exit();
}

$username = $_SESSION['username']; // Retrieve the username from the session variable
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pharmacy Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="username-header">
          Welcome, <?php echo $username; ?>!     
    </header>
    <br>
    <h2 class="portal-h2"><?php echo $username; ?> Portal</h2>
    <a href="add_pharmacist.php">Add Pharmacist</a>
    <a href="display_drug_pharmacy.html">View drugs in stock</a>


</body>
</html>

