<?php
if (str_contains(str_replace("/", " ", $_SERVER['REQUEST_URI']), "login")) header("location:../");
echo "<h1>login/index.php</h1>";
function getName($errorMessage)
{
?>
    <div class='col-center'>
        <div class='error'><?php echo $errorMessage; ?></div>
        <h1>Hvem er du?</h1>
        <div class='input-container'>
            <form action='' method='POST'>
                <div class='username-container'>
                    <b><label for='username'>Jeg heter&nbsp;</label></b>
                    <input type='text' id='username' name='username' required pattern="[A-Za-z0-9ÆØÅæøå\-_\' ]" {1,32}'>
                </div>
                <div class='fav-container'>
                    <b><label for='fav'>Jeg liker&nbsp;</label></b>
                    <input type='text' id='fav' name='favorite' required pattern="[A-Za-zÆØÅæøå\-_\' ]{1,32}">
                </div>
                <input type='submit' value='Start spillet'>
            </form>
        </div>
    </div>
<?php
}
function getCode($errorMessage)
{
?>
    <div class='col-center'>
        <div class='error'><?php echo $errorMessage; ?></div>
        <div class='login-container'>
            <h1>Bli med i spillet!</h1>
            <form action='' method='POST'>
                <b><input type='text' name='code' required placeholder='Skriv koden her' pattern='[0-9]{1,2}'></b>
                <input type='submit' value='Gå videre'>
            </form>
        </div>
    </div>
<?php
}

// Cookie varer i 1 år, men gjelder kun på denne siden
$cookieOptions = ['expires' => time() + 3600 * 24 * 365, 'path' => $_SERVER['REQUEST_URI'], 'samesite' => 'Lax'];

if (
    isset($_POST['username']) &&
    isset($_POST['favorite']) &&
    isset($_COOKIE['code'])
) {
    // Check if input fields matches the expected pattern
    if (
        preg_match("/^[a-zæøå0-9\-_' ]{1,32}$/i", trim($_POST['username'])) &&
        preg_match("/^[a-zæøå\-_' ]{1,32}$/i", trim($_POST['favorite']))
    ) {
        // Escape special characters
        $un = filter_var(trim($_POST['username']), FILTER_SANITIZE_SPECIAL_CHARS);
        $fav = filter_var(trim($_POST['favorite']), FILTER_SANITIZE_SPECIAL_CHARS);

        // Check if username is avaliable
        $exists=FALSE;
        for ($i = 0; $i < count($userlist[$_COOKIE['code']]); $i++) $exists = $userlist[$_COOKIE['code']][$i]["name"] == $un ? TRUE:$exists;
        if (!$exists) {
            setcookie("username", $un, $cookieOptions);

            // Update Jason on the latest news
            $nextIndex = count($userlist[$_COOKIE['code']]);
            $userlist[$_COOKIE['code']] += [$nextIndex => ["name" => $un, "score" => 0, "favorite" => $fav]];
            file_put_contents("userlist.json", json_encode($userlist));

            // Redirect to proper page
            header("location: ./");
        } else getName("En bruker med dette navnet finnes allerede :(");
    } else getName("Feltene kan bare inneholde bokstaver, tall og mellomrom :(");
} else if (isset($_POST['code'])) {
    $code = trim($_POST['code']);

    // Make sure the code is 1-2 digits
    if (preg_match("/^[0-9]{1,2}$/", $code)) {

        // Check if the game with this id exists
        if (!array_key_exists($code, $userlist));
        else {
            setcookie("code", $code, $cookieOptions);
            getName("");
        }
    } else getCode("Denne koden funker ikke :(");
} else getCode("");
