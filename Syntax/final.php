<?php
    $inputString = $_POST["array"];
    $inputArray = explode(" ", $inputString);
    $newArray = [];

    for ($i = 0; $i < count($inputArray); $i++) {
        try {
            $num = (int)$inputArray[$i];
        } catch (ValueError $ex) {
            echo "Invalid input. Enter integer values only.";
        }
        $newArray[$i] = $num;
    }

    echo "Max number is: ".(max($newArray))."<br>";
    echo "Sum: ".(array_sum($newArray));


