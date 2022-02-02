<?php
// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "game")) header("location:../");

// Get wordlist contents/info
$wordlist = json_decode(file_get_contents("wordlists/" . $db[$_SESSION['gamepin']]["language"] . ".json"), true)["words"];
$wordlistInfo = json_decode(file_get_contents("wordlists/wlinfo.json"), TRUE)[$db[$_SESSION['gamepin']]['language']];

function createTask($createNew, $responseAction)
{
    if ($responseAction == 0 && isset($_SESSION["responseAction"])) $responseAction = $_SESSION["responseAction"];
    else $_SESSION["responseAction"] = $responseAction;

    if ($createNew) $_SESSION['currentWordIndex']++;
    $language = $GLOBALS["db"][$_SESSION['gamepin']]['language'];
    $currentWord = $GLOBALS["wordlist"][$_SESSION['currentWordIndex']];
    $path = downloadSynthesizedAudio($currentWord, $language);
?>
    <div class="audioContainer">
        <audio id="synthesizedAudio" type="audio/mpeg" src="<?= $path ?>"></audio>
        <button type="button" id="audioTrigger"><img src="img/audio.svg" alt="Lydikon"></button>
        <audio src="audio/response/<?= $responseAction ?>.mp3" type="audio/mpeg" id="response"></audio>
    </div>
    <form action="" method="POST" class="task">
        <input class='answer' type='text' name='answer' id='answer' autofocus="true" autocomplete="off">
        <!-- <button type="button" name="skip" value=>Hopp over</button> -->
        <input type="submit" class="answer" value="Svar">
    </form>
<?php
}
function downloadSynthesizedAudio($word, $lang)
{
    $path = "audio/$lang";

    // https://php.watch/articles/php-hash-benchmark
    $file = "$path/" . hash("xxh3", $word) . ".mp3";
    $urlsafeWord = urlencode($word);
    if (!file_exists($path) || !is_dir($path)) mkdir($path, 0777);
    if (!file_exists($file)) {
        $url = "https://translate.google.com/translate_tts?ie=UTF-8&client=tw-ob&q=$urlsafeWord&tl=$lang";
        $audio = file_get_contents($url);
        file_put_contents($file, $audio);
    }
    return $file;
}
?>
<header class="bar">
    <p><b><?= $db[$_SESSION['gamepin']]["users"][$_SESSION["userid"]]["name"] ?></b></p>
    <p class="counter">
        <span><?= $_SESSION['completedWords'] ?>&nbsp;<img src="img/correct.svg" alt="riktige" class="icon i-inline"></span>
        <span><?= $_SESSION['wrongWords'] ?>&nbsp;<img src="img/wrong.svg" alt="riktige" class="icon i-inline"></span>
    </p>
    <button tabindex="-1" type="button" id="exit">Avslutt Spill <img src="img/exit.svg" alt="avslutt spillet" class="icon i-inline"></button>
</header>
<div class="playarea">
    <div id="question">

        <?php if (isset($_POST["answer"])) {

            $input = base64_encode(strtolower(trim($_POST["answer"])));
            $currentScore = $GLOBALS['db'][$_SESSION['gamepin']]["users"][$_SESSION['userid']]["score"];
            $currentWord = $GLOBALS['wordlist'][$_SESSION["currentWordIndex"]];

            if ($input === base64_encode(strtolower($currentWord))) {
                $_SESSION["completedWords"]++;
                createTask(TRUE, 1);
            } else {
                $_SESSION["wrongWords"]++;
                createTask(FALSE, 2);
            }
            $GLOBALS['db'][$_SESSION['gamepin']]["users"][$_SESSION['userid']]["score"] = $_SESSION["completedWords"] * 10 - $_SESSION["wrongWords"];
            exportData();
            header("Location:./");
        } else createTask(FALSE, 0); ?>
    </div>
    <div id="scoreboard">
        <?php scoreboard(); ?>
    </div>
</div>
<footer class="bar bar-slim">
    <p>Spillkode: <b><?= $_SESSION["gamepin"] ?></b></p>
    <p><?= $wordlistInfo["name"] ?></p>
    <p>ord <b><?= $_SESSION["completedWords"] + 1?></b> av <?= $wordlistInfo["length"] ?></p>
</footer>