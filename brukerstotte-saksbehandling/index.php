<?php

    // Set $timestamp variable to the current time in hours:minutes
    $timestamp = date("H:i");

    

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bedrift AS</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/main.js"></script>
    <script src="https://kit.fontawesome.com/4a2b7708f8.js" crossorigin="anonymous"></script>
</head>

<body>
    <div class="chat-window">
        <div class="chat-header">
            <div class="chat-header-title">
                <h1>Bedrift AS</h1>
            </div>
            <div class="chat-header-buttons">
                <!-- <button class="chat-header-button">
                    <i class="fa-solid fa-gear"></i>
                </button> -->
                <button class="chat-header-button">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
        <div class="chat-body">
            <div class="chat-body-messages">
                <div class="chat-body-message">
                    <div class="chat-body-message-text">
                        <p>Hei, hva kan vi hjelpe deg med?</p>
                    </div>
                    <div class="chat-body-message-info">
                        <p><?= $timestamp ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>