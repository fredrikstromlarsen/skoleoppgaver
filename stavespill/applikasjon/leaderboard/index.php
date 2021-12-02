<?php
// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "leaderboard")) header("location:../");

function showLeaderboard()
{
    $userlist = json_decode(file_get_contents("userlist.json"), true);
?>
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
<?php }
