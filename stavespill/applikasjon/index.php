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
	<!-- Foreløpig ikke nødvendig da det kun trengs en session. -->
	<section id="loginCode">
		<?php include("login/index.php"); ?>
	</section>
	<section id="loginUsername">
		<?php include("loginUsername/index.php"); ?>
	</section>
	<section id="leaderboard">
		<?php include("leaderboard/index.php"); ?>
	</section>
	<section id="game">
		<?php include("game/index.php"); ?>

	</section>
</body>

</html>