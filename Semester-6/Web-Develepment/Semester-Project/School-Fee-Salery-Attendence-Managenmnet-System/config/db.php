<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'eaglets_school');

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

session_start();

function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /04. eaglets_school/auth/login.php");
        exit();
    }
}
?>