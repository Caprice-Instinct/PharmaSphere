<?php
session_start();

// Check if the user is logged in as a patient
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: login.php"); // Redirect to the login page if not logged in as a patient
    exit();
}

$username = $_SESSION['username']; // Retrieve the username from the session variable
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
        Welcome, <?php echo $username; ?>!
    </header>
    <br>
    <a href="view_users.php">Edit user information</a>
</body>
</html>
