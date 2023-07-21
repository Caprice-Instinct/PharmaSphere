<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $SSN = $_POST['SSN'];
    $fname = $_POST['doc_fname'];
    $lname = $_POST['doc_lname'];
    $email = $_POST['doc_email'];
    $phone = $_POST['doc_phone'];
    $specialty = $_POST['doc_specialty'];
    $yop = $_POST['doc_yop'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = 'Doctor';

    $currentDate = new DateTime();
    $yopDate = DateTime::createFromFormat('Y-m-d', $yop);
    $duration = $yopDate->diff($currentDate);
    $durationofpractice = $duration->format('%y years, %m months');

    $query = "INSERT INTO user (username, password, usertype) VALUES ('$username', '$password', '$usertype')";

    if (mysqli_query($conn, $query)) {
        echo "User data inserted successfully!<br>";
        
        // Get the inserted user's ID
        $userID = mysqli_insert_id($conn);
        
        // Insert patient data with the obtained userID
        $query1 = "INSERT INTO doctor (userID, SSN, fname, lname, email, phone, specialty, yearofpractice, duration) VALUES ('$userID', '$SSN', '$fname', '$lname', '$email', '$phone', '$specialty', '$yop' , '$durationofpractice')";
        
        // Execute the query to insert doctor data
        if (mysqli_query($conn, $query1)) {
            echo "Doctor data inserted successfully!";
        } else {
            echo "Error inserting doctor data: " . mysqli_error($conn) . "<br>";
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