<?php
require_once('connect.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $SSN = $_POST['SSN'];
    $fname = $_POST['pha_fname'];
    $lname = $_POST['pha_lname'];
    $email = $_POST['pha_email'];
    $phone = $_POST['pha_phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = 'Pharmacist';

    $query = "INSERT INTO user (username, password, usertype) VALUES ('$username', '$password', '$usertype')";

    // Execute the query to insert user data
    if (mysqli_query($conn, $query)) {
        echo "User data inserted successfully!<br>";
        
        // Get the inserted user's ID
        $userID = mysqli_insert_id($conn);
        
        $query1 = "INSERT INTO pharmacist (userID, SSN, fname, lname, email, phone) VALUES ('$userID', '$SSN', '$fname', '$lname', '$email', '$phone')";
        
        // Execute the query to insert pharmacist data
        if (mysqli_query($conn, $query1)) {
            echo "Pharmacist data inserted successfully!";
        } else {
            echo "Error inserting pharmacist data: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Error inserting user data: " . mysqli_error($conn);
    }
}
?>