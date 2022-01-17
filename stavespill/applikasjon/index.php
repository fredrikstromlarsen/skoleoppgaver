<?php
/* NB: If this error pops up when loading the website:
"""
Error: This page is not redirecting properly.
"""
... then make sure to set the appropriate permissions for the json files.

# chmod 644 wordlists/*.json
# chmod 666 wordlists/wlInfo.json
# chmod 644 json/db.json

TODO:
* [ ] Resolve error: "Warning: Undefined array key 1331 in /opt/lampp/htdocs/2_active/skoleoppgaver/stavespill/applikasjon/php/game.php on line 33
* [ ] Show image of favorite thing when user answers correctly with https://rapidapi.com/contextualwebsearch/api/web-search?endpoint=apiendpoint_2799d2c8-3abb-4518-a544-48d2c32d6662.
* [ ] CSS.
* [ ] Allow users to login, not just register.
* [ ] Buttons for every (required) letter in the word to click instead of input fields.
* [X] Implement Speech Synthesis for multilanguage word pronounciation.
* [X] Prevent reload from being interpreted as a fail.
* [X] Fix login json problems.
* [X] Fix new task not changing.
* [X] Users who register in a game first can choose wordlist language.
* [X] Sort userlist based on score.
* [X] Introduce a bunch of new bugs.
* [X] Remove games after 12 hours.
* [X] Logout/Quit.
* [X] Sort userlist based on score.
* [X] Kill the bugs.
*/

// Highest level of error reporting.
// Used for optimizing and debugging code.
declare(strict_types=1);
error_reporting(E_ALL | E_NOTICE);
// PHP Sessions last for 12 hours
ini_set('session.gc_maxlifetime', '43200');
session_start();
// Generate a new session id to prevent session hijacking.
// Using boolean option true to delete old session .
session_regenerate_id(TRUE);
// Make code more readable.
function exportData()
{
	file_put_contents('./json/db.json', json_encode($GLOBALS['db']));
}
function showLeaderboard()
{
	// Sort user list based on score
	$userlistSorted = $GLOBALS['db'][$_SESSION['gamepin']]["users"];
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
			$isMe = "";
			if (isset($_SESSION["userid"]) && strtolower($userdata["name"]) == $_SESSION["userid"]) {
				$isMe = "👉 ";
			}
		?>
			<tr>
				<td><?= $isMe . $userdata["name"] ?></td>
				<td><?= $userdata["score"] ?></td>
			</tr>
		<?php
		}
		?>
		</tr>
	</table>
<?php
}

// Get data from json file and decode from json to an associative array.
$db = json_decode(file_get_contents("./json/db.json"), TRUE);

// Works the same way apt does, users update the db upon logging in.
// OK-ish for small scale apps. Does not scale. 
// Delete games which have expired.
$time = time();
$changed = FALSE;
for ($i = 0; $i < count($db); $i++) {
	$gameid = array_keys($db)[$i];
	if (
		$time > $db[$gameid]["expiration"]
		|| $db[$gameid]["users"] == []
	) {
		unset($db[$gameid]);
		$changed = TRUE;
	}
}
// Reload database
if ($changed) {
	exportData();
	header("Location:./");
}

// Regex patterns for input validation.
$regex = [
	"code" => "^[0-9]{1,2}$",
	"lang" => "^[a-z]{2}$",
	"user" => "^[A-ZÆØÅa-zæøå0-9\-_' ]{1,32}$",
	"char" => "^[A-ZÆØÅa-zæøå]{1}$",
	"mode" => "^(normal|repeat|hard|inherit)$"
];
// Check if user has a valid session.
if (isset($_SESSION['gamepin']) && isset($_SESSION['userid'])) {
	if (isset($db[$_SESSION["gamepin"]]["users"][$_SESSION["userid"]])) $p = "game";
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
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/fonts.css">
	<script src="js/main.js" defer></script>
</head>

<body>
	<?php
	// Load contents of the appropriate php file without redirecting
	echo "<section id='" . $p . "'>";
	include("php/$p.php");
	?>
	</section>
</body>

</html>