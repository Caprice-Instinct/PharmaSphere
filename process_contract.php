<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pharmacyID = $_POST['pharmacyID'];
    $supervisorID = $_POST['supervisorID'];
    $pharmcoID = $_POST['pharmcoID'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];
    $contractText = $_POST['contractText'];

    // Calculate duration in years, months, and days
    $startDateTimestamp = strtotime($startDate);
    $endDateTimestamp = strtotime($endDate);
    $durationSeconds = $endDateTimestamp - $startDateTimestamp;
    $duration = floor($durationSeconds / (365 * 24 * 60 * 60)) . ' years, ' .
                floor(($durationSeconds % (365 * 24 * 60 * 60)) / (30 * 24 * 60 * 60)) . ' months, ' .
                floor((($durationSeconds % (365 * 24 * 60 * 60)) % (30 * 24 * 60 * 60)) / (24 * 60 * 60)) . ' days';

    // Insert the contract data into the database
    $query = "INSERT INTO contract (pharmacyID, supervisorID, pharmcoID, startDate, endDate, contractText, duration)
              VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'iiissss', $pharmacyID, $supervisorID, $pharmcoID, $startDate, $endDate, $contractText, $duration);

    if (mysqli_stmt_execute($stmt)) {
        echo "Contract data inserted successfully!";
    } else {
        echo "Error inserting contract data: " . mysqli_error($conn);
    }

    // Close the database connection
    mysqli_close($conn);
} else {
    header("Location: add_contract.php"); // Redirect back to the form if accessed directly
    exit();
}
?>
