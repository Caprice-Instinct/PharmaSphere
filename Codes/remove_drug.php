<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drugID = $_POST['drugID'];

    // Retrieve the drug name using the drug ID from the drugs table
    $query = "SELECT drugName FROM drugs WHERE drugID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $drugID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $drugName);
    mysqli_stmt_fetch($stmt);

    if ($drugName) {
        // Remove the specified drug from the stock table
        $query = "DELETE FROM stock WHERE drugID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $drugID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "Drug '$drugName' (ID: $drugID) removed successfully!";
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }
    } else {
        echo "Error: Drug with ID '$drugID' not found.";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
