<?php
if (str_contains(str_replace("/", " ", $_SERVER['REQUEST_URI']), "leaderboard")) header("location:../");
echo "<h1>leaderboard/index.php</h1>";
?>
<div class="col-center">
    <table class="leaderboard" border="1">
        <tr>
            <th>Spiller</th>
            <th>Score</th>
        </tr>
        <?php
        foreach ($userlist[$_COOKIE['code']] as $userdata) {
            echo "<tr><td>" . $userdata["name"] . "</td><td>" . $userdata["score"] . "</td></tr>";
        }
        ?>
        </tr>
    </table>
</div>