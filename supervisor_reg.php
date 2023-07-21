<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $SSN = $_POST['SSN'];
    $fname = $_POST['sup_fname'];
    $lname = $_POST['sup_lname'];
    $email = $_POST['sup_email'];
    $phone = $_POST['sup_phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = 'Supervisor';

    $query = "INSERT INTO user (username, password, usertype) VALUES ('$username', '$password', '$usertype')";

    // Execute the query to insert user data
    if (mysqli_query($conn, $query)) {
        echo "User data inserted successfully!<br>";
        
        // Get the inserted user's ID
        $userID = mysqli_insert_id($conn);
        
        $query1 = "INSERT INTO supervisor (userID, SSN, fname, lname, email, phone) VALUES ('$userID', '$SSN', '$fname', '$lname', '$email', '$phone')";
        
        // Execute the query to insert supervisor data
        if (mysqli_query($conn, $query1)) {
            echo "Supervisor data inserted successfully!";
        } else {
            echo "Error inserting supervisor data: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Error inserting user data: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>

    </body>
</html>