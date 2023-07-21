<?php
// Connect to the database
require_once('connect.php');
session_start();

// Check if the user is logged in as a pharmacist
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Pharmacist') {
    header("Location: login.html"); // Redirect to the login page if not logged in as a pharmacist
    exit();
}

$userID = $_SESSION['userID']; // Retrieve the user ID from the session variable

// Retrieve the pharmacistID and pharmacyID for the pharmacist from the pharmacist table
$query = "SELECT pharmacistID, pharmacyID FROM pharmacist WHERE userID = ?";
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
$pharmacyID = $data['pharmacyID'];

// Check if the orderID is provided in the URL
if (!isset($_GET['orderID'])) {
    echo 'Invalid request.';
    exit();
}

$orderID = $_GET['orderID']; // Retrieve the orderID from the URL

// Retrieve the specific drug information for the pharmacy from the orderpharmacy table
$query = "SELECT o.quantity, o.drugWeight, d.drugName
          FROM orderpharmacy AS o
          INNER JOIN drug AS d ON o.drugID = d.drugID
          WHERE o.pharmacyID = ? AND o.orderID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'ii', $pharmacyID, $orderID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$row) {
    echo "Error: The drug does not exist in the pharmacy.";
    exit;
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Drug</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Edit Drug</h1>
    </header>

    <div>
        <form method="post" action="update_drug_pharmacy.php" class="form-box">
            <h2>Drug: <?php echo $row['drugName']; ?></h2>
            <label>Current Drug Name: <?php echo $row['drugName']; ?></label><br>
            <label>New Drug Name:</label>
            <input type="text" name="newDrugName" required><br>
            <input type="hidden" name="orderID" value="<?php echo $orderID; ?>">
            <label>Current Quantity: <?php echo $row['quantity']; ?></label><br>
            <label>New Quantity:</label>
            <input type="number" name="newQuantity" required><br>
            <label>Current Drug Weight (mg): <?php echo $row['drugWeight']; ?></label><br>
            <label>New Drug Weight (mg):</label>
            <input type="number" name="newDrugWeight" required><br>
            <input type="submit" value="Update Drug" class="btn">
        </form>
    </div>
</body>
</html>
