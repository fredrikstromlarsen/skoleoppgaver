<!-- Leaderboard side -->
<div class="col-center">
    <table class="leaderboard" border="1">
        <tr>
            <th>Spiller</th>
            <th>Score</th>
        </tr>
        <?php
        for ($i = 0; $i < count($players); $i++) {
        ?>
            <tr>
                <td>
                    <?php echo $players[$i]["name"]; ?>
                </td>
                <td>
                    <?php echo $players[$i]["score"]; ?>
                </td>
            </tr>
        <?php
            echo "</tr>";
        }
        ?>
    </table>
    </table>
</div>