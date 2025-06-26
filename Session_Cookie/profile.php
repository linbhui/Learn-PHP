<?php
session_start();

$_SESSION['lastSeen'] = date("Y-m-d H:i:s");

if (!isset($_SESSION['login_status'])) {
    header("Location: login.php");
}

if (isset($_POST['logout'])) {
    session_destroy();
    setcookie("PHPSESSID", "", time()-3600, "/");
    setcookie("role", "", time()-3600, "/");
    header("Location: login.php");
}

?>

<html lang="en">
    <body>
        <h1>Profile</h1>
        <?php
            if ($_COOKIE['role'] == "admin")
            echo "<a href=\"admin.php\">Admin Site</a>";
        ?>
        <ul>
            <li>Username: <?php echo $_SESSION['username']; ?></li>
            <li>Name: <?php echo $_SESSION['name'] ?></li>
            <li>Country: <?php echo $_SESSION['country'] ?></li>
            <li>Last login: <?php echo $_SESSION['lastSeen'] ?></li>
            <li>Browser used: <?php echo $_SESSION['browser'] ?></li>
        </ul>
        <form method="post">
            <input type="submit" name="logout" value="Log out">
        </form>
    </body>
</html>

