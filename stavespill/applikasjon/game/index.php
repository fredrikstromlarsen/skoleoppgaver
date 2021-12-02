<?php
// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "game")) header("location:../");

include("./leaderboard/index.php"); 

?>
<div class="col-center">

</div>
<div class="col-right"></div>
    <?php showLeaderboard();?>