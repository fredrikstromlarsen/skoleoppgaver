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
    <table>
        <tr>
            <th></th>
            <th>Spiller</th>
            <th class="r">Poeng</th>
        </tr>
        <?php
        foreach ($userlistSorted as $key=>$userdata) {
            $isMe = "";
            if (isset($_SESSION["userid"]) && strtolower($userdata["name"]) == $_SESSION["userid"])
                $isMe = "class=\"current-user\"";
        ?>
            <tr <?= $isMe ?>>
                <td class="r"><?= $key + 1 ?>.</td>
                <td><?= $userdata["name"] ?></td>
                <td class="r"><?= $userdata["score"] ?></td>
            </tr>
        <?php
        }
        ?>
        </tr>
    </table>
<?php }
