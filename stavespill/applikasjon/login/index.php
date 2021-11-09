<?php
$htmlName = '<div class="col-center"><h1>Hva heter du?</h1><div class="input-container"><form action="" method="POST"><input type="text" name="username" required placeholder="Skriv ditt navn her" pattern="[a-zA-Z]{1,}"><input type="submit" value="Start spillet"></form></div></div>';
$htmlCode = '<div class="col-center"><div class="login-container"><h1>Bli med i spillet!</h1><form action="" method="POST"><input type="text" name="gameCode" required placeholder="Skriv koden her" pattern="[0-9]{4}"><input type="submit" value="Gå videre"></form></div></div>';

$path = $_SERVER['REQUEST_URI'];

var_dump($_COOKIE['username'], $_COOKIE['gameCode'], $path);


if (isset($_COOKIE['username']) && isset($_COOKIE['gameCode'])) {


    // Prevent SQL Injection
    $un = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);

    // Cookie varighet: 1 time
    $cookieExpiry = time() + 3600;
    setcookie("username", $un, $cookieExpiry, $path);


} else if (isset($_POST['gameCode'])) {

    // Prevent SQL Injection
    $gc = filter_input(INPUT_POST, "gameCode", FILTER_SANITIZE_NUMBER_INT);
    if (preg_match("/\d{4}/", $gc)) {

        // Cookie varighet: 1 time
        $cookieExpiry = time() + 3600;
        setcookie("gameCode", $gc, $cookieExpiry, $path);
        echo $htmlName;
    } else {
        echo $htmlCode;
    }
?>
<?php
} else {
    echo $htmlCode;
?>
<?php
}
?>