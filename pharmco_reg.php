<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['pharmco_name'];
    $email = $_POST['pharmco_email'];
    $phone = $_POST['pharmco_phone'];
    $password = $_POST['password'];
    $usertype = 'Pharmaceutical Company';

    $query = "INSERT INTO user (username, password, usertype) VALUES ('$username', '$password', '$usertype')";

    // Execute the query to insert user data
    if (mysqli_query($conn, $query)) {
        echo "User data inserted successfully!<br>";
        
        // Get the inserted user's ID
        $userID = mysqli_insert_id($conn);
        
        $query1 = "INSERT INTO pharmco (userID, name, email, phone) VALUES ('$userID', '$username', '$email', '$phone')";
        
        // Execute the query to insert pharmaceutical company data
        if (mysqli_query($conn, $query1)) {
            echo "Pharmaceutical company data inserted successfully!";
        } else {
            echo "Error inserting pharmaceutical company data: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Error inserting user data: " . mysqli_error($conn);
    }
}
?>
<br><br><br>
<button class="back-button" onclick="goBack()">Back</button>
            <script>
                function goBack() {
                    history.back();
                }
            </script>
    </body>
</html>