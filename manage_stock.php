<!DOCTYPE html>
<html>
<head>
    <title>Manage Stock</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Stock Management</h1>
    </header>

    <div class="container">
        <h2>Current Stock</h2>
        <table>
            <thead>
                <tr>
                    <th>Drug ID</th>
                    <th>Drug Name</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Connect to the database
                require_once('connect.php');

                // Retrieve the stock information from the database
                $query = "SELECT orderpharmacy.drugID, drug.drugName, orderpharmacy.quantity FROM orderpharmacy JOIN drug ON orderpharmacy.drugID = drug.drugID";
                $result = mysqli_query($conn, $query);

                // Loop through the stock records and display them in the table
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . $row['drugID'] . "</td>";
                    echo "<td>" . $row['drugName'] . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "</tr>";
                }

                // Close the database connection
                mysqli_close($conn);
                ?>
            </tbody>
        </table>

        <h2>Add New Drug</h2>
        <form method="post" action="add_drug.php">
            <label for="drugName">Drug Name:</label>
            <input type="text" id="drugName" name="drugName" required>
            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" required>
            <input type="submit" value="Add Drug">
        </form>

        <h2>Update Drug Quantity</h2>
        <form method="post" action="update_quantity.php">
            <label for="drugID">Drug ID:</label>
            <input type="text" id="drugID" name="drugID" required>
            <label for="newQuantity">New Quantity:</label>
            <input type="number" id="newQuantity" name="newQuantity" required>
            <input type="submit" value="Update Quantity">
        </form>

        <h2>Remove Drug</h2>
        <form method="post" action="remove_drug.php">
            <label for="drugID">Drug ID:</label>
            <input type="text" id="drugID" name="drugID" required>
            <input type="submit" value="Remove Drug">
        </form>
    </div>
</body>
</html>
