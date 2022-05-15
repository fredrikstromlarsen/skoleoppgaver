<!-- 
TODO:
- FIX: 
    - nextIndex not working
    - setChatInput() to buttons not working

 -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bedrift AS</title>
    <link rel="stylesheet" href="css/style.css">
    <script>
        const chatMessages = <?= file_get_contents("json/chat-messages.json") ?>;
        const delay = 1000;
        let answers = [];

        /* TODO:
        - Send object to api/parseAnswers.php 
        - Admin panel to view/manage cases
        */

        function sendChatMessage(message, sender) {
            var senderClass, markup;
            const container = document.getElementById('chatMessagesContainer');
            let date = new Date(),
                time =
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

        function clearInput(input) {
            input.innerText = '';
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
            let message = input.innerText.trim();
            let type = input.getAttribute('data-type');
            let nextStep = input.getAttribute("data-next-step");
            let nextIndex = input.getAttribute("data-next-index");

            console.log("handleUserInput()", "message: " + message, "type: " + type, "nextStep: " + nextStep, "nextIndex: " + nextIndex);

            if (type == "text") {
                if (message != '') sendChatMessage(message, 'user');
                clearInput(input);
            } else if (type == "click") {
                sendChatMessage(message, 'user');
            }
            console.log("handleUserInput()", "nextStep: " + nextStep, "nextIndex: " + nextIndex);
            setChatInput(Number(nextStep), Number(nextIndex));
        }

        function setChatInput(step, index) {
            console.log("setChatInput()", "step:", step, "index:", index);
            let chat = chatMessages[step];
            let sender = chat[index]["sender"];

            // If sender is user, set the input options according to previous question.
            // Otherwise, send the chat message/question as bot.
            console.log("setChatInput()", "sender:", sender);
            if (sender == "user") {
                // Set the user inputs according to answerType in previous chat message/question.

                const container = document.getElementById('chatActions');
                let markup = "";
                let prevChat = chatMessages[step - 1];
                let answerType = prevChat[index]["answerType"];

                container.innerHTML = "";

                // answerType: 0 = buttons
                console.log("setChatInput()", "answerType:", answerType);
                if (answerType === 0) {
                    // Loop over all button alternatives (answers) in chatMessage and add to markup
                    for (let i = 0; i < chat.length; i++) {
                        markup += '<button class="chat-alternative" data-type="click" data-next-step="' + chat[i]["nextStep"] + '" data-next-index="' + chat[i]["nextIndex"] + '">' + chat[i]["message"] + '</button>';
                    }

                    setTimeout(() => {
                        container.innerHTML = markup;
                        addInputListener("btn", ".chat-alternative");
                    }, delay);
                } else if (answerType === 1) {
                    let pC = prevChat[index];
                    let C = chat[0];

                    // console.log("setChatInput()", "pC:", pC, "C:", C);

                    markup = '\
                        <div id="chatTypingField">\
                            <div id="textField" contenteditable="true" data-type="text" data-next-step="' + C["nextStep"] + '" data-next-index="' + index + '"></div>\
                            <button id="sendBtn">\
                                <i class="fa-solid fa-arrow-up"></i>\
                            </button>\
                        </div>';


                    setTimeout(() => {
                        container.innerHTML = markup;
                        addInputListener("text");
                    }, delay);
                }
            } else {
                setTimeout(() => sendChatMessage(chatMessages[step][index]["message"], 'robot'), delay);
                console.log(chat[step]["nextStep"], chat[step]["nextIndex"], "or", chat[index]["nextStep"], chat[index]["nextIndex"]);
                setChatInput(chat[index]["nextStep"], chat[index]["nextIndex"]);
            }
            console.log("setChatInput()", sender + " finished");
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