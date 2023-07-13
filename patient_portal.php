<?php
session_start();

// Check if the user is logged in as a patient
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Patient') {
    header("Location: login.php"); // Redirect to the login page if not logged in as a patient
    exit();
}

$username = $_SESSION['username']; // Retrieve the username from the session variable
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="username-header">
          Welcome, <?php echo $username; ?>!     
    </header>
    <br>
    <h1>Patient Portal</h1>
</body>
</html>
