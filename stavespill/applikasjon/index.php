<?php
session_start();
print_r(array_diff(scandir(realpath(session_save_path())), array('.', '..')));
// Generate a new session id to prevent session hijacking
// Using boolean option true to delete old session 
session_regenerate_id(true);

// Get data from json file
// Format: [gameid][userid][name|score|favorite]
$userlist = json_decode(file_get_contents("./userlist.json"), true);
$wordlist_no = json_decode(file_get_contents("./wordlists/no_nb.json"), true)["1000"];

// Check if user has a valid session
if (isset($_SESSION['gamecode']) && isset($_SESSION['username'])) {
	if (isset($userlist[$_SESSION["gamecode"]][$_SESSION["username"]])) $p = "game";
	else $p = "login";
} else $p = "login";
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Stavespill</title>
	<link rel="stylesheet" href="style.css">
	<script src="js/main.js" defer></script>
</head>

<body>
	<?php

	// Load contents of the appropriate php file without redirecting
	echo "<section id='" . $p . "'>";
	include($p . "/index.php");
	?>
	</section>
</body>

</html>
<?php

// Close session
// session_commit();
