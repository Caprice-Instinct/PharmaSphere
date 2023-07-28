<?php
session_start();

// Check if the user is logged in as a supervisor
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Supervisor') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a supervisor
    exit();
}

require_once('connect.php');

// Retrieve the userID from the session variable
$userID = $_SESSION['userID'];

// Use the userID to get the supervisorID and pharmacyID from the supervisor table
$query = "SELECT supervisorID, pharmacyID FROM supervisor WHERE userID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Initialize supervisorID and pharmacyID variables
$supervisorID = null;
$pharmacyID = null;

// Check if the query was successful and fetch the results
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $supervisorID = $row['supervisorID'];
    $pharmacyID = $row['pharmacyID'];
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Supervisor Portal</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="username-header">
        Welcome, <?php echo $_SESSION['username'];  ?>!     
    </header>
    <div class="link-container">
        <img src="images\supervisor.png" alt="Supervisor image" height="75" width="75"><br><br>
        <a class="link" href="add_contract.php?supervisorID=<?php echo $supervisorID; ?>&pharmacyID=<?php echo $pharmacyID; ?>">Add a new contract</a>
        <a class="link" href="view_contract.php?supervisorID=<?php echo $supervisorID; ?>">View contracts</a><br><br><br>
        <button class="back-button" onclick="goBack()">Back to log in</button>
        <script>
            function goBack() {
                history.back();
            }
        </script>
    </div>
</body>
</html>
