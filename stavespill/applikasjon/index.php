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
	// Initialize connection to database
	include("db.php");

	// Check if user has logged in
	if (isset($_COOKIE['username'])) {

		// ... and if it's a valid cookie, not just someone changing their cookie client side 
		$tmp = $con->query("SELECT * FROM user WHERE name='".$_COOKIE['username']."'");

		// If exactly 1 user exists with the name, continue
		if ($tmp->num_rows==1) {
			$p = "game";

			// Otherwise, return to the login screen
		} else $p = "login";
	} else $p = "login";

	// Show the appropriate page without redirecting
	echo "<section id='" . $p . "'>";
	include($p . "/index.php");
	?>
	</section>
</body>

</html>