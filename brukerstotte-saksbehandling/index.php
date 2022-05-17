<!-- 
TODO:
- FIX: 
    - nextIndex not working
    - setChatInput() to buttons not working
    - 

 -->
<?php
error_reporting(-1);
ini_set('display_errors', 1);
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
        // List of all [steps][chat alternatives][individual chat data]
        const chatMessages = <?= file_get_contents("json/chat-messages.json") ?>;
        const botAnswerDelay = 1000;
        let answers = [];
        let previousIndex = 0;

        function exportData() {
            // Encode answers array to JSON then serialize to Base64 

            encodedAnswers = [];
            answers.forEach((answer) => {
                encodedAnswers.push(btoa(answer));
            });
            let json = JSON.stringify(encodedAnswers);
            let base64 = btoa(json);

            // Send Base64 to api/parseAnswers.php via a post request
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "api/parseAnswers.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.send("answers=" + base64);
        }

        function sendChatMessage(message, sender) {
            var senderClass, markup;
            const container = document.getElementById('chatMessagesContainer');
            let date = new Date(),
                time =
                date.getHours().toString().padStart(2, 0) +
                ':' +
                date.getMinutes().toString().padStart(2, 0);

            if (sender === 'user') {
                senderClass = 'sent';
                answers.push(message);
            } else senderClass = 'received';

            markup =
                '\
            <div class="chat-body-message chat-message-' +
                senderClass +
                '">\
                <div class="chat-body-message-text">' + message + '</div>\
                <div class="chat-body-message-info">' + time + '</div>\
            </div>';

            container.insertAdjacentHTML('beforeend', markup);
            scrollToBottom('.chat-body-messages');
        }

        function scrollToBottom(elementQuery) {
            let element = document.querySelector(elementQuery);
            element.scrollTop = element.scrollHeight;
        }

        function addInputListener(type) {
            if (type == "text") {
                var typingField = document.getElementById('textField');
                var typingFieldBtn = document.getElementById('sendBtn');

                typingFieldBtn.addEventListener('click', () => {
                    if (typingField.innerText.trim() != '') handleUserInput(typingField);
                });
                typingField.addEventListener('keydown', (e) => {
                    // If Return was pressed send the message. Prevent inputting newline.
                    if (e.keyCode === 13 && typingField.innerText.trim() != '') {
                        e.preventDefault();
                        handleUserInput(typingField);
                    }
                });
                typingField.focus();
            } else if (type == "btn") {
                document.querySelectorAll('button.chat-alternative')
                    .forEach(a =>
                        a.addEventListener('click', () =>
                            handleUserInput(a)
                        )
                    );
            }
        }

        function handleUserInput(input) {
            // Get attribute values for new chat message
            let message = input.innerText.trim();
            // Click / Text
            let type = input.getAttribute('data-type');
            // chatMessages[nextStep]
            let nextStep = input.getAttribute("data-next-step");
            // chatMessages[nextStep][nextIndex]
            let nextIndex = input.getAttribute("data-next-index");

            // console.log("handleUserInput()", "message: " + message, "type: " + type, "nextStep: " + nextStep, "nextIndex: " + nextIndex);

            // Send message to bot
            if (message != '') sendChatMessage(message, 'user');
            if (type == 1) input.innerText = '';

            // Get bot answer
            setChatInput(nextStep, nextIndex);
        }

        function setChatInput(step, index) {
            let currentChat = chatMessages[step][index];
            const container = document.getElementById('chatActions');

            if (currentChat === undefined) {
                currentChat = chatMessages[step][0];
                // console.log("setChatInput():", "chat was undefined. falling back to index 0");
            }

            // Who let the dogs out?
            let sender;
            sender = currentChat["sender"];

            // console.log("setChatInput()", "step:", step, "index:", index, "sender: ", sender);

            // If sender is user, set the input options according to previous question.
            // Otherwise, send the chat message/question as bot.
            if (sender == "user") {
                let markup = "";

                // Determine what type of input to show based on previous message
                let previousChat = chatMessages[step - 1][index];
                let answerType = previousChat["answerType"];

                // console.log("setChatInput()", "answerType:", answerType, "previousChat:", previousChat);

                // answerType: 0 = buttons
                if (answerType === 0) {
                    currentStepMessages = chatMessages[step];
                    // Loop over all button alternatives (answers) in chatMessage and add to markup
                    for (let i = 0; i < currentStepMessages.length; i++) {
                        markup += '<button class="chat-alternative" data-type="click" data-next-step="' + currentStepMessages[i]["nextStep"] + '" data-next-index="' + currentStepMessages[i]["nextIndex"] + '">' + currentStepMessages[i]["message"] + '</button>';
                    }

                    setTimeout(() => {
                        container.innerHTML = markup;
                        addInputListener("btn", ".chat-alternative");
                    }, botAnswerDelay);
                } else {
                    markup = '\
                        <div id="chatTypingField">\
                            <div id="textField" contenteditable="true" data-type="text" data-next-step="' + currentChat["nextStep"] + '" data-next-index="' + index + '"></div>\
                            <button id="sendBtn">\
                                <i class="fa-solid fa-arrow-up"></i>\
                            </button>\
                        </div>';


                    setTimeout(() => {
                        container.innerHTML = markup;
                        addInputListener("text");
                    }, botAnswerDelay);
                }
            } else {
                setTimeout(() => sendChatMessage(currentChat["message"], 'bot'), botAnswerDelay);

                // Remove inputs
                container.innerHTML = "";

                if (currentChat.done) {
                    exportData();
                    return;
                } else setChatInput(currentChat["nextStep"], currentChat["nextIndex"]);
            }
        }

        window.onload = () => {
            sendChatMessage("Hei! Har du spørsmål, funnet en feil, eller trenger hjelp med noe, trykk på et av alternativene under for å starte en samtale.", 'bot');
            addInputListener("btn");
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
                <button class="chat-alternative" data-type="click" data-next-step="0" data-next-index="0">Jeg trenger hjelp</button>
                <button class="chat-alternative" data-type="click" data-next-step="0" data-next-index="1">Jeg vil rapportere en feil</button>
                <button class="chat-alternative" data-type="click" data-next-step="0" data-next-index="2">Noe annet</button>
            </div>
        </div>
    </div>
</body>

</html>