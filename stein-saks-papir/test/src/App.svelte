<script>
	// Stein-, saks-, papirmekanikk
	let totalRounds = 1

	const winTable = [[2, 0, 1],[1, 2, 0], [0, 1, 2]] 
	let gameHistory = [[8, 5, 7], [100, 1034, 32]]

    export function percentage(score, totalGames) {
        return Math.round((score / totalGames) * 10000) / 100;
    }

	export function game(playerAction){
		// Wins, losses, ties
		scoreboard = [0, 0, 0]

		for(i=0; i<totalRounds; i++){
			if (playerAction === 3) playerAction = Math.floor(Math.random() * 3)
			machineAction = Math.floor(Math.random() * 3)
			winner = winTable[playerAction][machineAction]
			scoreboard[winner]++
		}

		gameHistory[gameHistory.length] = {
			wins: scoreboard[0],
			losses: scoreboard[1],
			ties: scoreboard[2]
		}
	}

</script>

<main>
	<label for="rounds">Antall runder</label>
	<input type="text" id="rounds" bind:value={totalRounds}>
	<div class="btn-container">
		<button on:click={() => game(0)}>Stein</button>
		<button on:click={() => game(1)}>Saks</button>
		<button on:click={() => game(2)}>Papir</button>
		<button on:click={() => game(3)}>Tilfeldig</button>
	</div>

	<table border=1>
		<tr>
			<th>Vinn</th>
			<th>Tap</th>
			<th>Likt</th>
		</tr>
		{#each gameHistory as game}
			<tr>
				<td>{game[0]} ({percentage(game[0], game.reduce((x,y) => x+y))}%)</td>
				<td>{game[1]} ({percentage(game[1], game.reduce((x,y) => x+y))}%)</td>
				<td>{game[2]} ({percentage(game[2], game.reduce((x,y) => x+y))}%)</td>
			</tr>
		{/each}
	</table>
</main>

<style>
	.btn-container {
		display: flex;
		flex-direction: row;
	}
</style>