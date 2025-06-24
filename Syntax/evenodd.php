<?php
    $num = $_POST["num"];
    function checkEven($x){
        if ($x % 2 == 0) {
            return "even";
        } else {
            return "odd";
        }
    }

    echo "$num is " . checkEven($num);