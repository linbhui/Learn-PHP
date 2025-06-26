<?php
session_start();

if (!isset($_FILES["uploadingFile"])) {
    header("Location: uploadForm.php");
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["uploadingFile"]["name"]);
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
$success = $fail = "";
$upload = true;

if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["uploadingFile"]["tmp_name"]);
    if ($check !== false) {
        $success = "File is an image - " . $check["mime"] . ".";
    } else {
        $fail = "File is not an image.";
        echo "File is not an image.";
    }
} elseif (isset($_POST["button"])) {
    unlink("uploads/");
}

if (file_exists($target_file)) {
    $fail = "File already exists.";
    $upload = false;
}

if ($_FILES["uploadingFile"]["size"] > 2000000) {
    $fail = "File is too large.";
    $upload = false;
}

if ($imageFileType != "jpg" && $imageFileType != "png") {
    $fail = "Only JPG, PNG files are allowed.";
    $upload = false;
}

if ($upload) {
    if (move_uploaded_file($_FILES["uploadingFile"]["tmp_name"], $target_file)) {
        $success = "File uploaded successfully.";
    } else {
        $fail = "Could not upload file.";
    }
}

function addUpload() {
    $upload = "uploads/";
    if (is_dir($upload)) {
        if ($dh = opendir($upload)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != "." && $file != "..") {
                    echo "<span class='uploaded'><a href='". $upload.$file ."'>". $file ."</a></span><br>";
                }

            }
            closedir($dh);
        }
    }
    return;
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload image</title>
</head>
<body>
<form action="upload.php" method="post" enctype="multipart/form-data">
    <h1>Upload your image</h1>
    <input type="file" name="uploadingFile">
    <button type="submit">Upload</button><br>
    <span class="success"><?php echo $success ?></span>
    <span class="fail"><?php echo $fail ?></span><br><br>
    <?php addUpload() ?>
    <button type="button" formaction="upload.php" formmethod="post">Delete</button>
</form>
</body>
</html>