// Use logout.php to handle logout event.
if (document.querySelector('button#exit'))
	document.querySelector('button#exit').onclick = () => {
		return (location.href = 'php/logout.php');
	};

function playAudio(id, trg, delay) {
	try {
		const audio = document.getElementById(id);
		setTimeout(() => {
			window.onload = audio.play();
		}, delay);

		if (trg != '') {
			const trigger = document.getElementById(trg);
			trigger.addEventListener('click', () => {
				audio.play();
			});
		}
	} catch (e) {
		console.log(e.message);
	}
}

playAudio('synthesizedAudio', 'audioTrigger', 800);
playAudio('response', '');
