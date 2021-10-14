function testing(code) {
	if (code == '<img src="">'.substr(0, code.length)) return true;
	else {
		console.log('code: ' + code);
		console.log('match: ' + '<img src="">'.substr(0, code.length));
		return false;
	}
}

function tryHTML(element) {
	var outputElement = document.getElementById('htmlOutput');
	var errorElement = document.getElementById(
		element.getAttribute('data-error')
	);
	var code = element.value.trim();
	var elementType = element.getAttribute('data-element-type');

	if (elementType == 'img') {
		var url = code.replace(code.substr(0, Math.min(code.length, 9)), '');
		if (url.match(/"|">/g)) {
			url = url.replace(/["]|[>]/g, '');
		}
		var expectedInputString = '<img src="' + url + '">';
		var expectedInput = expectedInputString.substr(0, code.length);
		var htmlCode = code.replace(url, '');

		console.log('user input url: ' + url);
		console.log('user input html: ' + htmlCode);
		console.log('expected input: ' + expectedInputString);
		console.log('expected html: ' + expectedInput);

		if (htmlCode == '<img src="">') {
			// Legger til en alt="" attribute på slutten av
			// img - taggen, denne teksten vises når bildet
			// ikke kan lastes inn.
			output =
				'<img src="' +
				url +
				'" alt="Dette funket ikke. Sjekk at URLen lenker direkte til et bilde.">';
			errorElement.style.opacity = '0%';
			errorElement.style.maxHeight = '0rem';
		} else if (code == '' || code == expectedInput) {
			output =
				'<span>Bildet vises her hvis koden  og URLen er gyldig.</span>';
			errorElement.style.opacity = '0%';
			errorElement.style.maxHeight = '0rem';
		} else {
			output =
				'<span>Bildet vises her hvis koden og URLen er gyldig.</span>';
			errorElement.style.opacity = '100%';
			errorElement.style.maxHeight = '1.5rem';
		}
		outputElement.innerHTML = output;
	}
}

function tryCSS(element) {
	var errorElement = document.getElementById(
		element.getAttribute('data-element-error')
	);
	var code = element.innerHTML.replace(/<br>|&nbsp;|\s+/g, '');
	if (
		code.match(
			/h\d{1}{font-size:\d+(px|rem|em|ch|cm|mm|in|%|pt|pc|ex|vw|vh|vmin|vmax);}/g
		)
	) {
		var selector = code.replace(
			/{font-size:\d+(px|rem|em|ch|cm|mm|in|%|pt|pc|ex|vw|vh|vmin|vmax);}/,
			''
		);
		var value = code.replace(/h\d{1}\{font-size:|;\}/g, '');
		var property = code.replace(
			/h\d{1}\{|:\d+(px|rem|em|ch|cm|mm|in|%|pt|pc|ex|vw|vh|vmin|vmax);\}/g,
			''
		);
		if (selector && value && property) {
			document.querySelector('.col-right>' + selector).style.fontSize =
				value;
			console.log(selector + '<br>' + property + '<br>' + value);
			return true;
		} else {
			errorElement.style.opacity = '100%';
			errorElement.style.maxHeight = '1.5rem';
			return false;
		}
	}
}

function checkLang(element) {
	var lang = element.getAttribute('data-language');
	if (lang == 'html') tryHTML(element);
	else if (lang == 'css') tryCSS(element);
}

var inputArray = document.querySelectorAll('input, .input');
for (i = 0; i < inputArray.length; i++) {
	inputArray[i].addEventListener('input', checkLang(this));
}
