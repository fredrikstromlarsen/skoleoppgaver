<?php
// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "login")) header("location:../");
function isFirst($g)
{
    if (isset($GLOBALS['db'][$g]["language"]))
        return FALSE;
    else return TRUE;
}

function getName($errorMessage, $first)
{
?>
    <div>
        <button onclick="location.href = 'php/logout.php'">Gå tilbake</button>
        <h1>Hvem er du?</h1>
        <div class='error'><?php echo $errorMessage; ?></div>
        <div class='input-container'>
            <form action='' method='POST'>
                <div class='input-container'>
                    <label for='username'>Jeg heter&nbsp;</label>
                    <input type='text' id='username' name='username' required pattern="<?= $GLOBALS['regex']['user'] ?>" autofocus>
                </div>

                <?php if ($first) {
                    // Get info about all language files.
                    $wlInfo = json_decode(file_get_contents("wordlists/wlinfo.json"), TRUE); ?>
                    <div class="input-container">
                        <p>Du ble førstemann til mølla!</p>
                        <label for="languagePreference">Hvilket språk vil du bruke for dette spillet?</label>
                        <select name="language" id="languagePreference" required>
                            <option value="" disabled selected>Velg språk</option>
                            <?php foreach ($wlInfo as $iso => $lang) { ?>
                                <option value="<?= $iso ?>"><?= $lang['name'] . " (" . $lang['length'] . " ord)" ?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php } ?>
                <input type='submit' value='Start spillet'>
            </form>
        </div>
    </div>
    <div class="col-right">
        <?php if (!$first) showLeaderboard(); ?>
    </div>
<?php }
function getCode($errorMessage)
{ ?>
    <div>
        <div class='input-container'>
            <h1>Bli med i eller start et spill!</h1>
            <div class='error'><?php echo $errorMessage; ?></div>
            <form action='' method='POST'>
                <b><input type='text' name='gamepin' required placeholder='Spillkode' pattern='<?= $GLOBALS['regex']['code'] ?>' autofocus></b>
                <input type='submit' value='Gå videre'>
            </form>
        </div>
    </div>
<?php }

if (isset($_POST['username']) && isset($_SESSION['gamepin'])) {

    // Check if input values matches the expected pattern.
    if (preg_match("/" . $regex["user"] . "/", trim($_POST['username']))) {

        // Escape special characters.
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_SPECIAL_CHARS);
        $userid = strtolower($username);

        // Check if username is avaliable.
        if (is_null($db[$_SESSION['gamepin']]["users"][$userid])) {
            $gamepin = $_SESSION['gamepin'];

            // If user was first in the game they can choose game language.
            if (isFirst($_SESSION['gamepin'])) {

                // Check if language input was set, and matches regex.
                if (isset($_POST['language'])) {
                    echo "<br>Language is set.";
                    if (preg_match("/" . $regex["lang"] . "/", $_POST['language'])) {
                        // Each game lasts for 12 hours.
                        $db[$gamepin]["expiration"] = time() + 43200;

                        // Set game language.
                        $db[$gamepin]["language"] = $_POST['language'];
                    } else getName("Dette språket er ikke et av alternativene :(", TRUE);
                } else getName("Du må velge et språk for dette spillet for å fortsette :/", TRUE);
            }

            // Save username in session variable.
            $_SESSION["userid"] = $userid;

            // Add new user to db.json.
            $db[$gamepin]["users"][$userid] = ["name" => $username, "score" => 0];

            // Set default value for list of words completed.
            $_SESSION["completedWords"] = 0;
            $_SESSION["wrongWords"] = 0;
            $_SESSION["currentWordIndex"] = 0;

            // Update json file with input data.
            exportData();

            // Redirect to front page to validate login session.
            header("location: ./");
        } else getName("En bruker med dette navnet finnes allerede :(", isFirst($_SESSION['gamepin']));
    } else getName("Feltene kan ikke inneholde de spesialtegnene :(", isFirst($_SESSION['gamepin']));
} else if (isset($_POST['gamepin'])) {
    $gamepin = trim($_POST['gamepin']);

    // Make sure the code is 1 to 2 digits (min: 1, max: 99)
    if (preg_match("/" . $regex["code"] . "/", $gamepin)) {

        // Save in session
        $_SESSION["gamepin"] = $gamepin;

        // Get users name and favorite thing
        // Allow players who is first in a game to change language.
        getName("", isFirst($_SESSION['gamepin']));
    } else getCode("Denne koden funker ikke :(");
} else getCode("");
