// Check if every input has a value and enable.
// submit button if statement is true.
// But only if the desired form exists.
if (document.querySelector('form.task')) {
	var chars = document.querySelectorAll('input.char');
	var submit = document.querySelector('input.answer[type="submit"]');
	document.querySelector('form.task').addEventListener('input', () => {
		for (var i = 0; i < chars.length; i++) {
			if (!/^[A-ZÆØÅa-zæøå]{1}$/.test(chars[i].value))
				return (submit.disabled = true);
		}
		return (submit.disabled = false);
	});
	for (let char of chars) {
		char.addEventListener('input', () => {
			if (char.value.length > 1) {
				char.value = char.value.substr(0, 1);
			}

			// Get the index of char in chars array,
			// add 1 to it, and set focus on the next
			// input field (unless it's last).
			let id = char.id;
			let index = parseInt(id.replace('charInput', ''), 10) + 1;
			if (index >= chars.length) submit.focus();
			else if (char.value != '' && index < chars.length)
				chars[index].focus();
		});
	}
}

// Use logout.php to handle logout event.
if (document.querySelector('button#exit'))
	document.querySelector('button#exit').onclick = () => {
		return (location.href = 'php/logout.php');
	};

function test1() {
	var audio = new Audio();
	audio.src =
		'http://translate.google.com/translate_tts?ie=utf-8&tl=en&q=Hello%20World.';
	audio.play();
}

function test() {
	var msg = new SpeechSynthesisUtterance('Hello World');
	var msg = new SpeechSynthesisUtterance();
	var voices = window.speechSynthesis.getVoices();
	msg.voice = voices[10]; // Note: some voices don't support altering params
	msg.voiceURI = 'native';
	msg.volume = 1; // 0 to 1
	msg.rate = 1; // 0.1 to 10
	msg.pitch = 2; //0 to 2
	msg.text = 'Hello World';
	msg.lang = 'en-US';

	msg.onend = function (e) {
		console.log('Finished in ' + e.elapsedTime + ' seconds.');
	};
	speechSynthesis.speak(msg);

	speechSynthesis.getVoices().forEach(function (voice) {
		console.log(voice.name, voice.default ? voice.default : '');
	});
}
