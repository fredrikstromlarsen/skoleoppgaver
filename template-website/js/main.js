var inputArray = document.querySelectorAll('input');
for (i = 0; i < inputArray.length; i++) {
	inputArray[i].addEventListener('input', checkLang(this));
}

function tryHTML(element) {
	var outputElement = document.getElementById('htmlOutput');
	var errorMessage = document.getElementById('errorMessage');
	var code = element.value.trim();
	var elementType = element.getAttribute('data-element-type');

	if (elementType == 'img') {
		if (
			// Sjekk at koden matcher <img src="hvilken som helt url">
			code.match(/^([<]img src=".{1,}"[>])$/i)
		) {
			// Legger til en alt="" attribute på slutten av
			// img - taggen, denne teksten vises når bildet
			// ikke kan lastes inn.
			output =
				code.substring(0, code.length - 1) +
				' alt="Dette funket ikke, sjekk at URLen lenker direkte til et bilde.">';
			error = '';
		} else {
			output = '';
			error =
				'Invalid kode. Skriv det slik: <code><b>&lt;img src="https://www.example.com/image.jpg"&gt;</b></code>';
		}
		outputElement.innerHTML = output;
		errorMessage.innerHTML = error;
	}
}

function checkLang(element) {
	var lang = element.getAttribute('data-language');
	if (lang == 'html') tryHTML(element);
	else if (lang == 'css') tryCSS(element);
}
