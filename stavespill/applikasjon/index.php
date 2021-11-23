<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Stavespill</title>
	<link rel="stylesheet" href="style.css">
	<script src="test.js" defer></script>
</head>

<body>
	<?php
	$page = preg_match("/\d{4}\s{1}[A-Za-z]*/", base64_decode($_SESSION['id'])) ? "game" : "login";
	echo "<section id='$page'>";
	include($page . "/index.php");
	?>
	</section>
</body>

</html>