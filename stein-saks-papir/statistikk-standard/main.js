// Stein-, saks-, papirmekanikk
let totalRounds = 1000000;
let scoreboard;
let machineAction;
let winner;
let random;

// 0 = spiller vinner, 1 = maskin vinner, 2 = uavgjort
const winTable = [
	[2, 0, 1],
	[1, 2, 0],
	[0, 1, 2],
];
let gameHistory = [];

function percentage(score, totalGames) {
	return Math.round((score / totalGames) * 10000) / 100;
}

function renderHistory(history) {
	const container = document.getElementById('gameHistory');
	let markup;

	for (let i = history.length - 1; i >= 0; i--) {
		markup +=
			'<tr>\
				<td>' +
			history[i][0] +
			'(' +
			percentage(
				history[i][0],
				history[i][0] + history[i][1] + history[i][2]
			) +
			'%)</td>\
				<td>' +
			history[i][1] +
			'(' +
			percentage(
				history[i][1],
				history[i][0] + history[i][1] + history[i][2]
			) +
			'%)</td>\
				<td>' +
			history[i][2] +
			'(' +
			percentage(
				history[i][2],
				history[i][0] + history[i][1] + history[i][2]
			) +
			'%)</td>\
			</tr>';
	}
	container.innerHTML = markup;
}

function game(playerAction) {
	// Wins, losses, ties
	scoreboard = [0, 0, 0];

	if (playerAction === 3) random = 1;
	else random = 0;

	for (let i = 0; i < totalRounds; i++) {
		if (random === 1) playerAction = Math.floor(Math.random() * 3);

		machineAction = Math.floor(Math.random() * 3);
		winner = winTable[playerAction][machineAction];
		scoreboard[winner]++;
	}

	gameHistory[gameHistory.length] = [
		scoreboard[0],
		scoreboard[1],
		scoreboard[2],
	];
	renderHistory(gameHistory);
}
