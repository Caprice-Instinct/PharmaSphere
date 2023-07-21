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
    <h1><?php echo $username; ?> Portal</h1>
    <nav>
      <ul>
        <li><a href="add_drug.php">Add Drug</a></li>
        <li><a href="add_contract.php">Add Contract</a></li>
        <li><a href="add_sales.php">Add Sales</a></li>
      </ul>
    </nav>

    <script>
    var username = '<?php echo $username; ?>';
    document.getElementById('username').textContent = 'Welcome, ' + username + '!';
    </script>
  </body>
</html>
