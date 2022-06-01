<!-- CLIENT SIDE -->
<script lang="ts">
	// import { Socket } from 'socket.io-client';
	import { io } from '$lib/realtime';
	import { onMount } from 'svelte';

	// Create new typescript type: Dictionary
	interface Dictionary<T> {
		[Key: string]: T;
	}

	const actions: Array<any> = [
			{ id: 0, name: 'Stein' },
			{ id: 1, name: 'Saks' },
			{ id: 2, name: 'Papir' }
		],
		// 2 = tie, 0 = player, 1 = opponent
		winMatrix: Array<Array<number>> = [
			[2, 0, 1],
			[1, 2, 0],
			[0, 1, 2]
		];

	const player: Dictionary<any> = {
		id: ''
	};

	let status: string = '',
		data: any = [],
		gameHistoryContainer: Element,
		actionsContainer: Element;

	function result(game: Array<any>) {
		const playerIndex = game.findIndex((p: any) => p.id === player.id);
		const opponentIndex = game.findIndex((p: any) => p.id !== player.id);

		const playerAction = game[playerIndex].action;
		const opponentAction = game[opponentIndex].action;

		return winMatrix[playerAction][opponentAction];
	}

	function showGameHistory(gameHistory: Array<Array<any>>) {
		const outcomes = ['Win', 'Loss', 'Tie'];
		let markup: string = '',
			outcome: string;

		gameHistory.forEach((game) => {
			// Check if player.id is in the game
			let x = game.findIndex((p: any) => p.id === player.id);
			if (x > -1) {
				outcome = outcomes[result(game)];
				let playerIndex = x;
				let playerId = game[playerIndex].id;
				let playerAction = game[playerIndex].action;

				// Hack: return 1 if playerIndex was 0.
				let opponentIndex = [1, 0][playerIndex];
				let opponentId = game[opponentIndex].id;
				let opponentAction = game[opponentIndex].action;

				markup += `
				<div>
					<div class="left">
						<h2>${outcome.toUpperCase()}</h2>
						<p>${playerId}</p>
						<p>${playerAction}</p>
					</div>
					<div class="right">
						<p>${opponentId}</p>
						<p>${opponentAction}</p>
					</div>
				</div>`;
			}
		});
		gameHistoryContainer.innerHTML = markup;
	}

	function actionsVisibility(show: boolean) {
		actionsContainer.style.display = show ? 'flex' : 'none';
	}

	function initGame(data: Array<any>) {
		actionsVisibility(true);

		let opponentId = data.find((p: any) => p.id !== player.id).id;
	}

	onMount(() => {
		io.on('userinfo', (id: string) => (player.id = id));

		io.on('status', (sm: string, d: any) => {
			status = sm;
			data = d;

			console.log(data);

			if (sm === 'gameStarted') initGame(d);
			else if (sm === 'gameEnded') showGameHistory(d);
			else if (sm === 'waitingForPlayers') return;
		});
	});
</script>

<div>
	<!-- bind:this istedenfor id="actionsContainer" og document.getElementById("actionsContainer"); -->
	<div bind:this={actionsContainer} style="display: none;">
		{#each actions as action}
			<button
				on:click={() => {
					io.emit('action', action.id);
					actionsVisibility(false);
				}}>{action.name}</button
			>
		{/each}
	</div>
</div>

<div class="game-history">
	<h2>Spillhistorikk</h2>
	<div bind:this={gameHistoryContainer} class="games-container" />
</div>

<style>
	:global(body) {
		display: grid;
		grid-template-columns: 1fr 1fr 28rem;

		margin: 0;
		padding: 0;
	}
	.game-history {
		height: calc(100vh - 2rem);
		padding: 1rem 2rem;
		border-left: 1px solid #0004;
	}

	.games-container {
		display: flex;
		flex-direction: column;
		gap: 1rem;

		width: 24rem;
		overflow-y: auto;
	}

	.games-container h2 {
		font-size: 1.2rem;
	}
	.games-container .right {
		text-align: right;
	}
</style>
