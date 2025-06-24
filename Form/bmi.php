<head>
    <title>BMI Calculator</title>
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
        .message {
            font-size: 1.4em;
            font-weight: 700;
        }
    </style>
</head>
<body>
    <?php
        $height = $weight = "";
        $heightErr = $weightErr = "";

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["height"])) {
                $heightErr = "Height is required";
            } elseif (!is_numeric($_POST["height"])) {
                $heightErr = "Enter a numeric value";
            } else {
                $height = handle($_POST["height"]);
            }
        }
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (empty($_POST["weight"])) {
                $weightErr = "Weight is required";
            } elseif (!is_numeric($_POST["weight"])) {
                $weightErr = "Enter a numeric value";
            } else {
                $weight = handle($_POST["weight"]);
            }
        }

        function calculateBMI($height, $weight) {
            if ($height == "" || $weight =="") {
                return "";
            }
            if ($_POST["unit"] == "metric") {
                $height = $height / 100;
            } elseif ($_POST["unit"] == "us") {
                $heightArray = array_map('float', explode("ft", $height));
                $height = ($heightArray[0] * 12 + $heightArray[1]) / 39.3701;
                $weight = $weight / 2.25;
            }
            return "Your BMI is: ".round($weight / ($height * $height), 2);
        }

        function handle($input):float {
            return (float) htmlspecialchars(stripslashes(trim($input)));
        }
    ?>
    <form method="post">
        <h1>BMI Calculator</h1>
        <input type="radio" name="unit" value="metric" checked="checked">Metric
        <input type="radio" name="unit" value="us">US units<br><br>
        <label for="height">Enter height: <span id="height-unit">(cm)</span></label><br>
        <input type="text" name="height" id="height" placeholder="170">
        <span class="error"><?php echo $heightErr ?></span><br><br>
        <label for="weight">Enter weight: <span id="weight-unit">(kg)</span></label><br>
        <input type="text" name="weight" id="weight" placeholder="60">
        <span class="error"><?php echo $weightErr ?></span><br><br>
        <button type="submit">Calculate</button><br><br>
        <p class="message"> <?php echo calculateBMI($height, $weight) ?></p><br><br>

    </form>
    <script>
        const unitRadios = document.querySelectorAll("input[name='unit']");
        const heightInput = document.querySelector("input[name='height']");
        const weightInput = document.querySelector("input[name='weight']");
        const heightUnit = document.getElementById("height-unit");
        const weightUnit = document.getElementById("weight-unit");

        const metric = {
            heightUnit: "(cm)",
            weightUnit: "(kg)",
            heightExample: 170,
            weightExample: 60
        }

        const us = {
            heightUnit: "(ft/in)",
            weightUnit: "lbs",
            heightExample: "5ft7",
            weightExample: 135
        }

        unitRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.checked) {
                    if (radio.value === "metric") {
                        heightUnit.textContent = metric["heightUnit"];
                        heightInput.setAttribute("placeholder", metric["heightExample"]);
                        weightUnit.textContent = metric["weightUnit"];
                        weightInput.setAttribute("placeholder", metric["weightExample"]);
                    } else if (radio.value === "us") {
                        heightUnit.textContent = us["heightUnit"];
                        heightInput.setAttribute("placeholder", us["heightExample"]);
                        weightUnit.textContent = us["weightUnit"];
                        weightInput.setAttribute("placeholder", us["weightExample"]);
                    }
                }
            })
        })

    </script>
</body>
