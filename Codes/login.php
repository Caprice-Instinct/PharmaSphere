<?php
require_once('connect.php');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare the SQL query with placeholders
    $query = "SELECT * FROM user WHERE username = ? AND password = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);

    // Check if the query execution failed
    if (!$stmt) {
        die("Query execution failed: " . mysqli_error($conn));
    }

    // Get the number of rows returned by the query
    $result = mysqli_stmt_get_result($stmt);
    $numRows = mysqli_num_rows($result);

    // Check if one and only one row is returned
    if ($numRows === 1) {
        $user = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        // Store user information in session variables
        session_start();
        $_SESSION['userID'] = $user['userID']; // Assuming the primary key column is 'userID'
        $_SESSION['username'] = $user['username'];
        $_SESSION['user_type'] = $user['usertype'];

        // Redirect user based on their user_type
        switch ($user['usertype']) {
            case 'Doctor':
                header("Location: doctor_portal.php");
                break;
            case 'Patient':
                header("Location: patient_portal.php");
                break;
            case 'Pharmacist':
                header("Location: pharmacist_portal.php");
                break;
            case 'Pharmacy':
                header("Location: pharmacy_portal.php");
                break;
            case 'Pharmaceutical Company':
                header("Location: pharmco_portal.php");
                break;
            case 'Supervisor':
                header("Location: supervisor_portal.php");
                break;
            case 'Admin':
                header("Location: admin_portal.php");
                break;
            default:
                echo "Invalid username or password";
        }

        exit();
    } else {
        echo "Invalid username or password";
    }
}

mysqli_close($conn);
?>
