function tryHTML(element) {
	var outputElement = document.getElementById('htmlOutput');
	var errorElement = document.getElementById(element.getAttribute('data-element-error'));
	var code = element.value.trim();
	var elementType = element.getAttribute('data-element-type');

	if (elementType == 'img') {
		if (
			// Sjekk at koden matcher:
			// <img src="hvilken som helt url">
			code.match(/^([<]img src=".{1,}"[>])$/i)
		) {
			// Legger til en alt="" attribute på slutten av
			// img - taggen, denne teksten vises når bildet
			// ikke kan lastes inn.
			output =
				code.substring(0, code.length - 1) +
				' alt="Dette funket ikke, sjekk at URLen lenker direkte til et bilde.">';
			errorElement.style.opacity = '0%';
			errorElement.style.maxHeight = '0rem';
		} else if (code == '') {
			output = '<span>Bildet vises her hvis koden er gyldig.</span>';
			errorElement.style.opacity = '0%';
			errorElement.style.maxHeight = '0rem';
		} else {
			output = '<span>Bildet vises her hvis koden er gyldig.</span>';
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
			document.querySelector('.col-right>' + selector).style.fontSize = value;
			console.log(selector + "<br>" + property + "<br>" + value);
			return true;
		} else return false;

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
