<head>
    <title>Multiplication Table</title>
    <style>
        table {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border: 2px solid black;
            border-spacing: 0;
            width: auto;
            height: auto;
        }

        td, th {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
            font-size: 20px;
        }

        th {
            background-color: darkgrey;
        }

        td {
            background-color: lightgrey;
        }

    </style>
</head>
<body>
<table>
    <?php
        $limit = 10;
        $table = [[]];
        for ($i = 0; $i <= $limit; $i++) {
            echo "<tr></tr>";
            for ($j = 0; $j <= $limit; $j++) {
                if ($i === 0 && $j === 0) {
                    $table[$i][$j] = "x";
                    echo "<th>".$table[$i][$j]."</th>";
                } elseif ($i === 0) {
                    $table[$i][$j] = $j;
                    echo "<th>".$table[$i][$j]."</th>";
                } elseif ($j === 0) {
                    $table[$i][$j] = $i;
                    echo "<th>".$table[$i][$j]."</th>";
                } else {
                    $table[$i][$j] = $i * $j;
                    echo "<td>".$table[$i][$j]."</td>";
                }

            }

        }
    ?>
</table>

</body>
