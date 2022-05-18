<?php
error_reporting(-1);
ini_set('display_errors', 1);

function getApproximateTime($a, $b)
{
    $ts = $b->diff($a);
    if ($ts->format("%y") != 0) return $ts->format("%y år");
    else if ($ts->format("%m") != 0) return $ts->format("%m mnd");
    else if ($ts->format("%d") != 0) return $ts->format("%d dgr");
    else if ($ts->format("%h") != 0) return $ts->format("%h t");
    else if ($ts->format("%i") != 0) return $ts->format("%i min");
    else if ($ts->format("%s") != 0) return $ts->format("%s s");
    else return "0 sekunder";
}

function display_table($conn, $condition)
{
    if ($condition != "") $sql = "SELECT * FROM `tickets` WHERE $condition";
    else $sql = "SELECT * FROM `tickets`";

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
            $priority = round(($row["impact"] + $row["urgency"]) / 2 - 0.1, 0);

            // Calculate the time between row["registered"] and row["started"]
            $registered = new DateTime($row["registered"]);
            if (isset($row["started"])) {
                $started = new DateTime($row["started"]);
                $responseTime = '<abbr title="' . $row["started"] . '">' . getApproximateTime($registered, $started) . '</abbr>';
            }

            if (isset($row["finished"])) {
                // Calculate the time between row["started"] and row["finished"]
                $finished = new DateTime($row["finished"]);
                $timeSpent = '<abbr title="' . $row["finished"] . '">' . getApproximateTime($started, $finished) . '</abbr>';
            }

            if ($row["status"] == 0) $manageAction = [0, "Påbegynt"];
            else if ($row["status"] == 1) $manageAction = [1, "Fullført"];

            $markup .= '
                <tr class="pri-' . $priority . '">
                    <td>' . $row["type"] . '</td>
                    <td>' . $priority . ' (' . $row["impact"] . ', ' . $row["urgency"] . ')</td>
                    <td>' . base64_decode($row["category"]) . '</td>
                    <td>' . substr(base64_decode($row["description"]), 0, 36) . '...</td>
                    <td>' . substr(str_replace("https://", "", base64_decode($row["page"])), 0, 32) . '...</td>
                    <td>' . $row["registered"] . '</td>';
            if (isset($responseTime)) {
                $markup .= '<td>' . $responseTime . '</td>';
                if (isset($timeSpent)) $markup .= '<td>' . $timeSpent . '</td>';
                else $markup .= '<td></td>';
            } else $markup .= '<td></td><td></td>';


            if (isset($manageAction))
                $markup .= '<td><a href="../api/manage.php?action=' . $manageAction[0] . '&id=' . $row["id"] . '">' . $manageAction[1] . '</a></td>';
            else
                $markup .= '<td></td>';
            if (!isset($finished))
                $markup .= '<td><a href="../api/manage.php?action=2&id=' . $row["id"] . '">Slett</a></td>';
            else
                $markup .= '<td><a href=\'mailto:' . base64_decode($row["email"]) . '?subject=Henvendelsen%20din%20er%20l%C3%B8st!&amp;body=Hei,%0A%0A' . urlencode($row["registered"]) . '%20la%20du%20inn%20en%20sak%20om%20' . urlencode(base64_decode($row["category"])) . '%20hos%20oss%20med%20denne%20beskrivelsen:%20%0A%3Ci%3E' . urlencode(base64_decode($row["description"])) . '%3C/i%3E%0A%0ASaken%20er%20ble%20startet%20' . urlencode($row["started"]) . '%20og%20ble%20fullf%C3%B8rt%20' . urlencode($row["finished"]) . '.%0A\'>Varsle kunden</a></td>';

            $markup .= '</tr>';
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
            background-color: #b22;
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