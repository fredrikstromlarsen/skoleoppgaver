<?php
// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "game")) header("location:../");

// Get wordlist contents/info
$wordlist = json_decode(file_get_contents("wordlists/" . $db[$_SESSION['gamepin']]["language"] . ".json"), true)["words"];
$wordlistLength = json_decode(file_get_contents("wordlists/wlinfo.json"), TRUE)[$db[$_SESSION['gamepin']]["language"]]["length"];

function createTask($createNew, $method)
{
    // Make sure POST data hasn't been tampered with.
    if (preg_match("/" . $GLOBALS['regex']["mode"] . "/", $method)) $method = "normal";

    // Use previous method if input is inherit.
    // Save method in global variable if it's not "inherit".
    echo "Variable: $method<br>Session: " . $_SESSION['activeMethod'] . "<br>";
    if ($method == "inherit") $method = $_SESSION['activeMethod'];
    else $_SESSION['activeMethod'] = $method;

    if ($createNew) $_SESSION['currentWordIndex']++;

    switch ($method) {
        case "normal":
            // Get random index from non-completed word index.
            // Not using as a session variable because with the english wordlist of 
            // 451k words, the session-file would be inconveniently large and prevent 
            // the application from scaling.

            // 1. Marge completedWordsIndex and wrongWordsIndex to a single array.
            // 2. Get all index values from wordlist (0, 1, 2, 3, 4, 5, 6, ...).
            // 3. Remove all values from ^ which exist in the merged array.
            $unseenWords = array_diff(array_keys($GLOBALS['wordlist']), array_merge($_SESSION['completedWordsIndex'], $_SESSION['wrongWordsIndex']));
            $index = $unseenWords[rand(0, count($unseenWords) - 1)];
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
    echo "Word: " . $GLOBALS['wordlist'][$index] . "<br>Index: $index<br>Session Stored Mode: " . $_SESSION['activeMethod'] . "<br>Mode: $method";
    $currentWord = $GLOBALS["wordlist"][$index];
    $_SESSION['currentWordIndex'] = $index;
?>

    <form action="" method="POST" class="task">
        <div class="audioContainer">
            <!-- Temporary. Will be replaced by audio button later. -->
            <button type="button" id="audioNormal"></button>
            <button type="button" id="audioSlow">🐢</button>
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
        <input type="submit" class="answer" value="Svar" disabled="true">
    </form>

    <?php
}

function showResult($res)
{
    if ($res) {
        $imgUrl = "";
    ?>
        <div class="result-container result-correct">
            <h1>Wow! Det var riktig!</h1>
            <img src="<?= $imgUrl ?>" alt="<?= $_SESSION["favorite"] ?>">
            <form action=""><button type="submit" name="nextWord">Neste ord</button></form>
        </div>
    <?php
    } else {
        // Show image with 
        $imgUrl = "";
    ?>
        <div class="result-container result-wrong">
            <h1>Det var dessverre feil.</h1>
            <img src="<?= $imgUrl ?>" alt="Bilde for motivasjon">
            <form action=""><button type="submit" name="nextWord">Neste ord</button></form>
        </div>

<?php
    }
}

// Set game mode using buttons as input. 
// Make sure the input hasn't been tampered with using preg_match.
if (isset($_POST['mode'])) { // && preg_match($regex["mode"], $_POST['mode'])) {
    $_SESSION['activeMethod'] = $_POST['mode'];
    header("Location:./");
}

if (isset($_POST['nextWord'])) createTask(TRUE, "inherit");


?>
<div class="top-bar space-between">
    <button tabindex="-1" type="button" id="exit">Avslutt Spill <span class="icon"></span></button>
    <p class="space-between"><span><?= count($_SESSION['completedWordsIndex']) ?> <span class="icon"></span></span><span><?= count($_SESSION['wrongWordsIndex']) ?> <span class="icon"></span></span></p>
    <form action="" method="POST" oninput="this.submit()">
        <label for=" modeChange">Spillmodus</label>
        <select name="mode" id="modeChange">
            <option value="repeat" <?php echo $_SESSION["activeMethod"] == "repeat" ? "selected" : ""; ?>>Repetisjon</option>
            <option value="normal" <?php echo $_SESSION["activeMethod"] == "normal" ? "selected" : ""; ?>>Normal</option>
            <option value="hard" <?php echo $_SESSION["activeMethod"] == "hard" ? "selected" : ""; ?>>Vanskelig</option>
        </select>
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
        header("location:./");
    } else createTask(FALSE, "inherit");
    ?>

</div>
<div>

    <?php
    showLeaderboard();
    ?>

</div>
<?php
print_r($_SESSION);
