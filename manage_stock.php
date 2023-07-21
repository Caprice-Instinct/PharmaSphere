<!DOCTYPE html>
<html>
<head>
    <title>Manage Stock</title>
    <link rel="stylesheet" href="style.css">
    <script>
        function confirmDelete(orderID) {
            if (confirm("Are you sure you want to delete this drug?")) {
                // Send an AJAX request to delete_drug_pharmacy.php
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'delete_drug_pharmacy.php', true);
                xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = xhr.responseText;
                        if (response.trim() === 'success') {
                            alert('Drug deleted successfully.');
                            // Reload the page to update the table
                            location.reload();
                        } else {
                            alert('Error: ' + response);
                        }
                    }
                };
                xhr.send('orderID=' + orderID);
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Stock Management</h1>
        <img src="images\stock.png" alt="Stock image" height="75" width="75">
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

        // Retrieve the pharmacistID for the pharmacist from the pharmacist table
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

        // Retrieve the drug information for the specific pharmacy from the orderpharmacy table
        $query = "SELECT * FROM orderpharmacy WHERE pharmacyID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $pharmacyID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Display the drug information in a table
        ?>
        <h2>Current Stock</h2>
        <table class="viewdata">
            <thead>
                <tr>
                    <th>Stock ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Weight(mg)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['orderID'] . "</td>";

                    // Retrieve the drugName from the drug table using the drugID
                    $drugID = $row['drugID'];
                    $drugQuery = "SELECT drugName FROM orderpharmacy WHERE drugID = ?";
                    $stmt = mysqli_prepare($conn, $drugQuery);
                    mysqli_stmt_bind_param($stmt, 'i', $drugID);
                    mysqli_stmt_execute($stmt);
                    $resultDrug = mysqli_stmt_get_result($stmt);
                    $rowDrug = mysqli_fetch_assoc($resultDrug);
                    $drugName = $rowDrug['drugName'];
                    mysqli_stmt_close($stmt);

                    echo "<td>" . $drugName . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td>" . $row['drugWeight'] . "</td>";
                    echo '<td>
                            <a href="edit_drug_pharmacy.php?orderID=' . $row['orderID'] . '">Edit</a> |
                            <a href="#" onclick="confirmDelete(' . $row['orderID'] . ')">Delete</a>
                          </td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <?php
        // Close the database connection
        mysqli_close($conn);
        ?>
    </div>
</body>
</html>
