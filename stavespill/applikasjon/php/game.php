<?php
print_r($_SESSION);

// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "game")) header("location:../");

$wordlistLanguage = $db[$_SESSION['gamepin']]["language"];
$wordlist = json_decode(file_get_contents("wordlists/$wordlistLanguage/1k.json"), true);

$activeMethod = "standard";
function createTask($createNew, $method)
{
    // Use previous method if input is inherit.
    // Save method in global variable if it's not "inherit".
    if ($method == "inherit") $method = $GLOBALS['activeMethod'];
    else $GLOBALS['activeMethod'] = $method;

    if ($createNew) $_SESSION['currentWordIndex']++;

    switch ($method) {
        case "normal":
            $index = $_SESSION["currentWordIndex"];
            break;

        case "repeat":
            // Get random number from completedWordsIndex-array.
            $index = $_SESSION["completedWordsIndex"][rand(0, count($_SESSION["completedWordsIndex"]))];
            break;

        case "hard":
            // Get random number from wrongWordsIndex-array.
            $index = $_SESSION["wrongWordsIndex"][rand(0, count($_SESSION["wrongWordsIndex"]))];
            break;
    }
    $currentWord = $GLOBALS["wordlist"][$index];
?>

    <form action="" method="POST" class="task">
        <div class="audioContainer">
            <!-- Temporary. Will be replaced by audio button later. -->
            <button type="button" id="audio"><?= $currentWord ?></button>
        </div>
        <div class="charArray">

            <?php
            for ($i = 0; $i < mb_strlen($currentWord); $i++) {
                $inputOptions = "class='char' type='text' name='charInput[]' id='charInput$i' pattern='" . $GLOBALS["regex"]["char"] . "' autocomplete='off' required='true'";
                if ($i == 0) $inputOptions .= " autofocus='true'";
            ?>
                <input <?= $inputOptions ?>>
            <?php
            }
            ?>

        </div>
        <input type="submit" class="answer" value="Sjekk svar" disabled="true">
    </form>

<?php
}

function showResult($res)
{
    createTask(TRUE, "normal");
}

// Set game mode using buttons as input. 
// Make sure the input hasn't been tampered with using preg_match.
if (isset($_POST['mode']) && preg_match($regex["mode"], $_POST['mode']))
    $activeMethod = $_POST['mode'];
?>
<div>
    <button tabindex="-1" type="button" id="exit">Avslutt Spill</button>
    <form action="" method="POST">
        <button name="mode" value="repeat" title="Gå gjennom oppgaver du allerede har klart.">Repetisjon</button>
        <button name="mode" value="normal" title="Gå gjennom alle oppgavene bare èn gang.">Normal</button>
        <button name="mode" value="hard" title="Gå gjennom oppgaver du har prøvd, men ikke klart.">Vanskelig</button>
    </form>
</div>
<div>

    <?php
    if (isset($_POST["charInput"])) {
        foreach ($_POST["charInput"] as $c) {
            if (!preg_match("/" . $GLOBALS["regex"]["char"] . "/", $c)) {
                createTask(FALSE, "inherit");
            }
        }
        $input = implode("", $_POST["charInput"]);
        $currentScore = $GLOBALS['db'][$_SESSION['gamepin']]["users"][$_SESSION['userid']]["score"];
        $currentWord = $GLOBALS['wordlist'][$_SESSION["currentWordIndex"]];

        if (trim(strtolower($input)) == strtolower($currentWord)) {
            if (!in_array($_SESSION["currentWordIndex"], $_SESSION['completedWordsIndex'])) $_SESSION['completedWordsIndex'][] = $_SESSION['currentWordIndex'];
            $newScore = $currentScore + 10;
            showResult(TRUE);
        } else {
            if (!in_array($_SESSION["currentWordIndex"], $_SESSION['wrongWordsIndex'])) $_SESSION['wrongWordsIndex'][] = $_SESSION['currentWordIndex'];
            $newScore = $currentScore < 2 ? 0 : $currentScore - 1;
            showResult(FALSE);
        }

        // ZZ
        $GLOBALS['db'][$_SESSION['gamepin']]["users"][$_SESSION['userid']]["score"] = $newScore;
        exportData();
    } else createTask(FALSE, "normal");
    ?>

</div>
<div>

    <?php
    showLeaderboard();
    ?>

</div>