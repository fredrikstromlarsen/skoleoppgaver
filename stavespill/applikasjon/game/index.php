<?php
if (str_contains(str_replace("/", " ", $_SERVER['REQUEST_URI']), "game")) header("location:../");
echo "<h1>game/index.php</h1>";
?>
<div class="col-center">
    <?php include("./leaderboard/index.php");?>
</div>