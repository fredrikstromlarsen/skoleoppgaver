<?php
require("./connect.php");

$row_id = (int) $_GET["id"];
$action = (int) $_GET["action"];

// Get data
$result = $connection->query("SELECT * FROM `tickets` WHERE `id` = '$row_id'");
if ($result->num_rows == 1) {
    if ($action === 0) {
        // Set `status` to 1
        $connection->query("UPDATE `tickets` SET `status` = '1' WHERE `id` = '$row_id'");
        // Set `started` to current time
        $connection->query("UPDATE `tickets` SET `started` = NOW() WHERE `id` = '$row_id'");
    } else if ($action === 1) {
        // Set `status` to 2
        $connection->query("UPDATE `tickets` SET `status` = '2' WHERE `id` = '$row_id'");
        // Set `finished` to current time
        $connection->query("UPDATE `tickets` SET `finished` = NOW() WHERE `id` = '$row_id'");
    } else if ($action === 2) {
        // Delete row from `tickets` table
        $connection->query("DELETE FROM `tickets` WHERE `id` = '$row_id'");
    }
}
header("Location: ../admin/index.php");
