<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['pharm_name'];
    $email = $_POST['pharm_email'];
    $phone = $_POST['pharm_phone'];
    $password = $_POST['password'];
    $usertype = 'Pharmacy';

    $query = "INSERT INTO user (username, password, usertype) VALUES ('$username', '$password', '$usertype')";

    // Execute the query to insert user data
    if (mysqli_query($conn, $query)) {
        echo "User data inserted successfully!<br>";
        
        // Get the inserted user's ID
        $userID = mysqli_insert_id($conn);
        
        $query1 = "INSERT INTO pharmacy (userID, name, email, phone) VALUES ('$userID', '$username', '$email', '$phone')";
        
        // Execute the query to insert pharmacy data
        if (mysqli_query($conn, $query1)) {
            echo "Pharmacy data inserted successfully!";
        } else {
            echo "Error inserting pharmacy data: " . mysqli_error($conn) . "<br>";
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