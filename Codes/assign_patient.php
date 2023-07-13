<?php
require_once('connect.php');
session_start();

// Check if the user is logged in as a doctor
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Doctor') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a doctor
    exit();
}

$username = $_SESSION['username']; // Retrieve the username from the session variable
$doctorID = $_GET['docID']; // Retrieve the doctor's ID from the session variable

// Query the database to fetch unassigned patients
$query = "SELECT * FROM patient WHERE assignedDocID IS NULL";
$result = mysqli_query($conn, $query);

// Check if the query was executed successfully
if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}

// Process form submission
// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected patient ID from the form submission
    $patientID = $_POST['patient'];

    // Update the patient table with the assigned doctor ID using prepared statement
    $updateQuery = "UPDATE patient SET assignedDocID = ? WHERE patID = ?";
    $stmt = mysqli_prepare($conn, $updateQuery);
    mysqli_stmt_bind_param($stmt, 'ii', $doctorID, $patientID);

    if (mysqli_stmt_execute($stmt)) {
        echo "Patient assigned successfully!";
    } else {
        echo "Error assigning patient: " . mysqli_error($conn);
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}


// Fetch all unassigned patients
$patients = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Free the result set
mysqli_free_result($result);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Assign Patients</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="username-header">
        Welcome, <?php echo $username; ?>!
    </header>
    <br>
    <h1>Assign Patient</h1>
    
    <!-- Form for assigning patients -->
    <form action="assign_patient.php" method="post" class="form-box">
        <label for="patient">Assign Patient:</label><br>
        <select id="patient" name="patient">
            <!-- Populate the select options with available patients -->
            <?php foreach ($patients as $patient): ?>
                <option value="<?php echo $patient['patID']; ?>"><?php echo $patient['fname'] . ' ' . $patient['lname']; ?></option>
            <?php endforeach; ?>
        </select><br><br>
        <input type="submit" value="Assign Patient" class="btn">
    </form>
    <!-- Add other content specific to the doctor portal -->
 
</body>
</html>
