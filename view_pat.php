<?php
require_once('connect.php');
$sql="SELECT * FROM Users";
$result= $conn->query($sql);
$row=$result->fetch_assoc();
print_r($row);
?>