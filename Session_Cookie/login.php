<?php
session_start();
$username = "user" || "admin";
$password = password_hash("123456789", PASSWORD_DEFAULT);
$username_err = $password_err = $wrong_err = "";
$r = "";

if (!isset($_COOKIE["session_id"])) {
    $r = session_id();
} else {
    header("Location: profile.php");
}

if (isset($_SERVER["REQUEST_METHOD"]) && $_SERVER["REQUEST_METHOD"] == "POST"){
    if (empty(trim($_POST["username"]))){
        $username_err = "Username is required.";
    }
    elseif (empty(trim($_POST["password"]))){
        $password_err = "Password is required.";
    }
    else {
        if ($_POST["username"] == $username && password_verify($_POST["password"], $password)){
            header("Location: profile.php");
            $_SESSION['login_status'] = "success";
            $_SESSION['username'] = $_POST["username"];
            $_SESSION['name'] = "EXAMPLE PERSON";
            $_SESSION['country'] = "Vietnam";
            $_SESSION['lastSeen'] = date("Y-m-d H:i:s");
            $_SESSION['browser'] = $_SERVER['HTTP_USER_AGENT'];

            if (isset($_POST["remember"])){
                setcookie("username", $username, time() + (86400 * 7) ,"/");
            }
            if ($_POST["username"] == "admin"){
                setcookie("role", "admin", time() + 3600, "/");
                $_SESSION['name'] = "EXAMPLE ADMIN";
            } else {
                setcookie("role", "user", time() + 3600, "/");
            }

        }
        else {
            $wrong_err = "Wrong username or password.";
        }
    }
}
?>
<html lang="en">
    <head>
        <title>Login</title>
    </head>
    <body>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <span class="error"><?php echo $wrong_err ?></span><br><br>
            <label for="username">Username: </label>
            <input type="text" name="username" id="username">
            <span class="error"><?php echo $username_err ?></span><br><br>
            <label for="password">Password: </label>
            <input type="password" name="password" id="password">
            <span class="error"><?php echo $password_err ?></span><br><br>
            <input type="checkbox" name="remember" id="remember" value="on">
            <label for="remember">Remember me</label><br><br>
            <button type="submit">Login</button>
        </form>
    </body>
</html>