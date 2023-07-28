<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form data is submitted
    if (isset($_POST['patID'], $_POST['drugID'], $_POST['frequency'], $_POST['quantity'], $_POST['startDate'], $_POST['endDate'])) {
        // Retrieve the submitted form data
        $patID = $_POST['patID'];
        $drugID = $_POST['drugID'];
        $frequency = $_POST['frequency'];
        $quantity = $_POST['quantity'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];

        // Check if the drugID exists in the orderpharmacy table and retrieve the pharmacyID
        $checkQuery = "SELECT pharmacyID FROM orderpharmacy WHERE drugID = ? LIMIT 1";
        $checkStmt = mysqli_prepare($conn, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, 'i', $drugID);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if (mysqli_num_rows($checkResult) === 1) {
            $data = mysqli_fetch_assoc($checkResult);
            $pharmacyID = $data['pharmacyID'];

            // Insert the prescription into the database
            $insertQuery = "INSERT INTO prescription (patID, drugID, pharmacyID, frequency, quantity, startDate, endDate) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insertQuery);
            mysqli_stmt_bind_param($stmt, 'iiisiss', $patID, $drugID, $pharmacyID, $frequency, $quantity, $startDate, $endDate);

            if (mysqli_stmt_execute($stmt)) {
                echo "Prescription assigned successfully.";
            } else {
                echo "Error assigning prescription: " . mysqli_error($conn);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Invalid drug selection. Debug: drugID = " . $drugID;
        }

        // Close the check statement
        mysqli_stmt_close($checkStmt);
        exit();
    } else {
        echo "Error: Invalid form data.";
        exit();
    }
}
?>

<button class="back-button" onclick="goBack()">Back</button>
            <script>
                function goBack() {
                    history.back();
                }
            </script>
    </body>
</html>
