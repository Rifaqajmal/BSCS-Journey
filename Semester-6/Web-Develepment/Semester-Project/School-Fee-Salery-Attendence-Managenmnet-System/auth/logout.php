<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/04. eaglets_school/config/db.php';
session_destroy();
header("Location: /04. eaglets_school/auth/login.php");
exit();
?>