function reset() {
	// Reset scoreboard
	document.getElementById('playerScore').innerHTML = 0;
	document.getElementById('machineScore').innerHTML = 0;

	// Remove existing JSON data from data/stats.json
	const resetData = {
		gameCount: 0,
		playerWins: 0,
		machineWins: 0,
		ties: 0,
	};
	console.log('Resetting: ', postJSON(resetData));

	return 1;
}

function getJSON(url) {
	// Use the Fetch API to get the data from the server.
	console.log(
		'Fetching JSON data: ',
		fetch(url).then((response) => response.json())
	);
}

function postJSON(obj) {
	// Use the Fetch API to post the data to the server.
	return fetch('./api/updateJson.php', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json',
		},
		body: JSON.stringify('hello word'),
	});
}

function game(playerAction) {
	const moveDict = {
		rock: 0,
		paper: 1,
		scissors: 2,
	};

	let machineMove = Math.floor(Math.random() * 3);
	let playerMove = moveDict[playerAction];
	console.log(playerAction);
	console.log(playerMove, machineMove);
}

window.onload = () => {
	reset();

	// Listen for player input
	document.querySelectorAll('div.clickable').forEach((div) => {
		addEventListener('click', game(div.value));
	});

	const data = getJSON('./data/stats.json');
	const playerScore = document.getElementById('playerScore');
	const machineScore = document.getElementById('machineScore');

	console.log(data);
};
