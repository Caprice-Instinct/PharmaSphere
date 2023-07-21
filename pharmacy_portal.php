<?php
session_start();
require_once('connect.php');

// Check if the user is logged in as a pharmacy
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Pharmacy') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a pharmacy
    exit();
}

$userID = $_SESSION['userID']; // Retrieve the userID from the session variable
$username = $_SESSION['username']; // Retrieve the username from the session variable

// Retrieve the pharmacyID from the pharmacy table based on the userID
$query = "SELECT pharmacyID FROM pharmacy WHERE userID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $pharmacyData = mysqli_fetch_assoc($result);
    $_SESSION['pharmacyID'] = $pharmacyData['pharmacyID']; // Set the pharmacyID in the session variable
} else {
    // Handle the case when pharmacyID is not found (optional)
    // Redirect to an error page or perform some other action
    exit("Error: Pharmacy ID not found in the database.");
}

// Close the database statement
mysqli_stmt_close($stmt);
// Close the database connection
mysqli_close($conn);
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
    <a href="display_drug_pharmacy.php">View drugs in stock</a>
    <a href="display_prescription_pharmacy.php">View drugs dispensed</a>
</body>
</html>
