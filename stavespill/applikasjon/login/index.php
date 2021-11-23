<?php
$getName = '<div class="col-center"><h1>Hva heter du?</h1><div class="input-container"><form action="" method="POST"><input type="text" name="username" required placeholder="Skriv ditt navn her" pattern="[a-zA-Z]{1,}"><input type="submit" value="Start spillet"></form></div></div>';
$getCode = '<div class="col-center"><div class="login-container"><h1>Bli med i spillet!</h1><form action="" method="POST"><input type="text" name="code" required placeholder="Skriv koden her" pattern="[0-9]{4}"><input type="submit" value="Gå videre"></form></div></div>';
$path = $_SERVER['REQUEST_URI'];
if (
    (filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING)
    || isset($_COOKIE['username']))
    && isset($_COOKIE['code'])
) {
    // Prevent SQL Injection
    $un = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    // Check if username is taken

    // Set user session id
    $_SESSION["id"] = base64_encode($_COOKIE['code'] . "." . $un);


    // Cookie varighet: 1 time
    $cookieExpiry = time() + 3600;

    setcookie("username", $un, $cookieExpiry, $path);
    // setcookie("session", $sessionID, $cookieExpiry, $path);

    var_dump($_COOKIE['code']);
    var_dump($un);
    var_dump($_SESSION["id"]);

} else if (filter_input(INPUT_POST, "code", FILTER_SANITIZE_NUMBER_INT)) {
    // Prevent SQL Injection
    $gc = filter_input(INPUT_POST, "code", FILTER_SANITIZE_NUMBER_INT);
    if (preg_match("/\d{4}/", $gc)) {
        // Cookie varighet: 1 time
        $cookieExpiry = time() + 3600;
        setcookie("code", $gc, $cookieExpiry, $path);
        echo $getName;
    } else {
        echo $getCode;
    }
?>
<?php
} else {
    echo $getCode;
?>
<?php
}
?>