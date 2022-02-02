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
}

// Execute function respective to user input.
if (isset($_POST["function"])) $_POST["function"]();
?>


<form action="" method="POST">
    <label for="function">Hvilken funksjon skal kjøres?</label>
    <select name="function" id="function">
        <option value="generateInfo">generateInfo()</option>
    </select>
    <input type="submit" value="Kjør">
</form>