<?php

/* TODO:
  * [ ] Fix login json problems.
  * [ ] Fix new task not changing.
  * [ ] Allow users to login, not just register.
  * [ ] Audio recordings for all words.
  * [ ] CSS.
  * [ ] Buttons for every (required) letter in the word to click instead of keyboard input.
  * [X] Users who register in a game first can choose wordlist language.
  * [X] Sort userlist based on score.
  * [X] Introduce a bunch of new bugs.
  * [X] Remove games after 12 hours.
  * [X] Logout/Quit.
  * [X] Sort userlist based on score.
  * [X] Kill the bugs.
*/

// PHP Sessions last 2 hours
ini_set('session.gc_maxlifetime', '7200');
session_start();

// Generate a new session id to prevent session hijacking.
// Using boolean option true to delete old session .
session_regenerate_id(true);

// Make code more readable.
function exportData()
{
	file_put_contents('userlist.json', json_encode($GLOBALS['userlist']));
}

function showLeaderboard()
{
	// Sort userlist based on score
	$userlistSorted = $GLOBALS['userlist'][$_SESSION['gamecode']]["users"];
	usort($userlistSorted, function ($a, $b) {
		return $b["score"] - $a["score"];
	});
?>

	<table class="leaderboard" border="1">
		<tr>
			<th>Spiller</th>
			<th>Score</th>
		</tr>

		<?php
		foreach ($userlistSorted as $userdata) {
			echo strtolower($userdata["name"]) == $_SESSION["userid"] ? "<b>" : "";
		?>

			<tr>
				<td><?= $userdata["name"] ?></td>
				<td><?= $userdata["score"] ?></td>
			</tr>

		<?php
			echo strtolower($userdata["name"]) == $_SESSION["userid"] ? "</b>" : "";
		}
		?>

		</tr>
	</table>

<?php
}

// Purge old games
for ($i = 0; $i < $GLOBALS['userlist']; $i++)
	if (time() > $GLOBALS['userlist']["expiration"])
		unset($GLOBALS['userlist'][$i]);

// Get data from json file and decode from json to an associative array.
$userlist = json_decode(file_get_contents("userlist.json"), true);

$regex = [
	"code" => "^[0-9]{1,2}$",
	"lang" => "^[a-z]{2}_[a-z]{2}$",
	"user" => "^[A-ZÆØÅa-zæøå0-9\-_' ]{1,32}$",
	"char" => "^[A-ZÆØÅa-zæøå]{1}$"
];

// Set default value for completed index if it doesn't already exist.
// $_SESSION["completedIndex"] = $_SESSION["completedIndex"] ?? [];

// Check if user has a valid session.
if (isset($_SESSION['gamecode']) && isset($_SESSION['userid'])) {
	if (isset($userlist[$_SESSION["gamecode"]]["users"][$_SESSION["userid"]])) $p = "game";
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
