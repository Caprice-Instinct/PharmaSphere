<?php
session_start();
require_once('connect.php');

// Check if the user is logged in as a pharmaceutical company
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Pharmaceutical Company') {
    header("Location: login.php"); // Redirect to the login page if not logged in as a pharmaceutical company
    exit();
}

$companyID = $_SESSION['pharmco_id'] ?? null;
$username = $_SESSION['username'];

// Retrieve the list of drugs for the company
$query = "SELECT * FROM drug WHERE pharmcoID = '$companyID'";
$result = mysqli_query($conn, $query);

// Retrieve sales information for the company
$salesQuery = "SELECT * FROM sales WHERE contractID = '$companyID'";
$salesResult = mysqli_query($conn, $salesQuery);

// Retrieve contract information for the company
$contractQuery = "SELECT * FROM contract WHERE pharmcoID = '$companyID'";
$contractResult = mysqli_query($conn, $contractQuery);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add or modify drug logic
    if (isset($_POST['drugID']) && isset($_POST['drugName']) && isset($_POST['drugFormula'])) {
        $drugID = $_POST['drugID'];
        $drugName = $_POST['drugName'];
        $drugFormula = $_POST['drugFormula'];

        // Check if the pharmaceutical company exists in the database
        $pharmcoQuery = "SELECT * FROM pharmco WHERE pharmcoID = '$companyID'";
        $pharmcoResult = mysqli_query($conn, $pharmcoQuery);

        if (mysqli_num_rows($pharmcoResult) > 0) {
            // Check if the drug already exists in the database
            $existingQuery = "SELECT * FROM drug WHERE drugID = '$drugID' AND pharmcoID = '$companyID'";
            $existingResult = mysqli_query($conn, $existingQuery);

            if (mysqli_num_rows($existingResult) > 0) {
                // Update the existing drug
                $updateQuery = "UPDATE drug SET drugName = '$drugName', drugFormula = '$drugFormula' WHERE drugID = '$drugID' AND pharmcoID = '$companyID'";
                if (mysqli_query($conn, $updateQuery)) {
                    echo "Drug updated successfully!";
                } else {
                    echo "Error updating drug: " . mysqli_error($conn);
                }
            } else {
                // Add a new drug
                $insertQuery = "INSERT INTO drug (drugID, pharmcoID, drugName, drugFormula) VALUES ('$drugID', '$companyID', '$drugName', '$drugFormula')";
                if (mysqli_query($conn, $insertQuery)) {
                    echo "Drug added successfully!";
                } else {
                    echo "Error adding drug: " . mysqli_error($conn);
                }
            }
        } else {
            echo "Pharmaceutical company does not exist!";
        }
    }

    // Add or modify contract logic
    if (isset($_POST['startDate']) && isset($_POST['endDate']) && isset($_POST['contractText'])) {
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $contractText = $_POST['contractText'];

        // Check if the pharmaceutical company exists in the database
        $pharmcoQuery = "SELECT * FROM pharmco WHERE pharmcoID = '$companyID'";
        $pharmcoResult = mysqli_query($conn, $pharmcoQuery);

        if (mysqli_num_rows($pharmcoResult) > 0) {
            // Check if the contract already exists in the database
            $existingContractQuery = "SELECT * FROM contract WHERE pharmcoID = '$companyID'";
            $existingContractResult = mysqli_query($conn, $existingContractQuery);

            if (mysqli_num_rows($existingContractResult) > 0) {
                // Update the existing contract
                $updateContractQuery = "UPDATE contract SET startDate = '$startDate', endDate = '$endDate', contractText = '$contractText' WHERE pharmcoID = '$companyID'";
                if (mysqli_query($conn, $updateContractQuery)) {
                    echo "Contract updated successfully!";
                } else {
                    echo "Error updating contract: " . mysqli_error($conn);
                }
            } else {
                // Add a new contract
                $insertContractQuery = "INSERT INTO contract (pharmcoID, startDate, endDate, contractText) VALUES ('$companyID', '$startDate', '$endDate', '$contractText')";
                if (mysqli_query($conn, $insertContractQuery)) {
                    echo "Contract added successfully!";
                } else {
                    echo "Error adding contract: " . mysqli_error($conn);
                }
            }
        } else {
            echo "Pharmaceutical company does not exist!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pharmaceutical Company Portal</title>
    <!-- CSS and other head elements -->
</head>
<body>
    <header>
        <!-- Display the username at the top right -->
        <div style="text-align: right;">
            Welcome, <?php echo $username; ?>!
        </div>
        <!-- header content -->
    </header>

    <!-- pharmaceutical company portal content -->

    <h1>Drug List</h1>

    <table>
        <tr>
            <th>Drug ID</th>
            <th>Drug Name</th>
            <th>Drug Description</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['drugID']; ?></td>
                <td><?php echo $row['drugName']; ?></td>
                <td><?php echo $row['drugDescription']; ?></td>
            </tr>
        <?php } ?>
    </table>

    <h1>Add/Modify Drug</h1>

    <form method="POST" action="">
        <div>
            <label for="drugID">Drug ID:</label><br>
            <input type="text" id="drugID" name="drugID" required>
        </div>
        <br>
        <div>
            <label for="drugName">Drug Name:</label><br>
            <input type="text" id="drugName" name="drugName" required>
        </div>
        <br>
        <div>
            <label for="drugFormula">Drug Description:</label><br>
            <textarea id="drugFormula" name="drugFormula" required></textarea>
        </div>
        <br>
        <input type="submit" value="Add/Modify Drug">
    </form>

    <h1>Contract Information</h1>

    <?php if ($contractRow = mysqli_fetch_assoc($contractResult)) { ?>
        <div>
            <strong>Start Date:</strong> <?php echo $contractRow['startDate']; ?><br>
            <strong>End Date:</strong> <?php echo $contractRow['endDate']; ?><br>
            <strong>Contract Text:</strong> <?php echo $contractRow['contractText']; ?><br>
        </div>
    <?php } else { ?>
        <div>No contract information available.</div>
    <?php } ?>

    <h1>Add/Modify Contract</h1>

    <form method="POST" action="">
        <div>
            <label for="startDate">Start Date:</label><br>
            <input type="date" id="startDate" name="startDate" required>
        </div>
        <br>
        <div>
            <label for="endDate">End Date:</label><br>
            <input type="date" id="endDate" name="endDate" required>
        </div>
        <br>
        <div>
            <label for="contractText">Contract Text:</label><br>
            <textarea id="contractText" name="contractText" required></textarea>
        </div>
        <br>
        <input type="submit" value="Add/Modify Contract">
    </form>

    <h1>Sales Information</h1>

    <table>
        <tr>
            <th>Drug ID</th>
            <th>Quantity Sold</th>
            <th>Total Revenue</th>
        </tr>
        <?php while ($salesRow = mysqli_fetch_assoc($salesResult)) { ?>
            <tr>
                <td><?php echo $salesRow['drugID']; ?></td>
                <td><?php echo $salesRow['quantitySold']; ?></td>
                <td><?php echo $salesRow['totalRevenue']; ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
