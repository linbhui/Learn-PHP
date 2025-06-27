<?php
session_start();
$db_host = 'localhost';
$db_user = 'testMe';
$db_password = 'Je/Y5Xi_k*aai6n@';
$db_db = "mydb";

$connection = @new mysqli(
    $db_host,
    $db_user,
    $db_password,
    $db_db
);

// Create a connection
if ($connection->connect_error) {
    echo 'Errno: '.$connection->connect_errno;
    echo '<br>';
    echo 'Error: '.$connection->connect_error;
    exit();
}

// Create a user table for the first time
$newTable = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(30) NOT NULL,
            name VARCHAR(30) NOT NULL,
            email VARCHAR(50) NOT NULL,
            title VARCHAR(30) NOT NULL)";
$connection->query($newTable);

// Prepared statements to insert data from form
$newUser = $connection->prepare("INSERT INTO users (username, name, email, title) VALUES (?, ?, ?, ?)");
$newUser->bind_param("ssss", $username, $name, $email, $title);

$username = $name = $email = $title = "";
$usernameErr = $nameErr = $emailErr = $titleErr = "";
$success= "";
$editForm = $updateError = $updateSuccess = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check existent users (username & email)
    $existingUsername = $existingEmail = "";
    if (isset($_POST['username']) && isset($_POST['email'])) {
        $existingUsername = $connection->query("SELECT id FROM users WHERE username='$_POST[username]'");
        $existingEmail = $connection->query("SELECT id FROM users WHERE email='$_POST[email]'");
    }

    // Validate inputs
    if (empty($_POST['username'])) {
        $usernameErr = "Username is required";
    } elseif (strlen($_POST['username']) > 30) {
        $usernameErr = "Username is too long";
    } elseif ($existingUsername->num_rows !== 0) {
        $usernameErr = "Username already exists";
    } else {
        $username = $_POST['username'];
    }
    if (empty($_POST['name'])) {
        $nameErr = "Name is required";
    } elseif (strlen($_POST['name']) > 30) {
        $nameErr = "Name is too long";
    } else {
        $name = $_POST['name'];
    }
    if (empty($_POST['email'])) {
        $emailErr = "Email is required";
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    } elseif ($existingEmail->num_rows !== 0) {
        $emailErr = "Email already exists";
    } else {
        $email = $_POST['email'];
    }
    if (empty($_POST['title'])) {
        $titleErr = "Title is required";
    } elseif (strlen($_POST['title']) > 30) {
        $titleErr = "Title is too long";
    } else {
        $title = $_POST['title'];
    }

    // Add user
    if ($username !== "" && $name !== "" && $email !== "" && $title !== "") {
        $newUser->execute();
        $success = "User added successfully <br><br>";
    }
}

if (!empty($_GET['action'])) {
    // Delete user
    if ($_GET['action'] == "delete") {
        $usernameErr = $nameErr = $emailErr = $titleErr = "";
        $delete_id = $_GET['id'];
        $query_delete = "DELETE FROM users WHERE id='$delete_id'";
        $connection->query($query_delete);
    }

    // Edit user
    $edit_id = "";
    if ($_GET['action'] == "edit") {
        $usernameErr = $nameErr = $emailErr = $titleErr = "";
        $edit_id = $_GET['id'];

        $query_edit = "SELECT * FROM users WHERE id='$edit_id'";
        $edit = $connection->query($query_edit);
        $row_edit = $edit->fetch_assoc();
        $editForm ="
    <form method='post'>
        <h1>Edit user</h1>
        <input type='text' name='updateUsername' value='".$row_edit['username']."'><br><br>
        <input type='text' name='updateName' value='".$row_edit['name']."'><br><br>
        <input type='text' name='updateEmail' value='".$row_edit['email']."'><br><br>
        <input type='text' name='updateTitle' value='".$row_edit['title']."'><br><br>
        <input type='hidden' name='id' value='" . $row_edit['id'] . "'>
        <button name='action' value='update'>Save</button><br><br>
    </form>
    ";
    }

// Prepared statements to update user
    $editUser = $connection->prepare("UPDATE users SET username=?, name=?, email=?, title=? WHERE id=?");
    $editUser->bind_param("ssssi", $updateUsername, $updateName, $updateEmail, $updateTitle, $edit_id);

// Update user in database
    $updateUsername = $updateName = $updateEmail = $updateTitle = $edit_id = "";
    if (!empty($_POST['action'])) {
        if ($_POST['action'] == "update") {
            // Get row ID
            $edit_id = $_POST['id'];
            // Check existent username/email
            $existingUsername = $existingEmail = $existingUsernameID = $existingEmailID = "";
            if (isset($_POST['updateUsername']) && isset($_POST['updateEmail'])) {
                $existingUsername = $connection->query("SELECT id FROM users WHERE username='$_POST[updateUsername]'");
                $existingEmail = $connection->query("SELECT id FROM users WHERE email='$_POST[updateEmail]'");
                $existingUsernameID = $existingUsername->fetch_assoc()['id'];
                $existingEmailID = $existingEmail->fetch_assoc()['id'];
            }
            // Validate input
            $updateUsername = $_POST['updateUsername'];
            $updateName = $_POST['updateName'];
            $updateEmail = $_POST['updateEmail'];
            $updateTitle = $_POST['updateTitle'];

            if (empty($_POST['updateUsername']) || empty($_POST['updateName']) || empty($_POST['updateEmail']) || empty($_POST['updateTitle'])) {
                $updateError = "All fields are required <br><br>";
                $updateUsername = $updateName = $updateEmail = $updateTitle = $edit_id = "";
            }
            if (!filter_var($_POST['updateEmail'], FILTER_VALIDATE_EMAIL)) {
                $updateError = "Invalid email format <br><br>";
                $updateEmail = "";
            }
            if ($existingUsernameID !== $edit_id) {
                $updateError = "Username already exists <br><br>";
                $updateUsername = "";
            }
            if ($existingEmailID !== $edit_id) {
                $updateError = "Email already exists <br><br>";
                $updateEmail = "";
            }

            // Update user
            if ($updateUsername !== "" && $updateName !== "" && $updateEmail !== "" && $updateTitle !== "") {
                $editUser->execute();
                header("Location: database.php");
            }

            $usernameErr = $nameErr = $emailErr = $titleErr = "";
        }
    }
}


// Print data rows
function printRow($database):void {
    $query_row = "SELECT * FROM users";
    $result = $database->query($query_row);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td style='text-align: center'>" . $row["id"] . "</td>
                <td>" . $row["username"] . "</td>
                <td>" . $row["name"] . "</td>
                <td>" . $row["email"] . "</td>
                <td>" . $row["title"] . "</td>
                <td>
                    <form method='get'>
                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                        <button name='action' value='edit'>Edit</button>
                        <button name='action' value='delete'>Delete</button>
                    </form>
                </td>
            </tr>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>User table</title>
    <style>
        body {
            display: grid;
            grid-template-columns: 3fr 1fr;
        }
        table {
            grid-column: 1 / span 1;
        }
        .add-user {
            grid-column: 2 / span 1;
        }
        th {
            text-align: left;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
        }
        .edit-form {
            grid-column: 2 / span 1;
            margin-left: 20%;
        }

    </style>
</head>
<body>
<table style="width:100%; margin: 0; border: 1px solid black; border-spacing: 0">
    <thead>
    <tr>
        <th style="width:10%; text-align: center">User ID</th>
        <th style="width:20%">Username</th>
        <th style="width:25%">Name</th>
        <th style="width:25%">Email</th>
        <th style="width:20%">Title</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
        <?php printRow($connection); ?>
    <tbody>
</table>

<form class="add-user" action="database.php" method="post" style="margin-left: 20%">
    <h1>Add user</h1>
    <?php echo $success ?>
    <label for="username">Username: </label>
    <input type="text" name="username" id="username" placeholder="example">
    <span class="error"><?php echo $usernameErr ?></span><br><br>
    <label for="name">Name: </label>
    <input type="text" name="name" id="name" placeholder="Jane Doe">
    <span class="error"><?php echo $nameErr ?></span><br><br>
    <label for="email">Email: </label>
    <input type="email" name="email" id="email" placeholder="user@example.com">
    <span class="error"><?php echo $emailErr ?></span><br><br>
    <label for="title">Job title: </label>
    <input type="text" name="title" id="title" placeholder="Teacher">
    <span class="error"><?php echo $titleErr ?></span><br><br>
    <button type="submit">Add User</button>
</form>

<div class="edit-form">
    <?php echo $editForm . $updateError ?>
</div>

</body>
</html>
