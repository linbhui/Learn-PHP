<?php
session_start();

$logFile = fopen("visitor.log", "a+");
$logText = file_get_contents("visitor.log");
$visits = preg_match_all("/(?<=visit: )\d+(?=\n)/", $logText, $matches );

if (empty($logText)) {
    $count = 0;
} else {
    $count = $matches[0][$visits - 1];
}

$_SESSION['visit'] = $count++;
$_SESSION['browser'] = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
$_SESSION['time'] = date("Y-m-d H:i:s");

$visit = "\tvisit: ".$count."\n";
$browser = "\tbrowser: ".$_SESSION['browser']."\n";
$ip = "\tip: ".$_SESSION['ip']."\n";
$time = "\tdate_time: ".$_SESSION['time']."\n\n";

fwrite($logFile, "log: \n");
fwrite($logFile, $visit);
fwrite($logFile, $browser);
fwrite($logFile, $ip);
fwrite($logFile, $time);

fclose($logFile);

session_unset();
