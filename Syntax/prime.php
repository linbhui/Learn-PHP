<?php
    $num = $_POST["num"];
    function checkPrime($x) {
        if ($x > 0 && $x <= 2) {
            return "prime";
        } elseif ($x != 0 && $x % (int) sqrt($x) != 0) {
            return "prime";
        } else {
            return "not prime";
        }
    }

    echo "$num is ".checkPrime($num);