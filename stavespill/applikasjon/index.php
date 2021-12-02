<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Stavespill</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php
	// Get data from Database
	// Is formatted like: [gameid][userid][name|score|favorite]
	$userlist = json_decode(file_get_contents("userlist.json"), true);

	// Check if user has logged in properly
	if (isset($_COOKIE['username']) && isset($_COOKIE['code'])) {
		$username = $_COOKIE['username'];
		$code = $_COOKIE['code'];
		$tmp = $userlist[$code];

		// Loop through user list to see if 
		// any user with that username in 
		// the chosen game exists
		$exists = FALSE;
		for ($i = 0; $i < count($userlist[$_COOKIE['code']]); $i++) $exists = $userlist[$_COOKIE['code']][$i]["name"] == $_COOKIE['username'] ? TRUE : $exists;
		if ($exists) $p = "game";
		else $p = "login";
	} else $p = "login";

	// Show the appropriate page without redirecting
	echo "<section id='" . $p . "'>";
	include($p . "/index.php");
	?>
	</section>
</body>

</html>