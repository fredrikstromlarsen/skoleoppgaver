<?php
// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "scoreboard")) header("location:../");

function scoreboard()
{
    // Sort user list based on score.
    $userlistSorted = $GLOBALS["db"][$_SESSION['gamepin']]["users"];
    usort($userlistSorted, function ($a, $b) {
        return $b["score"] - $a["score"];
    });
?>
    <table class="leaderboard" border="1">
        <tr>
            <th>Spiller</th>
            <th>Score</th>
        </tr>
        <?php
        foreach ($userlistSorted as $userdata) {
            $isMe = "";
            if (isset($_SESSION["userid"]) && strtolower($userdata["name"]) == $_SESSION["userid"]) {
                $isMe = "<img src=\"img/arrow.svg\" alt=\"Du\" class=\"icon i-inline\">&nbsp;";
            }
        ?>
            <tr>
                <td><?= $isMe . $userdata["name"] ?></td>
                <td><?= $userdata["score"] ?></td>
            </tr>
        <?php
        }
        ?>
        </tr>
    </table>
<?php }
