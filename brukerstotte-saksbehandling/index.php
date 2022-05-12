<?php

/*/
/* [0] = step
/* [1] = index in step
/* [2] = answer type (0 = buttons, 1 = text)
/*/
$questions = file_get_contents("json/questions.json");

/*/
/* [0] = step
/* [1] = index in step
/*/
$answers = file_get_contents("json/answers.json");


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bedrift AS</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        const questions = <?= $questions; ?>;
        const answers = <?= $answers; ?>;

        function sendChatMessage(message, sender) {
            var senderClass, markup;
            const container = document.getElementById('chatMessagesContainer');
            let date = new Date();
            let time =
                date.getHours().toString().padStart(2, 0) +
                ':' +
                date.getMinutes().toString().padStart(2, 0);

            if (sender === 'user') senderClass = 'sent';
            else senderClass = 'received';

            markup =
                '\
            <div class="chat-body-message chat-message-' +
                senderClass +
                '">\
                <div class="chat-body-message-text">\
                    <span>' +
                message +
                '</span>\
                </div>\
                <div class="chat-body-message-info">\
                    <span>' +
                time +
                '</span>\
                </div>\
            </div>';

            container.insertAdjacentHTML('beforeend', markup);
            scrollToBottom('.chat-body-messages');
            return markup;
        }

        function scrollToBottom(elementQuery) {
            let element = document.querySelector(elementQuery);
            element.scrollTop = element.scrollHeight;
        }

        function writeChatMessage() {
            document.getElementById('chatButtonAction').style.display = 'none';
            document.getElementById('chatTypingField').style.display = 'flex';
            document.getElementById("textField").focus();
        }

        function clearInput(input) {
            input.innerText = '';
        }

        function handleUserInput(input) {
            let message = input.innerText.trim();
            let type = input.getAttribute('data-type');
            let nextStep = input.getAttribute("data-next-step");
            let nextIndex = input.getAttribute("data-next-index");

            if (type === "text") {
                if (message != '') sendChatMessage(message, 'user');
                clearInput(input);
            } else if (type === "button") {
                sendChatMessage(message, 'user');
            }

            // Send robo-question
            let question = questions[nextStep][nextIndex];
            let roboMsg = question["message"];
            setTimeout(() => sendChatMessage(roboMsg, 'robot'), 1500);

            // Set chat inputs according to robot question type
            let answerType = question["answerType"];
            if (answerType === 0)
                setChatInput(nextStep);
            else {
                writeChatMessage();
            }
        }

        function setChatInput(index) {
            /* 
            if buttons
                ...

            else if text
                markup = <div id="chatTypingField">
                    <div id="textField" contenteditable="true"></div>
                    <button id="sendBtn">
                        <i class="fa-solid fa-arrow-up"></i>
                    </button>
                </div>
            
            */

            const container = document.getElementById('chatButtonAction');
            let markup = "";
            for (let i = 0; i < answers[index].length; i++) {
                let x = answers[index][i];
                console.log("x", x);
                markup += '<button class="chat-alternative" data-type="click" data-next-step="' + x["nextStep"] + '" data-next-index="' + x["nextIndex"] + '">' + x["message"] + '</button>';
            }
            container.innerHTML = markup;

            var alts = document.querySelectorAll('.chat-alternative');

            // Add "click" eventlistener to all button alternatives.
            alts.forEach(
                (alt) => alt.addEventListener('click',
                    () => handleUserInput(alt)
                )
            );
        }

        window.onload = () => {
            var typingField = document.getElementById('textField');
            var typingFieldBtn = document.getElementById('sendBtn');

            typingFieldBtn.addEventListener('click', () => handleUserInput(typingField));
            typingField.addEventListener('keydown', (e) => {
                // If Return was pressed send the message. Prevent inputting newline.
                if (e.keyCode === 13) {
                    e.preventDefault();
                    handleUserInput(typingField);
                }
            });

            setChatInput(0);
        };
    </script>
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
            <div class="chat-alternatives" id="chatActions">

            </div>
        </div>
    </div>
</body>

</html>