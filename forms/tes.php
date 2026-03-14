<?php

$host = "smtp.gmail.com";
$ports = [25, 465, 587];

foreach ($ports as $port) {

    $connection = @fsockopen($host, $port, $errno, $errstr, 5);

    if ($connection) {
        echo "Port $port : OPEN\n";
        fclose($connection);
    } else {
        echo "Port $port : BLOCKED ($errstr)\n";
    }
}