<?php

/*/
/* [0] = step
/* [1] = index in step
/* [2] = answer type (0 = buttons, 1 = text)
/*/
$questions = json_decode(file_get_contents("json/questions.json"));

/*/
/* [0] = step
/* [1] = index in step
/*/
$answers = json_decode(file_get_contents("json/answers.json"));


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bedrift AS</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/main.js" async></script>
    <script src="https://kit.fontawesome.com/4a2b7708f8.js" crossorigin="anonymous" async></script>
</head>

<body>
    <div class="chat-window">
        <div class="chat-header">
            <div class="chat-header-title">
                <h1>Chat med oss!</h1>
            </div>
        </div>
        <div class="chat-body" id="chatBody">
            <div class="chat-body-messages" id="chatMessagesContainer">
                <div class="spacing-20"></div>
            </div>
            <div class="chat-alternatives">
                <div id="chatButtonAction">
                    <button class="chat-alternative" data-type="click">Jeg vil rapportere en feil</button>
                    <button class="chat-alternative" data-type="click">Jeg trenger hjelp</button>
                    <button class="chat-alternative" data-type="write">Annet (beskriv)</button>
                </div>
                <div id="chatTypingField">
                    <div id="textField" contenteditable="true"></div>
                    <button id="sendBtn">
                        <i class="fa-solid fa-arrow-up"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>

</html>