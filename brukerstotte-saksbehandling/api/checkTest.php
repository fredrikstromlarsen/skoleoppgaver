<?php
error_reporting(-1);
ini_set('display_errors', 1);

$contents = file_get_contents("test.txt");
echo $contents . "<br>";
echo base64_decode($contents) . "<br>";
foreach (json_decode(base64_decode($contents)) as $item) {
    echo base64_decode($item) . "<br>";
}
