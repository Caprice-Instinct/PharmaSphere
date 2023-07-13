<?php
require_once('connect.php');

session_start();

// Check if the user is logged in as a pharmacist
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Pharmacist') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a pharmacist
    exit();
}

$userID = $_SESSION['userID']; // Retrieve the user ID from the session variable
$username = $_SESSION['username']; // Retrieve the username from the session variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the order details from the form submission
    $pharmcoID = $_POST['pharmcoID'];
    $drugID = $_POST['drugName'];
    $quantity = $_POST['quantity'];
    $drugWeight = $_POST['drugWeight'];

    // Validate that the drug is available in the selected pharmaceutical company
    $query = "SELECT COUNT(*) AS count FROM drug WHERE pharmcoID = ? AND drugID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $pharmcoID, $drugID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    if ($data['count'] === 0) {
        echo "Error: The selected drug is not available in the selected pharmaceutical company.";
        exit;
    }

    // Check if the pharmacist exists
    $query = "SELECT pharmacistID FROM pharmacist WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    if (!$data) {
        echo "Error: The pharmacist does not exist.";
        exit;
    }

    $pharmacistID = $data['pharmacistID'];

    // Retrieve the pharmacyID for the pharmacist
    $query = "SELECT pharmacyID FROM pharmacist WHERE pharmacistID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $pharmacistID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    $pharmacyID = $data['pharmacyID'];

    // Insert the order into the database
    $query = "INSERT INTO orderPharmacy (pharmcoID, pharmacistID, drugID, pharmacyID, quantity, drugWeight) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'iiiiii', $pharmcoID, $pharmacistID, $drugID, $pharmacyID, $quantity, $drugWeight);
    $result = mysqli_stmt_execute($stmt);

    // Check if the insertion was successful
    if ($result) {
        echo "Order placed successfully!";
        exit;
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Redirect to the place order page if accessed directly without form submission
header("Location: place_order.php");
exit();
?>
