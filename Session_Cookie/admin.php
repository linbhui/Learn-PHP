<?php
session_start();

if (!isset($_SESSION['login_status'])) {
    header("Location: login.php");
} elseif ($_COOKIE['role'] != "admin") {
    header("Location: forbid.php");
} else {
    echo "YOU ARE AN ADMIN";
}

?>


