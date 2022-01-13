<?php

// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "game")) header("location:../");

$wordlistLanguage = $userlist[$_SESSION['gamecode']]["language"];
$wordlist = json_decode(file_get_contents("wordlists/$wordlistLanguage.json"), true)["1000"];
echo "Completed words index: <br>" .  print_r($_SESSION["completedWordsindex"], true);

function newTask()
{
    $i = 0;
    while ($i < count($GLOBALS['wordlist'])) {
        if (!in_array($i, $_SESSION["completedWordsIndex"])) break;
        $i++;
    }
    $_SESSION['currentWordIndex'] = $i;
    $currentWord = $GLOBALS["wordlist"][$i];
    echo "$i: $currentWord";
?>

    <form action="" method="POST" class="task">
        <div class="audioContainer">
            <button type="button" onclick="javascript.void()">Lydikon</button>
        </div>
        <div class="charArray">

            <?php
            for ($i = 0; $i < strlen($currentWord); $i++) {
                $inputOptions = "class='char' type='text' name='charInput[]' pattern='" . $GLOBALS["regex"]["char"] . "' autocomplete='off' required='true'";
                if ($i == 0) $inputOptions .= " autofocus='true'";
                echo "<input $inputOptions>";
            }
            ?>

        </div>
        <input type="submit" class="answer" value="Sjekk svar" disabled="true">
    </form>

<?php
}


function showFavorite()
{

    newTask();
}

function showFail()
{
    newTask();
}
?>

<div class="col-left">
    <button onclick="location.href = 'logout/index.php'">Logg ut</button>
</div>
<div class="col-center">

    <?php
    if (isset($_POST["charInput"])) {
        $input = implode("", $_POST["charInput"]);
        $currentScore = $GLOBALS['userlist'][$_SESSION['gamecode']]["users"][$_SESSION['userid']]["score"];
        $currentWord = $GLOBALS['wordlist'][$_SESSION["currentWordIndex"]];

        if (trim(strtolower($input)) == strtolower($currentWord)) {
            $_SESSION['completedWordsIndex'][] = $currentWord;
            $newScore = $currentScore + 10;
            showFavorite();
        } else {
            $_SESSION['wrongWordsIndex'][] = $currentWord;
            $newScore = $currentScore < 2 ? 0 : $currentScore - 1;
            showFail();
        }

        // ZZ
        $GLOBALS['userlist'][$_SESSION['gamecode']]["users"][$_SESSION['userid']]["score"] = $newScore;
        exportData();
    } else newTask();
    ?>

</div>
<div class="col-right">

    <?php
    showLeaderboard();
    ?>

</div>