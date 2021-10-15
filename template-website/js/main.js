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
	var elementProperty = element.getAttribute('data-property');

	if (elementProperty == 'img') {
		var url = code.replace(code.substr(0, Math.min(code.length, 9)), '');
		if (url.match(/"|">/g)) {
			url = url.replace(/"|>/g, '');
		}
		var expectedInputString = '<img src="' + url + '">';
		var expectedInput = expectedInputString.substr(0, code.length);
		var htmlCode = code.replace(url, '');

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
				'<span>Bildet vises her hvis koden og URLen er gyldig.</span>';
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
		element.getAttribute('data-error')
	);
	var elementProperty = element.getAttribute('data-property');
	var code = element.innerHTML.replace(/\s+|&nbsp;|<br>/gi, '');
	const rulesetArr = code.split(/}/g);

	switch (elementProperty) {
		case 'font-size':
			var validRegex =
				/^h[1-6]{1}{font-size:\d{1,}(px|rem|em|ch|cm|mm|in|%|pt|pc|ex|vw|vh|vmin|vmax);}$/;
			var anyValid = false;

			for (i = 0; i < rulesetArr.length; i++) {
				rulesetArr[i] = rulesetArr[i] + '}';

				if (rulesetArr[i].match(validRegex)) {
					var selector = rulesetArr[i].substr(0, 2);
					var value = rulesetArr[i].replace(
						/^h[1-6]{1}{font-size:|;}$/g,
						''
					);
					anyValid = true;

					console.log('valid ruleset: ' + rulesetArr[i]);
					document.getElementById(
						'css-task-1-' + selector
					).style.fontSize = value;
				}
			}

			if (anyValid) {
				errorElement.style.opacity = '0%';
				errorElement.style.maxHeight = '0rem';
			} else {
				errorElement.style.opacity = '100%';
				errorElement.style.maxHeight = '1.5rem';
			}
			break;
		case 'font-family':
			var validRegex = /^(h[1-6]{1}|p){font-family:[A-Za-z'"\- ]{1,};}$/;
			var anyValid = false;

			for (i = 0; i < rulesetArr.length; i++) {
				rulesetArr[i] = rulesetArr[i] + '}';

				if (rulesetArr[i].match(validRegex)) {
					var selector = rulesetArr[i].substr(0, 2);
					var value = rulesetArr[i].replace(
						/^(h[1-6]{1}|p){font-family:|;}$/g,
						''
					);
					anyValid = true;

					console.log('valid ruleset: ' + rulesetArr[i]);
					document.getElementById(
						'css-task-2-' + selector
					).style.fontFamily = value;
				}
			}

			if (anyValid) {
				errorElement.style.opacity = '0%';
				errorElement.style.maxHeight = '0rem';
			} else {
				errorElement.style.opacity = '100%';
				errorElement.style.maxHeight = '1.5rem';
			}
			break;
	}
}

function tryJS(element) {
// p5.js

}

function checkLang(element) {
	var lang = element.getAttribute('data-language');
	if (lang == 'html') tryHTML(element);
	else if (lang == 'css') tryCSS(element);
	else if (lang == 'js') tryJS(element);
}

var inputArray = document.querySelectorAll('input, .input');
for (i = 0; i < inputArray.length; i++) {
	inputArray[i].addEventListener('input', checkLang(this));
}
