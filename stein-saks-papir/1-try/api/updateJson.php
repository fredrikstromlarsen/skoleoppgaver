<?php
$json = json_encode([$_POST, $_GET]);
var_dump($json);
file_put_contents("../data/stats.json", $json);
