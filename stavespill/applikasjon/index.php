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
	if (isset($_COOKIE['id'])) {
		showThisPage("game");
		echo "game";
	} else {
		showThisPage("login");
		echo "login 1st else";
	}

	function showThisPage($page)
	{
		echo "<section id='" . $page . "'>";
		include($page . "/index.php");
	}
	?>
	</section>
</body>

</html>