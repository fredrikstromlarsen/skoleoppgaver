<?php
session_start();
session_regenerate_id();

// Remove user from game.
$ul = json_decode(file_get_contents("../json/db.json"), TRUE);
if (isset($ul[$_SESSION['gamepin']]["users"][$_SESSION['userid']])) {
    unset($ul[$_SESSION['gamepin']]["users"][$_SESSION['userid']]);
    file_put_contents('../json/db.json', json_encode($ul));
}

// Supports all browser IIRC.
// Removes all session data.
session_unset();
session_destroy();
session_write_close();
setcookie(session_name(), '', 0, '/');

// Let index.php determine where to go next.
header("Location:../");
