<?php
// Reset data
$json = json_encode([]);
file_put_contents("../data/stats.json", $json);
$playerScore = 0;
$machineScore = 0;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Stein Sax Papir</title>
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <header>
        <h2>Deg</h2>
        <div class="score">
            <p class=""><span id="playerScore"><?= $playerScore ?></span>&nbsp;-&nbsp;<span id="machineScore"><?= $machineScore ?></span></p>
        </div>
        <h2>Maskin</h2>
    </header>
    <main>
        <div class="player">
            <div value="rock" class="clickable">
                <img src="img/player/rock.png" alt="Stein" />
            </div>
            <div value="scissor" class="clickable">
                <img src="img/player/scissor.png" alt="Saks" />
            </div>
            <div value="paper" class="clickable">
                <img src="img/player/paper.png" alt="Papir" />
            </div>
        </div>
        <div id="result">
        </div>
        <div class="machine">
            <?php
            ?>
            <div>
                <img src="img/machine/rock.png" alt="Stein" />
            </div>
            <div>
                <img src="img/machine/scissor.png" alt="Saks" />
            </div>
            <div>
                <img src="img/machine/paper.png" alt="Papir" />
            </div>
        </div>
    </main>

    </div>
</body>

</html>