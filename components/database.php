<?php

$conn = mysqli_connect("localhost", "root", "", "learningwebsite");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function unique_id()
{
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $rand = array();
    $length = strlen($str) - 1;
    for ($i = 0; $i < 20; $i++) {
        $n = mt_rand(0, $length);
        $rand[] = $str[$n];
    }
    return implode($rand);
}
