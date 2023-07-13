<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the pharmacist details from the form submission
    $SSN = $_POST['SSN'];
    $fname = $_POST['pha_fname'];
    $lname = $_POST['pha_lname'];
    $email = $_POST['pha_email'];
    $phone = $_POST['pha_phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Retrieve the pharmacyID from the session variable
    session_start();
    $pharmcoID = $_SESSION['pharmcoid'];

    $query = "INSERT INTO user (username, password, usertype) VALUES ('$username', '$password', 'Pharmacist')";

    // Execute the query to insert user data
    if (mysqli_query($conn, $query)) {
        echo "User data inserted successfully!<br>";

        // Get the inserted user's ID
        $userID = mysqli_insert_id($conn);

        // Insert patient data with the obtained userID
        $query1 = "INSERT INTO pharmacist (userID, pharmacyID, SSN, fname, lname, email, phone) VALUES ('$userID', '$pharmcoID', '$SSN', '$fname', '$lname', '$email', '$phone')";

        // Execute the query to insert patient data
        if (mysqli_query($conn, $query1)) {
            echo "Pharmacist data inserted successfully!";
        } else {
            echo "Error inserting pharmacist data: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Error inserting user data: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Pharmacist</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="form-box">
        <h2>Add Pharmacist</h2>
        <form method="post" action="add_pharmacist.php">
                <label for="SSN">SSN:</label><br>
                <input type="text" id="SSN" name="SSN" required><br><br>

                <label for="pha_fname">First Name:</label><br>
                <input type="text" id="pha_fname" name="pha_fname" required><br><br>

                <label for="pha_lname">Last Name:</label><br>
                <input type="text" id="pha_lname" name="pha_lname" required><br><br>

                <label for="pha_email">Email Address:</label><br>
                <input type="email" id="pha_email" name="pha_email" required><br><br>

                <label for="pha_phone">Phone Number:</label><br>
                <input type="tel" id="pha_phone" name="pha_phone" required><br><br>

                <label for="username">Username:</label><br>
                <input type="text" id="username" name="username" required><br><br>

                <label for="password">Password:</label><br>
                <input type="password" id="password" name="password" required><br><br>

                <input type="submit" value="Add Pharmacist" class="btn">
                <input type="reset" value="Clear" class="btn"> 
        </form>
    </div>
</body>
</html>
