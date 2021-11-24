<!-- Leaderboard side -->
<?php
$game = array(
    'id' => trim(preg_replace("/.\.*$/", "", base64_decode($_COOKIE['id']))),
    'name' => trim(preg_replace("/^\.*./", "", base64_decode($_COOKIE['id'])))
);

?>
<div class="col-center">
    <table class="leaderboard" border="1">
        <tr>
            <th>Spiller</th>
            <th>Score</th>
        </tr>
        <tr>
            <td></td>
            <td>58</td>
        </tr>
        <tr>
            <td>Mira</td>
            <td>29</td>
        </tr>
    </table>
</div>