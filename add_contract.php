<?php
require_once('connect.php');

session_start();

// Check if the user is logged in as a pharmacy
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Pharmaceutical Company') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a pharmacy
    exit();
}

$userID = $_SESSION['userID']; // Retrieve the user ID from the session variable
$username = $_SESSION['username']; // Retrieve the username from the session variable

// Retrieve the pharmacyID based on the logged-in user's ID
$query = "SELECT pharmcoID FROM pharmco WHERE userID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {
    $pharmcoID = $row['pharmcoID'];
    $_SESSION['pharmcoid'] = $pharmcoID; // Store the pharmacyID in the session variable
} else {
    // Handle the case where pharmacyID is not found
    echo "Error: Pharmaceutical Company ID not found.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the contract details from the form submission
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $contractText = $_POST['contractText'];

    // Calculate the duration of the contract
    $startDateObj = new DateTime($startDate);
    $endDateObj = new DateTime($endDate);
    $duration = $startDateObj->diff($endDateObj);
    $formattedDuration = $duration->format('%y years, %m months, %d days');

    // Insert the contract into the database with the pharmcoID and duration
    $query = "INSERT INTO contract (pharmcoID, startDate, endDate, duration, contractText) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'issss', $pharmcoID, $startDate, $endDate, $formattedDuration, $contractText);
    $result = mysqli_stmt_execute($stmt);

    // Check if the insertion was successful
    if ($result) {
        echo "Contract added successfully!";
        exit;
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Check for active contracts
$today = date('Y-m-d');
$query = "SELECT * FROM contract WHERE pharmcoID = ? AND startDate <= ? AND endDate >= ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'iss', $pharmcoID, $today, $today);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Contract</title>
    <style>
        .header {
            display: flex;
            justify-content: flex-end; /* Align to the right */
            align-items: flex-start;
        }

        .user-info {
            align-self: flex-start;
        }
    </style>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">
        <div class="user-info">
            <?php echo $_SESSION['username']; ?><br>
        </div>
    </div>

    <br>

    <form method="post" action="add_contract.php">
        <fieldset>
            <legend>New Contract</legend>
            <label for="startDate">Start Date</label><br>
            <input type="date" id="startDate" name="startDate" required><br>

            <label for="endDate">End Date</label><br>
            <input type="date" id="endDate" name="endDate" required><br>

            <label for="contractText">Contract Text</label><br>
            <textarea id="contractText" name="contractText" required></textarea><br><br>

            <input type="submit" value="Add Contract" class="btn">
            <input type="reset" value="Clear" class="btn">
        </fieldset>
    </form>
</body>
</html>
