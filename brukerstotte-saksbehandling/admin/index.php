<?php

// Verbose error logging
error_reporting(-1);
ini_set('display_errors', 1);

// Human readable ~approximate time
function getApproximateTime($a, $b)
{
    // Figure out the time difference between the two dates
    $ts = $b->diff($a);

    // Works..
    if ($ts->format("%y") != 0) return $ts->format("%y år");
    else if ($ts->format("%m") != 0) return $ts->format("%m mnd");
    else if ($ts->format("%d") != 0) return $ts->format("%d dgr");
    else if ($ts->format("%h") != 0) return $ts->format("%h t");
    else if ($ts->format("%i") != 0) return $ts->format("%i min");
    else if ($ts->format("%s") != 0) return $ts->format("%s s");
    else return "0 sekunder";
}

// Generate HTML to show all tickets
function display_table($conn, $condition)
{
    // SQL query to get all tickets, both with and without 'WHERE' conditions
    if ($condition != "") $sql = "SELECT * FROM `tickets` WHERE $condition ORDER BY `id` DESC";
    else $sql = "SELECT * FROM `tickets` ORDER BY `id` DESC";

    // Send query to database
    $result = $conn->query($sql);

    // Show table only if there are more 1 or more tickets/results from the query
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

        // Loop through all tickets
        while ($row = $result->fetch_assoc()) {

            // Calculate "priority" based on urgency and impact:
            // Priority = urgency * impact / 2
            // ^^This, but round ~upwards-ish
            if (round(($row["impact"] + $row["urgency"]) / 2 - 0.1, 0) == 2) $priority =  round(($row["impact"] + $row["urgency"]) / 2, 0);
            else $priority = round(($row["impact"] + $row["urgency"]) / 2 - 0.1, 0);

            // Calculate the time between row["registered"] and row["started"]
            $registered = new DateTime($row["registered"]);

            // Started tickets get an extra field showing the time since it was started.
            if (isset($row["started"])) {
                $started = new DateTime($row["started"]);
                $responseTime = '<abbr title="' . $row["started"] . '">' . getApproximateTime($registered, $started) . '</abbr>';
            }

            // Same as started tickets, but for completed tickets.
            if (isset($row["finished"])) {
                // Calculate the time between row["started"] and row["finished"]
                $finished = new DateTime($row["finished"]);
                $timeSpent = '<abbr title="' . $row["finished"] . '">' . getApproximateTime($started, $finished) . '</abbr>';
            }

            // Urls get max display-length of 39 chars.
            // Add "..." if the url is too long (39+).
            if (strlen(str_replace("https://", "", base64_decode($row["page"]))) > 39) $page = substr(str_replace("https://", "", base64_decode($row["page"])), 0, 36) . "...";
            else $page = base64_decode($row["page"]);

            // Same goes for the description.
            if (strlen(base64_decode($row["description"])) > 39) $description = substr(base64_decode($row["description"]), 0, 36) . "...";
            else $description = base64_decode($row["description"]);

            // Started / Finished
            if ($row["status"] == 0) $manageAction = [0, "Påbegynt"];
            else if ($row["status"] == 1) $manageAction = [1, "Fullført"];

            // Add row to table with ticket data
            $markup .= '
                <tr class="pri-' . $priority . '">
                    <td>' . $row["type"] . '</td>
                    <td>' . $priority . ' (' . $row["impact"] . ', ' . $row["urgency"] . ')</td>
                    <td>' . base64_decode($row["category"]) . '</td>
                    <td>' . $description . '</td>
                    <td>' . $page . '</td>
                    <td>' . $row["registered"] . '</td>';

            // If ticket has been finished, show the time spent
            // between the time it was started->finished.
            if (isset($responseTime)) {
                $markup .= '<td>' . $responseTime . '</td>';
                if (isset($timeSpent)) $markup .= '<td>' . $timeSpent . '</td>';
                else $markup .= '<td></td>';
            } else $markup .= '<td></td><td></td>';


            // Based on ticket status, show the appropriate url-link-button-actions.
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