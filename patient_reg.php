<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
<?php
require_once('connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $SSN = $_POST['SSN'];
    $fname = $_POST['pat_fname'];
    $lname = $_POST['pat_lname'];
    $email = $_POST['pat_email'];
    $phone = $_POST['pat_phone'];
    $dob = $_POST['pat_dob'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $usertype = 'Patient';

    // Calculate patient age
    $dob = new DateTime($dob);
    $today = new DateTime();
    $age = $dob->diff($today)->y;

    $query = "INSERT INTO user (username, password, usertype) VALUES ('$username', '$password', '$usertype')";

    // Execute the query to insert user data
    if (mysqli_query($conn, $query)) {
        echo "User data inserted successfully!<br>";

        // Get the inserted user's ID
        $userID = mysqli_insert_id($conn);

        // Format dob as string in 'Y-m-d' format
        $dobString = $dob->format('Y-m-d');

        // Insert patient data with the obtained userID and age
        $query1 = "INSERT INTO patient (userID, SSN, fname, lname, email, phone, dob, age) VALUES ('$userID', '$SSN', '$fname', '$lname', '$email', '$phone', '$dobString', '$age')";

        // Execute the query to insert patient data
        if (mysqli_query($conn, $query1)) {
            echo "Patient data inserted successfully!";
        } else {
            echo "Error inserting patient data: " . mysqli_error($conn) . "<br>";
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
