<?php

$contents = file_get_contents("test.txt");
echo $contents . "<br>";
echo base64_decode($contents) . "<br>";
var_dump(json_decode(base64_decode($contents)));
