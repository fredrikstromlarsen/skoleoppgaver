var inputArray = document.querySelectorAll('input, .input')
console.log(inputArray);

for (i = 0; i < inputArray.length; i++) {
	inputArray[i].addEventListener('input', checkLang(this));
}

function tryHTML(element) {
	var outputElement = document.getElementById('htmlOutput');
	var errorElement = document.getElementById('errorMessage');
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
			output = code.substring(0, code.length - 1) + ' alt="Dette funket ikke, sjekk at URLen lenker direkte til et bilde.">';
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
    var stylesheet = window.document.styleSheets[0];
    var code = element.innerHTML;
    if (code.match(/^(h\d{1,}\s+{\s+font-size:\s+\d+(px|rem|em|ch|cm|mm|in|%|pt|pc|ex|vw|vh|vmin|vmax);\s+})$/g)) {
        output = ".col-right " + code;
        stylesheet.insertRule(output, stylesheet.cssRules.length);
    }
}

function checkLang(element) {
	var lang = element.getAttribute('data-language');
	if (lang == 'html') tryHTML(element);
	else if (lang == 'css') tryCSS(element);
}
