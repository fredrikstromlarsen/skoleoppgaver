<?php
function generateInfo()
{
    $a = "../wordlists/";
    $b = glob("$a*.json");
    $c = [];
    foreach ($b as $d)
        if ($d != $a . "wlinfo.json") {
            $e = json_decode(file_get_contents($d), TRUE);
            $c = number_format(count($e["words"]), 0, '', ' ');
            $f[substr(str_replace($a, "", $d), 0, 2)] = ["length" => $c, "name" => ucfirst(substr($e["name"] . "_", 0, strpos($e["name"] . "_", "_")))];
        }
    file_put_contents("../wordlists/wlinfo.json", json_encode($f));
    header("Content-Type: application/json");
    echo json_encode($f);
}
function scrambleWordlistOrder()
{
    $wordlists = glob("../wordlists/*.json");
    print_r($wordlists);

    // Remove junk.
    foreach ($wordlists as $key => $wordlist) {
        if ($wordlist == "../wordlists/wlinfo.json") unset($wordlists[$key]);
        else $wordlists[$key] = substr($wordlist, 13, 2);
    }

    echo "<br><br>" . print_r($wordlists, TRUE);

    // Loop over each wordlist.json file.
    for ($i = 0; $i < count($wordlists); $i++) {
        $key = array_keys($wordlists)[$i];
        echo "<br><br>key: $key";
        $wordlistData = json_decode(file_get_contents("../wordlists/" . $wordlists[$key] . ".json"));
        echo "<br><br>" . print_r($wordlistData["words"]);
        $wordsArray = $wordlistData["words"];
        echo "<br><br>wordsArray: " . print_r($wordsArray);
        $keysAvailable = array_keys($wordsArray);
        $newWordsArray = [];

        echo "<br><br>key: $key<br>keysAvailable: " . print_r($keysAvailable, TRUE);
        die();

        // Loop over every word in wordlist.json file.
        for ($j = 0; $j < count($wordsArray); $j++) {
            /* 
            // Psuedo-code:
         _______
        |       \
        |      \|/
        |       
        |    wordsArray = ["Hallo", "How", "The", "It]
        |    keysAvailable = [0,1,2,3]
        |    randomIndex = keysAvailable[3] = 2
        |    newWordsArray[2] = $wordsArray[0] = Hallo
        |      ||
        |    newWordsArray = [-, -, "Hallo", -]
        |    keysAvailable = [0,1,3]
        |      |||
        |    newWordsArray = ["How", -, "Hello", -]
        |    keysAvailable = [1,3]
        |      
        |      |
        \_____/
            
            */
            $randomIndex = $keysAvailable[rand(0, count($keysAvailable))];
            $newWordsArray[$randomIndex] = $wordsArray[$j];
            unset($keysAvailable[$randomIndex]);
        }
        $wordlistData["words"] = $newWordsArray;
        file_put_contents("../wordlists/$key.json", json_encode($wordlistData));
        echo "[OK] $key<br>";
    }
    echo "[COMPLETED] All tasks finished.";
}


// Execute function respective to user input.
if ($_POST["function"]) $_POST["function"]();
?>


<form action="" method="POST">
    <label for="function">Hvilken funksjon skal kjøres?</label>
    <select name="function" id="function">
        <option value="generateInfo">generateInfo()</option>
        <option value="scrambleWordlistOrder">scrambleWordlistOrder()</option>
    </select>
    <input type="submit" value="Kjør">
</form>