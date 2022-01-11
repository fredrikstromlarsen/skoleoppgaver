<?php
// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "game")) header("location:../");

include("./leaderboard/index.php");

function gameFinished($s)
{
    switch ($s) {
        case 0:
            $msg = "Man lærer ofte mye av å prøve og feile!";
            break;
        case 1:
            $msg = "Én riktig er bedre enn ingenting!";
            break;
        case 2:
            $msg = "";
            break;
        case 3:
            $msg = "";
            break;
        case 4:
            $msg = "";
            break;
        case 5:
            $msg = "";
            break;
        case 6:
            $msg = "";
            break;
        case 7:
            $msg = "";
            break;
        case 8:
            $msg = "";
            break;
        case 9:
            $msg = "";
            break;
        case 10:
            $msg = "";
            break;
    }
    // 7 correct answers == 70 points
    $newScore = $GLOBALS['userlist'][$_SESSION['username']]["score"] = $GLOBALS['userlist'][$_SESSION['username']]["score"] + $s * 10;


?>
    <h1><?= $msg ?></h1>


<?php
}
function newTask()
{
    $GLOBALS['tasknum']++;
    $_SESSION['wordnum']++;
    $_SESSION['currentWord'] = $_SESSION['pendingWords'][0];
?>
    <form action="" method="post" class="task">
        <div class="charArray">
            <?php
            print_r($_SESSION['currentWord']);
            // for ($i = 0; $i < strlen($_SESSION['currentWord']); $i++) {
            $inputOptions = "class='char' type='text' name='input[]' pattern='/^[A-ZÆØÅa-zæøå]$/' autocomplete='off' required='true'";
            // if ($i = 0) $inputOptions .= " autofocus='true'";
            ?>
            <input <?= $inputOptions ?>>
            <?php //} 
            ?>
        </div>
        <input type="submit" class="answer" value="Sjekk svar" disabled="true">
    </form>
<?php
}
function correctAnswer()
{
}

function wrongAnswer()
{
}


?>
<h1>php/game.php</h1>
<div class="col-center">
    <?php

    // Set $tasknum to value stored in session if it exists
    $tasknum = isset($_SESSION['tasknum']) ? $_SESSION['tasknum'] : 1;

    if (isset($_POST["answerInput"])) {
        $input = implode(",", $_POST["answerInput"]);
        $_SESSION["pendingWords"] = array_splice($_SESSION["pendingWords"], 0, 1);
        if ($input == $currentWord) {
            $_SESSION['completedWords'][] = $currentWord;
            correctAnswer();
        } else {
            $_SESSION['wrongWords'][] = $currentWord;
            wrongAnswer();
        }
    }
    // If game is done
    if ($tasknum == 10) gameFinished($score);
    else newTask();

    ?>
</div>
<div class="col-right">
    <?php // showLeaderboard(); 
    ?>
</div>