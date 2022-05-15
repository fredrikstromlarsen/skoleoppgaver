<?php
// Create connection
$connection = new mysqli("localhost", "root", "", "saksbehandlingssystem");
// Check connection
if ($connection->connect_error) die("Connection failed: " . mysqli_connect_error());
