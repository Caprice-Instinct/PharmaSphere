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
    // Retrieve the drug details from the form submission
    $drugName = $_POST['drugName'];
    $drugDescription = $_POST['drugDescription'];
    $drugPrice = $_POST['drugPrice'];

    // Retrieve the pharmcoID associated with the user
    $userID = $_SESSION['userID'];
    $query = "SELECT pharmcoID FROM pharmco WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $pharmcoID = $row['pharmcoID'];

        // Insert the drug into the database with the pharmcoID, drug price, and drug weight
        $query = "INSERT INTO drug (pharmcoID, drugName, drugDescription, drugPrice) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'isss', $pharmcoID, $drugName, $drugDescription, $drugPrice);
        $result = mysqli_stmt_execute($stmt);

        // Check if the insertion was successful
        if ($result) {
            echo "Drug added successfully!";
            exit;
        } else {
            echo 'Error: ' . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo 'Error: Pharmaceutical company not found for the user.';
    }
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Drug</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="username-header">
        <?php echo $_SESSION['username']; ?><br>
    </div>
    <br>
    <div class="form-box">
        <img src="images\pills.png" alt="Pills image" height="75" width="75"><br><br>
        <form method="post" action="add_drug.php">
            <label for="drugName">Drug Name:</label><br>
            <input type="text" id="drugName" name="drugName" required><br>

            <label for="drugDescription">Drug Description:</label><br>
            <input type="text" id="drugDescription" name="drugDescription" required><br>
            
            <label for="drugPrice">Drug Price:</label><br>
            <input type="text" id="drugPrice" name="drugPrice" required><br>

            <input type="submit" value="Add Drug" class="btn">
            <input type="reset" value="Clear" class="btn">
        </form>
    </div>
</body>
</html>
