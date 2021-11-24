<?php
include("db.php");
$errorMessage = '';
$getName = "<div class='col-center'><h1>Hva heter du?</h1><div class='input-container'><form action='' method='POST'><input type='text' name='username' required placeholder='Skriv ditt navn her' pattern='[A-Za-z0-9]{1,32}'><input type='submit' value='Start spillet'></form></div></div>";
$getCode = "<div class='col-center'><div class='error'>$errorMessage</div><div class='login-container'><h1>Bli med i spillet!</h1><form action='' method='POST'><input type='text' name='code' required placeholder='Skriv koden her' pattern='[0-9]{4}'><input type='submit' value='Gå videre'></form></div></div>";
$cookieOptions = ['expires' => time() + 3600, 'path' => $_SERVER['REQUEST_URI'], 'samesite' => 'Lax'];
if (isset($_COOKIE['id'])) {
    include("game/index.php");
} else if (isset($_POST['username']) && isset($_COOKIE['code'])) {
    echo "username=" . $_POST['username'] . "<br>code=" . $_COOKIE['code'];

    // Remove special characters and whitespace and start+end of string
    $un = filter_var(trim($_POST['username']), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_HIGH);
    if ($un == $_POST['username'] && preg_match("/^[A-Za-z0-9 ]{1,32}$/", $un)) {
        // Check if username is taken
        if ($con->query("SELECT * FROM session WHERE name='$un'") == "") {
            // Set user id
            setcookie("id", base64_encode($_COOKIE['code'] . "." . $un), $cookieOptions);
            setcookie("username", $un, $cookieOptions);
            $con->query("INSERT INTO session (name) VALUES ('" . $un . "')");
        } else {
            echo $getName;
        }
        print_r($con->query("SELECT * FROM session WHERE name='$un'"));
    } else {
        echo $getName;
    }
} else if (isset($_POST['code'])) {
    // Remove whitespace from start and end of variable
    $code = trim($_POST['code']);
    // Make sure the code is just 4 digits
    if (preg_match("/^[0-9]{4}$/", $code)) {
        setcookie("code", $code, $cookieOptions);
        echo $getName;
    } else {
        $errorMessage = "Invalid Code";
        echo $getCode;
    }
} else {
    echo $getCode;
}
