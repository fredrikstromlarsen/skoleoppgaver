function testing() {
	var code = '<img src="https://">';
	if (code.match(/^(<img src=".{1,}">)$/)) return true;
	else return false;
}

console.log(testing());

function isImage(url) {
	console.log('isImage(' + url + ')');
	returnValue = new Promise(function (rv) {
		const img = new Image();
		img.onload = () => (rv = true);
		img.onerror = () => (rv = false);
		img.src = url;
		return rv;
	});
	console.log(returnValue);
	return returnValue;
}

function tryHTML(element) {
	var outputElement = document.getElementById('htmlOutput');
	var errorElement = document.getElementById(
		element.getAttribute('data-element-error')
	);
	var code = element.value.trim();
	var elementType = element.getAttribute('data-element-type');

	if (elementType == 'img') {
		var url = code.replace(/<img src="|">/g, '');
		var code = code.replace(url, '');

		console.log('url: ' + url);
		console.log('code: ' + code);

		if (code == '<img src="">') {
			// Legger til en alt="" attribute på slutten av
			// img - taggen, denne teksten vises når bildet
			// ikke kan lastes inn.
			output = '<img src="' + url + '" alt="Dette funket ikke... sjekk at URLen lenker direkte til et bilde.">';
			errorElement.style.opacity = '0%';
			errorElement.style.maxHeight = '0rem';
		} else if (code == '' || code.includes()) {
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
