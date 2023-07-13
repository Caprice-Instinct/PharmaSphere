<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header
<?php
require_once('connect.php');

if (isset($_GET['id'])) {
    $userID = $_GET['id'];

    // Retrieve the user data from the database
    $query = "SELECT * FROM user WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if the user exists
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Display the edit form with the user data
        ?>
        <form method="POST" action="update.php">
            <input type="hidden" name="userID" value="<?php echo $row['userID']; ?>">
            <label>Username:</label><br>
            <input type="text" name="username" value="<?php echo $row['username']; ?>"><br><br>
            <label>Password:</label><br>
            <input type="password" name="password" value="<?php echo $row['password']; ?>"><br><br>
            <input type="submit" value="Update">
        </form>
        <?php
    } else {
        echo 'User not found.';
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo 'Invalid request.';
}

// Close the database connection
mysqli_close($conn);
?>
    </body>
</html>