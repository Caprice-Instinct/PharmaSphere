<?php
session_start();

// Check if the user is logged in as a patient
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Patient') {
    header("Location: login.php"); // Redirect to the login page if not logged in as a patient
    exit();
}

require_once('connect.php');

$username = $_SESSION['username']; // Retrieve the username from the session variable
$userID = $_SESSION['userID']; // Retrieve the userID from the session variable

// Fetch the patientID for the current patient using userID
$patientIDQuery = "SELECT patID FROM patient WHERE userID = ?";
$stmt = mysqli_prepare($conn, $patientIDQuery);
mysqli_stmt_bind_param($stmt, 'i', $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 1) {
    $patientData = mysqli_fetch_assoc($result);
    $patientID = $patientData['patID'];

    // Fetch the prescriptions for the current patient using patientID
    $prescriptionQuery = "SELECT p.drugID, o.drugName AS orderDrugName, p.startDate, p.endDate
                          FROM prescription AS p
                          LEFT JOIN orderpharmacy AS o ON p.drugID = o.drugID
                          WHERE p.patID = ?";
    $prescriptionStmt = mysqli_prepare($conn, $prescriptionQuery);
    mysqli_stmt_bind_param($prescriptionStmt, 'i', $patientID);
    mysqli_stmt_execute($prescriptionStmt);
    $prescriptionResult = mysqli_stmt_get_result($prescriptionStmt);
} else {
    // Handle the case when patientID is not found (optional)
    // Redirect to an error page or perform some other action
}

// Helper function to get drug name from orderpharmacy table
function getDrugNameFromOrderPharmacy($conn, $drugID) {
    $drugQuery = "SELECT drugName FROM orderpharmacy WHERE drugID = ?";
    $stmt = mysqli_prepare($conn, $drugQuery);
    mysqli_stmt_bind_param($stmt, 'i', $drugID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return isset($row['drugName']) ? $row['drugName'] : 'N/A';
}
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

    <div class="container">
        <h2>Prescriptions</h2>
        <table class="viewdata">
            <tr>
                <th>Drug Name</th>
                <th>Start Date</th>
                <th>End Date</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($prescriptionResult)) { ?>
                <tr>
                    <td><?php echo isset($row['drugID']) ? getDrugNameFromOrderPharmacy($conn, $row['drugID']) : 'N/A'; ?></td>
                    <td><?php echo isset($row['startDate']) ? $row['startDate'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['endDate']) ? $row['endDate'] : 'N/A'; ?></td>
                </tr>
            <?php } ?>
        </table>
        <br><br>
            <button class="back-button" onclick="goBack()">Back</button>
            <script>
                function goBack() {
                    history.back();
                }
            </script>
    </div>
</body>
</html>
