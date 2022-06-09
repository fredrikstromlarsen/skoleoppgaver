<script>
	// import { Socket } from 'socket.io-client';
	import { io } from '$lib/realtime';
	import { onMount } from 'svelte';

	let title, id, joinBtn, actionsContainer, gameHistory = [], playerScore = 0, opponentScore = 0, status, gameHistoryContainer;

	const actions = [ { id: 0, name: 'Stein' }, { id: 1, name: 'Saks' }, { id: 2, name: 'Papir' }, ],
	winMatrix = [ [2, 0, 1], [1, 2, 0], [0, 1, 2], ],
	outcomes = ['Vinn', 'Tap', 'Uavgjort'];

	onMount(() => {
		// Check for connection
		io.emit('gnopgnip', 'ping');
		let connectionAttempt = setInterval(
			() => io.emit('gnopgnip', 'ping'),
			2000
		);

		// Allow the player to join a game when connection is verified
		io.on('gnopgnip', (x) => {
			if (x == 'pingpong') {
				clearInterval(connectionAttempt);
				joinBtn.style.display = 'block';
				status.innerText = "Connected!"
			}
		});

		// Initialize the listeners
		io.on('gameStarted', startGame);
		io.on('waitingForPlayers', awaitingPlayers);
		io.on('gameFinished', (g) => gameEnded);
	});


	function joinGame() {
		// Hide join button, and send a join event to the server.
		joinBtn.style.display = 'none';
		status.innerText = "Attempting to join a room...";
		io.emit('join');
	}

	function startGame() {
		// Show action buttons
		actionsContainer.style.display = 'flex';
		status.innerText = "Game has started.";
		gameHistoryContainer.style.display = "flex";
	}

	function awaitingPlayers() {
		// Show waiting status
		actionsContainer.style.display = 'none';
		status.innerText = "Waiting for another player to connect...";
	}

	function sendAction(id) {
		// Send stein, saks or papir to server
		actionsContainer.style.display = 'none';
		status.innerText = `Sending "${actions[id].name}" to the server. Waiting for opponent`;
		io.emit('action', id);
	}

	function gameEnded(g) {
		// Add last played game to game history - svelte style (re-assigning the variable)
		gameHistory = [...gameHistory, g];
		let ps = 0, os = 0;

		// Check how many games the player has won/lost
		for (i = 0; i < gameHistory.length; i++) {
			let playerIndex = g.players.indexOf(io.id);
			let opponentIndex = [1, 0][playerIndex];

			let playerAction = g.actions[playerIndex];
			let opponentAction = g.actions[opponentIndex];

			let winner = winMatrix[playerAction][opponentAction];

			if (winner === 0) ps++;
			else if (winner === 1) os++;
		}
		playerScore= ps;
		opponentScore= os;
		status.innerText = "Game ended. Scores have been updated";
	}
</script>

<main>
	<div class="user-interactable">
		<!-- When clicked, triggerthe joinGame() function. Hidden by default.
		bind:this={variableName} is the same as doing variableName = document.getElementById("someId"); -->
		<button bind:this={joinBtn} on:click={joinGame} class="ghost" >Join</button>
		<div bind:this={actionsContainer} class="ghost actions-container">
			<!-- Each is the same as a forEach loop.
			Loop over the actions array to display 3 buttons with information from the array objects. -->
			{#each actions as action}
				<button on:click={() => sendAction(action.id)}> {action.name} </button>
			{/each}
		</div>
	</div>
	<div class="gamehistory-container ghost" bind:this={gameHistoryContainer}>
		<div class="gamehistory-item">
			<div class="split">
				<b>{playerScore}</b>
				<span>-</span>
				<b>{opponentScore}</b>
			</div>
		</div>
		<!-- Show all previous games in a list. Warning: ugly code ahead -->
		{#each gameHistory as game}
			<div class="gamehistory-item">
				<h2> {outcomes[winMatrix[game.actions[game.players.indexOf(io.id)]][game.actions[[1,0][game.players.indexOf(io.id)]]]]} </h2>
				<div class="split">
					<p>Deg</p>
					<span>-</span>
					<p>Emil</p>
				</div>
				<div class="split">
					<span>{actions[game.actions[game.players.indexOf(io.id)]].name}</span>
					<span>-</span>
					<span>{actions[game.actions[[1, 0][game.players.indexOf(io.id)]]].name}</span>
				</div>
			</div>
		{/each}
	</div>
	<span class="status-message" bind:this={status}>Not connected, please reload</span>
</main>

<style>
	main {
		display: grid;
		grid-template-columns: 1fr 10rem;
		gap: 1rem;
	}

	/* Hidden by default */
	.ghost {
		display: none;
	}

	/* Align children to each side, space in between */
	.split {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
	}


	.split > *:not(:first-child, :last-child) {
		text-align: center;
	}

	.split > *:last-child {
		text-align: right;
	}

	.actions-container {
		gap: 0.5rem;
	}

	.gamehistory-container {
		flex-direction: column-reverse;
		width: 100%;
	}

	.gamehistory-item {
		display: flex;
		flex-direction: column;
		padding: 0.5rem 1rem;
		gap: 0.5rem;
	}

	.gamehistory-item:not(:first-child) {
		border-bottom: 1px solid #0002;
	}

	.gamehistory-item * {
		margin: 0;
		padding: 0;
	}

	.status-message {
		position: fixed;
		bottom: 1rem;
		left: 1rem;

		font-size: 0.8rem;
		font-weight: bold;
	}
</style>
