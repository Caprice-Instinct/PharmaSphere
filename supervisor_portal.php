<?php
session_start();
// Check if the user is logged in as a supervisor
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Supervisor') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a supervisor
    exit();
}

$username = $_SESSION['username']; // Retrieve the username from the session variable
?>
<!DOCTYPE html>
<html>
<head>
    <title>Supervisor Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="header">
          Welcome, <?php echo $username; ?>!     
    </header>
</body>
</html>
