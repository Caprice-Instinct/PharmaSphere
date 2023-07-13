<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $drugName = $_POST['drugName'];
    $quantity = $_POST['quantity'];

    // Insert the new drug into the stock table
    $query = "INSERT INTO stock (drugName, quantity) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'si', $drugName, $quantity);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        echo "Drug added successfully!";
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>
