<!-- Leaderboard side -->
<?php
echo $_COOKIE['code'];
echo "<br>" . $_COOKIE['username'];
echo "<br>";
$game = [
    'session' => trim("session"),
    'name' => trim("name")
];
var_dump($game);

$players = $con->query("SELECT (name, score) FROM session WHERE id=" . $game["session"]);
var_dump($players);
?>
<div class="col-center">
    
</div>