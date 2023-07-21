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
    <header class="username-header">
        Welcome, <?php echo $username; ?>!
    </header>
    <br>
    <div id="menu" >
        <a href="view_users.php">Edit user information</a>
    </div>
    <div id="menu">
        <ul>
            <li><a href="admin_reg.html">Register new admin</a></li>
            <li><a href="view_pat.php">View patients</a></li>
            <li><a href="view_doc.php">View doctors</a></li>
            <li><a href="view_supervisor.php">View supervisors</a></li>
            <li><a href="view_pharmacist.php">View pharmacists</a></li>
            <li><a href="view_pharmacy.php">View pharmacies</a></li>
            <li><a href="view_pharmco.php">View pharmaceutical companies</a></li>
        </ul>
    </div>
</body>
</html>
