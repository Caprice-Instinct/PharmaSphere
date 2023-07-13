<!DOCTYPE html>
<html>
<body>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['size'])) {
            $size = $_POST['size'];
            echo "Selected size: " . $size;
        } else {
            echo "No size selected";
        }
    }
    ?>
</body>
</html>
