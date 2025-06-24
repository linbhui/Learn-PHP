<?php
    $num =  $_POST["num"];
    function factorial($x) {
        if ($x < 1) {
            return "Invalid number";
        }
        elseif ($x === 1) {
            return 1;
        }
        else {
            return $x * factorial($x - 1);
        }
    }

    echo factorial($num);