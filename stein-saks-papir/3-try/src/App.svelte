<script>
	import Player from './Player.svelte';
	import Historic from './Historic.svelte';
	import Machine from './Machine.svelte';

    export let gameHistory = [[2, 0], [1, 1], [2, 1], [0, 2], [1, 2], [2, 2], [0, 1], [1, 0], [2, 0]];
	// export let gameHistory
	export let machineAction;

    export const actions = [
		["rock", "✊"],
        ["scissor", "✌"],
        ["paper", "🖐"]
	];
	
 	export function getResult(a, b) {
		return [[2, 0, 1],[1, 2, 0], [0, 1, 2]][a][b];
    }

	let results;
	function gameHistoryResults() {
		results = [0, 0, 0];
		for (let i = 0; i < gameHistory.length; i++) {
			let winner = getResult(gameHistory[i][0], gameHistory[i][1]);
			results[winner]++;
		}
		console.log(results);
		return results;
	}
	export let scoreboard = [gameHistoryResults()[0], gameHistoryResults()[1]];

	export function game(playerAction) {		
		machineAction = Math.floor(Math.random() * 3);
		gameWinner = getResult(playerAction, machineAction);
		gameHistory = [...gameHistory, [playerAction, machineAction]];
		scoreboard[gameWinner]++;
	}

</script>

<style>
	main {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		padding: 1rem 2rem;
	}
</style>

<main>
	<Player {actions} {game} />
	<Historic {actions} {scoreboard} {gameHistory} {getResult} />
	<Machine {actions} {machineAction} {getResult} />
</main>