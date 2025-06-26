<?php
session_start();
$success = $fail = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $success = $_SESSION['success'];
    $fail = $_SESSION['fail'];
}

?>


