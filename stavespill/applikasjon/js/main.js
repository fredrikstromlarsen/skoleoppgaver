// Check if every input has a value and enable.
// submit button if statement is true.
// But only if the desired form exists.
if (document.querySelector('form.task')) {
	var char = document.querySelectorAll('input.char');
	var submit = document.querySelector('input.answer[type="submit"]');
	document.querySelector('form.task').oninput = () => {
		for (var i = 0; i < char.length; i++) {
			console.log(char[i]);
			if (!/^[A-ZÆØÅa-zæøå]{1}$/.test(char[i].value)) return (submit.disabled = true);
		}
		return (submit.disabled = false);
	};
}
