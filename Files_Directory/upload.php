<?php
session_start();

$target_dir = "uploads/";
$files = glob("uploads/*");
$success = $fail = "";
$upload = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $target_file = $target_dir . basename($_FILES["uploadingFile"]["name"]);
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    // Delete uploaded file
    if (isset($_POST["delete"])) {
        unlink($_POST["delete"]);
    }

    // Validate existence, type, limit
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

    // Save to directory
    if ($upload) {
        if (move_uploaded_file($_FILES["uploadingFile"]["tmp_name"], $target_file)) {
            $success = "File uploaded successfully.";
        } else {
            $fail = "Could not upload file.";
        }
    }
} else {
    // Delete all files when session ends - restart the page
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    session_destroy();
}

// Prints uploaded file & delete button
function addUpload():void {
    $upload = "uploads/";
    if (is_dir($upload)) {
        if ($dir_handle = opendir($upload)) {
            while (($file = readdir($$dir_handle)) !== false) {
                if ($file != "." && $file != "..") {
                    echo "<span class='uploaded'><a href='". $upload.$file ."'>". $file ."</a></span>";
                    echo "<button type='submit' name='delete' value='". $upload.$file ."'>Delete</button><br>";
                }
            }
            closedir($dir_handle);
        }
    }
}
?>

<!DOCTYPE html>
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
</form>
</body>
</html>
