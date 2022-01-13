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
		char.addEventListener('keypress', function (e) {
			// Prevent more than 1 character in input field.
			// Whitelist characters such as Backspace and Enter.
			console.log(e);

			// Allow only return, delete (default) and backspace
			// (default ), when ipnut field has length of 1.
			if (
				char.value.length >= 1 &&
				(e.keyCode !== 13 || e.which !== 13)
			) {
				e.preventDefault();
				return false;
			}
		});
		char.addEventListener('input', () => {
			// Get the index of char in chars array,
			// add 1 to it, and set focus on the next
			// input field (unless it's last).
			let id = char.id;
			let index =
				parseInt(id.substring(id.length - 1, id.length), 10) + 1;
			if (char.value != '' && index < chars.length) chars[index].focus();
		});
	}
}

if (document.querySelector('button#exit'))
	document.querySelector('button#exit').onclick = () => {
		return (location.href = 'php/logout.php');
	};