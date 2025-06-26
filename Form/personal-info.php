<head>
    <title>Personal Information</title>
    <style>
        body {
            background: lavender;
        }
        form {
            position: fixed;
            width: auto;
            height: auto;
            padding: 50px;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            background: lightblue;
        }
        .error {
            font-size: 0.8em;
            color: red;
        }
    </style>
</head>
<body>
    <?php
        $name = $email = $number = $message = "";
        $nameErr = $emailErr = $numberErr = $messageErr = "";
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Validate name
            if (empty($_POST["name"])) {
                $nameErr = "*Name is required";
            } else {
                $name = validate($_POST["name"]);
            }

            // Validate email
            if (empty($_POST["email"])) {
                $emailErr = "*Email is required";
            } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
                $emailErr = "*Invalid email format";
            } else {
                $email = validate($_POST["email"]);
            }

            // Validate phone number
            if (empty($_POST["phone-number"])) {
                $numberErr = "*Phone number is required";
            } elseif(!preg_match("/^\+84([0-9]){9}$/", $_POST["phone-number"])) {
                $numberErr = "*Invalid Phone number";
            } else {
                $number = validate($_POST["phone-number"]);
            }

            // Validate message
            if (empty($_POST["message"])) {
                $messageErr = "*Message is required";
            } elseif (strlen($_POST["message"]) < 50) {
                $messageErr = "*Message is too short";
            } else {
                $message = validate($_POST["message"]);
            }

        }

        function validate($input):string {
            return htmlspecialchars(stripslashes(trim($input)));
        }
    ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"])?>" method="post" target="_self">
        <h1>Personal Information</h1>
        <!--name, email, number, message -->
        <label for="name">Full Name: </label><br>
        <input type="text" name="name" id="name" placeholder="Ex: Jane Doe"><br>
        <span class="error"><?php echo $nameErr ?></span><br><br>
        <label for="email">Email: </label><br>
        <input type="email" name="email" id="email" placeholder="Ex: janedoe@example.com"><br>
        <span class="error"><?php echo $emailErr ?></span><br><br>
        <label for="phone-number">Phone Number: </label><br>
        <input type="text" name="phone-number" id="phone-number" placeholder="Ex: +84123456789"><br>
        <span class="error"><?php echo $numberErr ?></span><br><br>
        <label for="message">Message: <span>(Minimum 50 characters)</span></label><br>
        <textarea id="message" cols="50" rows="5"></textarea><br>
        <span class="error"><?php echo $messageErr ?></span><br><br>
        <button type="submit">Submit</button>

    </form>
</body>

