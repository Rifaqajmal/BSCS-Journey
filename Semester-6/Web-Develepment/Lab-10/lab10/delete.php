<?php
include 'db.php';

$id = $_GET['id'];
mysqli_query($conn,"DELETE FROM employees WHERE id=$id");

header("Location:dashboard.php");
?>