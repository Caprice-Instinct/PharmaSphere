<?php
// ... (previous code)

require_once('connect.php');
session_start(); 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $SSN = $_POST['SSN'];
    $fname = $_POST['sup_fname'];
    $lname = $_POST['sup_lname'];
    $email = $_POST['sup_email'];
    $phone = $_POST['sup_phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = 'Supervisor';

    // First, insert the user data into the user table
    $userQuery = "INSERT INTO user (username, password, usertype) VALUES (?, ?, ?)";
    $userStmt = mysqli_prepare($conn, $userQuery);
    mysqli_stmt_bind_param($userStmt, 'sss', $username, $password, $usertype);
    if (mysqli_stmt_execute($userStmt)) {
        echo "User data inserted successfully!<br>";

        // Get the inserted user's ID
        $userID = mysqli_insert_id($conn);

        // Retrieve the pharmacyID from the session variable
        $pharmacyID = $_SESSION['pharmacyID'];

        $supervisorQuery = "INSERT INTO supervisor (userID, pharmacyID, SSN, fname, lname, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $supervisorStmt = mysqli_prepare($conn, $supervisorQuery);
        mysqli_stmt_bind_param($supervisorStmt, 'iisssss', $userID, $pharmacyID, $SSN, $fname, $lname, $email, $phone);

        // Execute the query to insert supervisor data
        if (mysqli_stmt_execute($supervisorStmt)) {
            echo "Supervisor data inserted successfully!";
        } else {
            echo "Error inserting supervisor data: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Error inserting user data: " . mysqli_error($conn);
    }

    // Close the prepared statements
    mysqli_stmt_close($userStmt);
    mysqli_stmt_close($supervisorStmt);
    // Close the database connection
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <button class="back-button" onclick="goBack()">Back</button>

        <script>
            function goBack() {
                history.back();
            }
        </script>
    </body>
</html>
