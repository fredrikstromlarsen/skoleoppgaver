<?php
// Redirect to homepage if user tries to open this page directly
if (str_contains($_SERVER['REQUEST_URI'], "login")) header("location:../");

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
                <b><input type='text' name='gamecode' required placeholder='Skriv koden her' pattern='[0-9]{1,2}'></b>
                <input type='submit' value='Gå videre'>
            </form>
        </div>
    </div>
<?php
}

if (
    isset($_POST['username']) &&
    isset($_POST['favorite']) &&
    isset($_SESSION['gamecode'])
) {
    // Check if input fields matches the expected pattern (and doesn't include nasty unicode characters)
    if (
        preg_match("/^[A-ZÆØÅa-zæøå0-9\-_' ]{1,32}$/i", trim($_POST['username'])) &&
        preg_match("/^[A-ZÆØÅa-zæøå\-_' ]{1,32}$/i", trim($_POST['favorite']))
    ) {
        // Escape special characters
        $username = filter_var(trim($_POST['username']), FILTER_SANITIZE_SPECIAL_CHARS);
        $fav = filter_var(trim($_POST['favorite']), FILTER_SANITIZE_SPECIAL_CHARS);

        // Check if username is avaliable
        if (!isset($userlist[$_SESSION['gamecode']]['username'])) {

            // Save username in session variable
            $_SESSION["username"] = $un;

            // Add new user to userlist.json
            $userlist[$_SESSION['gamecode']][$username] = ["name" => $username, "score" => 0, "favorite" => $fav];
            file_put_contents("userlist.json", json_encode($userlist));

            // Redirect to proper page
            header("location: ./");
        } else getName("En bruker med dette navnet finnes allerede :(");
    } else getName("Feltene kan ikke inneholde de spesialtegnene :(");
} else if (isset($_POST['gamecode'])) {
    $gamecode = trim($_POST['gamecode']);

    // Make sure the code is 1 to 2 digits (min: 1, max: 99)
    if (preg_match("/^[0-9]{1,2}$/", $gamecode)) {

        // Check if the game with this id exists
        $_SESSION["gamecode"] = $gamecode;
        // if (!isset($userlist[$gamecode])) {
        // $userlist += $gamecode;
        // file_put_contents("userlist.json", json_encode($userlist));
        // }
        // After game id is found or created, get the users name and favorite.
        getName("");
    } else getCode("Denne koden funker ikke :(");
} else getCode("");
