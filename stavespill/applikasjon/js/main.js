// Use logout.php to handle logout event.
if (document.querySelector('button#exit'))
	document.querySelector('button#exit').onclick = () => {
		return (location.href = 'php/logout.php');
	};

// if (document.querySelector('button#audio'))
// document.querySelector('button#audio').onclick = () => {
// return document.getElementById('audio#synthesizedAudio')();
// };

if (document.getElementById('synthesizedAudio')) {
	const audio = document.getElementById('synthesizedAudio');
	const trigger = document.getElementById('audioTrigger');
	window.onload = audio.play();
	trigger.addEventListener('click', () => {
		audio.play();
	});
}
