<?php
$con = new mysqli("localhost", "root", "", "stavespill");
if ($con->connect_error) {
    die("Kunne ikke koble til databasen");
}
