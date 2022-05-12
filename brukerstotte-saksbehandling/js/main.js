class ChatMessage {
	constructor(message, sender) {
		this.message = message;
		this.sender = sender;
	}

	send() {
		var senderClass, markup;
		const container = document.getElementById('chatMessagesContainer');
		let date = new Date();
		let time =
			date.getHours().toString().padStart(2, 0) +
			':' +
			date.getMinutes().toString().padStart(2, 0);

		if (this.sender === 'user') senderClass = 'sent';
		else senderClass = 'received';

		markup =
			'\
            <div class="chat-body-message chat-message-' +
			senderClass +
			'">\
                <div class="chat-body-message-text">\
                    <span>' +
			this.message +
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
}

class RobotResponse {
    constructor(input) {
        
    }
}

function scrollToBottom(query) {
	var elements = document.querySelectorAll(query);
	for (var i = 0; i < elements.length; i++)
		elements[i].scrollTop = elements[i].scrollHeight;
}

function sendChatMessage(m, s) {
	var chat = new ChatMessage(m, s);
	chat.send();
}

function writeChatMessage() {
	document.getElementById('chatButtonAction').style.display = 'none';
    document.getElementById('chatTypingField').style.display = 'flex';
    document.getElementById("textField").focus();
}

function clearInput(input) {
	input.innerText = '';
	input.value = '';
}



window.onload = () => {
	setTimeout(() => {
		firstInteraction = new ChatMessage(
			'Hei, hva kan vi hjelpe deg med?',
			'robot'
		);
		firstInteraction.send();
	}, 300);

	var alts = document.querySelectorAll('.chat-alternative');
	alts.forEach((alt) => {
		alt.addEventListener('click', () => {
			var type = alt.getAttribute('data-type');
			if (type === 'click') sendChatMessage(alt.innerText, 'user');
			else if (type === 'write') writeChatMessage();
		});
	});
	var typingField = document.getElementById('textField');
	var typingFieldBtn = document.getElementById('sendBtn');

	typingFieldBtn.addEventListener('click', () => {
		var message = typingField.innerText.trim();
		if (message != '') sendChatMessage(message, 'user');
		clearInput(typingField);
	});
};
