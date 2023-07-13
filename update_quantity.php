<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drugID = $_POST['drugID'];
    $newQuantity = $_POST['newQuantity'];

    // Retrieve the drug name using the drug ID from the drugs table
    $query = "SELECT drugName FROM drugs WHERE drugID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $drugID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $drugName);
    mysqli_stmt_fetch($stmt);

    if ($drugName) {
        // Update the quantity of the specified drug in the stock table
        $query = "UPDATE stock SET quantity = ? WHERE drugID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $newQuantity, $drugID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "Quantity for drug '$drugName' (ID: $drugID) updated successfully!";
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
