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

            setChatInput(Number(nextStep));

            // Send robo-question
            // let question = questions[nextStep][nextIndex];
            // let roboMsg = question["message"];
            // setTimeout(() => sendChatMessage(roboMsg, 'robot'), 1500);

            // Set chat inputs according to robot question type
            // let answerType = question["answerType"];
            // if (answerType === 0)
            // setChatInput(nextStep);
            // else {
            // writeChatMessage();
            // }
        }

        function setChatInput(step) {
            console.log(step);
            let chat = chatMessages[step];
            let sender = chat[0]["sender"];

            // If sender is user, set the input options according to previous question.
            // Otherwise, send the chat message/question as bot.
            if (sender === "user") {
                // Set the user inputs according to answerType in previous chat message/question.

                const container = document.getElementById('chatActions');
                let markup = "";
                let nextChat = chatMessages[step + 1];
                let prevChat = chatMessages[step - 1];
                let answerType = prevChat[0]["answerType"];
                // answerType: 0 = buttons
                if (answerType === 0) {

                    // Loop over all button alternatives (answers) in chatMessage and add to markup
                    for (let i = 0; i < chat.length; i++)
                        markup += '<button class="chat-alternative" data-type="click" data-next-step="' + chat[i]["nextStep"] + '" data-next-index="' + chat[i]["nextIndex"] + '">' + chat[i]["message"] + '</button>';
                    container.innerHTML = markup;

                    // Add onclick-listeners to buttons 
                    document.querySelectorAll('.chat-alternative')
                        .forEach((alt) =>
                            alt.addEventListener('click', () =>
                                handleUserInput(alt)
                            )
                        );

                    // answerType: 1 = text 
                } else if (answerType === 1) {
                    let q = chat[0];
                    markup = '\
                        <div id="chatTypingField">\
                            <div id="textField" contenteditable="true" data-type="text" data-next-step="' + q["nextStep"] + '" data-next-index="' + q["nextIndex"] + '"></div>\
                            <button id="sendBtn">\
                                <i class="fa-solid fa-arrow-up"></i>\
                            </button>\
                        </div>';

                    container.innerHTML = markup;

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
                }
            } else {
                // Send robo-question
                setTimeout(() => sendChatMessage(chatMessages[step][0]["message"], 'robot'), 1500);
                setChatInput(Number(step) + 1);
            }
        }

        window.onload = () => {
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