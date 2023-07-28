<?php
require_once('connect.php');

session_start();

// Check if the user is logged in as a pharmaceutical company
if (!isset($_SESSION['userID']) || $_SESSION['user_type'] !== 'Pharmaceutical Company') {
    header("Location: login.php"); // Redirect to the login page if not logged in as a pharmaceutical company
    exit();
}

// Retrieve the username from wherever it is stored
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pharmaceutical Company Portal</title>
    <link rel="stylesheet" href="style.css">
</head>
  <body>
    <header class="username-header">
          Welcome, <?php echo $username; ?>!     
    </header>
    <br>
    <div class="link-container">
        <img src="images\pharmaceutical.png" alt="Login image" height="75" width="75"><br><br>
        <h1><?php echo $username; ?> Portal</h1>
        <a href="add_drug.php" class="link">Add Drug</a>
        <a href="display_drug_sold.php" class="link">Drugs sold</a>
        <br><br>
        <button class="back-button" onclick="goBack()">Back</button>
            <script>
                function goBack() {
                    history.back();
                }
            </script>
    </div>

    <script>
    var username = '<?php echo $username; ?>';
    document.getElementById('username').textContent = 'Welcome, ' + username + '!';
    </script>
  </body>
</html>
