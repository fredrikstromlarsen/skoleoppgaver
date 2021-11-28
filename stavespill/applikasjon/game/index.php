<?php
$game = [
    'code' => trim($_COOKIE["code"]),
    'name' => trim($_COOKIE["username"])
];

$tmp = $con->query("SELECT * FROM user");
if ($tmp->num_rows > 0) {
    while ($player=$tmp->fetch_assoc()) {
        echo $player["userid"] . "<br>".$player["name"]."<br>".$player["favorite"]."<br>"; 
    }
}
?>
<div class="col-center">
    
</div>
