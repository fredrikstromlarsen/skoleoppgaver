<?php
function getApproximateTime($a, $b)
{
    $ts = $b->diff($a);
    if ($ts->format("%y") != 0) return $ts->format("%y år");
    else if ($ts->format("%m") != 0) return $ts->format("%m måneder");
    else if ($ts->format("%d") != 0) return $ts->format("%d dager");
    else if ($ts->format("%h") != 0) return $ts->format("%h timer");
    else if ($ts->format("%i") != 0) return $ts->format("%i minutter");
    else if ($ts->format("%s") != 0) return $ts->format("%s sekunder");
    else return "0 sekunder";
}

function display_table($conn, $condition)
{
    if ($condition != "") {
        $sql = "SELECT * FROM `cases` WHERE $condition";
    } else {
        $sql = "SELECT * FROM `cases`";
    }

    $result = $conn->query($sql);
    $markup = "";

    if ($result->num_rows > 0) {
        $markup = '
            <table>
                <tr>
                    <th>Type</th>
                    <th>Prioritet</th>
                    <th>Kategori</th>
                    <th>Beskrivelse</th>
                    <th>Side</th>
                    <th>Registrert</th>
                    <th>Startet etter</th>
                    <th>Fullført etter</th>
                    <th>Marker som</th>
                    <th>Behandle</th>
                </tr>';
        while ($row = $result->fetch_assoc()) {
            $priority = round(($row["impact"] + $row["urgency"]) / 2, 0);

            // Calculate the time between row["registered"] and row["started"]
            $registered = new DateTime($row["registered"]);
            if ($row["started"] != null) {
                $started = new DateTime($row["started"]);
                $responseTime = '<abbr title="' . $row["started"] . '">' . getApproximateTime($registered, $started) . '</abbr>';
            }

            if ($row["finished"] != null) {
                // Calculate the time between row["started"] and row["finished"]
                $finished = new DateTime($row["finished"]);
                $timeSpent = '<abbr title="' . $row["finished"] . '">' . getApproximateTime($started, $finished) . '</abbr>';
            }



            $manageAction = [];

            if ($row["status"] == 0) $manageAction = [0, "Påbegynt"];
            else if ($row["status"] == 1) $manageAction = [1, "Fullført"];

            $markup .= '
                <tr class="pri-' . $priority . '">
                    <td>' . $row["type"] . '</td>
                    <td>' . $priority . ' (' . $row["impact"] . ', ' . $row["urgency"] . ')</td>
                    <td>' . $row["category"] . '</td>
                    <td>' . substr($row["description"], 0, 36) . '...</td>
                    <td>' . substr(str_replace("https://", "", $row["page"]), 0, 32) . '...</td>
                    <td>' . $row["registered"] . '</td>
                    <td>' . $responseTime . '</td>
                    <td>' . $timeSpent . '</td>';

            if ($manageAction != "") $markup .= '<td><a href="../api/manage.php?action=' . $manageAction[0] . '&id=' . $row["id"] . '">' . $manageAction[1] . '</a></td>';
            $markup .= '<td><a href="../api/manage.php?action=2&id=' . $row["id"] . '">Slett</a></td>
                </tr>';
        }
        $markup .= "</table>";
    }
    return [$markup, $result->num_rows];
}

require("../api/connect.php");

$result_waiting = display_table($connection, "`status` = 0");
$result_started = display_table($connection, "`status` = 1");
$result_finished = display_table($connection, "`status` = 2");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saksbehandlingssystem</title>
    <style>
        a {
            color: inherit;
        }

        table {
            border: 2px solid #000;
            border-collapse: collapse;
        }

        tr:not(:last-child) {
            border-bottom: 1px solid #000a;
        }

        td,
        th {
            padding: 0.25rem 0.75rem;
            text-align: start;
        }

        td {
            padding: 0.5rem 1rem;
            word-wrap: break-word;
        }

        tr.pri-1 {
            background-color: #f45;
            color: #fff;
        }

        tr.pri-2 {
            background-color: #fc28;
        }

        tr.pri-3 {
            background-color: #0001;
        }
    </style>
</head>

<body>
    <h2>Ventende (<?= $result_waiting[1] ?>)</h2>
    <?= $result_waiting[0] ?>

    <h2>Påbegynt (<?= $result_started[1] ?>)</h2>
    <?= $result_started[0] ?>

    <h2>Fullførte (<?= $result_finished[1] ?>)</h2>
    <?= $result_finished[0] ?>
</body>

</html>

<?php
$connection->close();
?>