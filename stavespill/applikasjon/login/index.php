<?php
include("db.php");
function getName($errorMessage) {
    ?>
    <div class='col-center'>
        <div class='error'><?php echo $errorMessage; ?></div>
        <h1>Hvem er du?</h1>
        <div class='input-container'>
            <form action='' method='POST'>
                <div class='username-container'>
                    <label for='username'>Jeg heter&nbsp;</label>
                    <input type='text' id='username' name='username' required pattern='[A-Za-z0-9ÆØÅæøå ]{1,32}'>
                </div>
                <div class='fav-container'>
                    <label for='fav'>Jeg liker&nbsp;</label>
                    <input type='text' id='fav' name='favorite' required pattern='[A-Za-zÆØÅæøå ]{1,32}'>
                </div>
                <input type='submit' value='Start spillet'>
            </form>
        </div>
    </div>
    <?php
}
function getCode($errorMessage) {
    ?>
    <div class='col-center'>
        <div class='error'><?php echo $errorMessage; ?></div>
        <div class='login-container'>
            <h1>Bli med i spillet!</h1>
            <form action='' method='POST'>
                <input type='text' name='code' required placeholder='Skriv koden her' pattern='[0-9]{1,2}'>
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
    echo "username=" . $_POST['username'] . "<br>favorite=" . $_POST['favorite'] . "<br>code=" . $_COOKIE['code'];

    // Remove special characters and whitespace at start+end of string
    $un = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    $fav = filter_var(trim($_POST['favorite']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);

    if (
        $un == $_POST['username'] && preg_match("/^[A-Za-z0-9 ]{1,32}$/", $un) &&
        $fav == $_POST['favorite'] && preg_match("/^[A-Za-z ]{1,32}$/", $fav)
    ) {

        // Check if username is taken
        $tmp = $con->query("SELECT * FROM user WHERE name='$un'");
        if ($tmp->num_rows==0) {
            // Husk navnet til senere
            setcookie("username", $un, $cookieOptions);
            $con->query("INSERT INTO user (name, favorite) VALUES ('" . $un . "', '" . $fav . "')");

            header("location: ./");
        } else {
            getName("En bruker med dette navnet finnes allerede :( ");
        }

    } else {
        getName("Feltene kan bare inneholde bokstaver, tall og mellomrom :(");
    }
} else if (isset($_POST['code'])) {
    // Remove whitespace from start and end of variable
    $code = trim($_POST['code']);
    // Make sure the code is just 4 digits
    if (preg_match("/^[0-9]{1,2}$/", $code)) {
        setcookie("code", $code, $cookieOptions);
        getName("");
    } else {
        getCode("Denne koden funger ikke :(");
    }
} else {
    getCode("");
}
$con->close();
