<script>
	// import { Socket } from 'socket.io-client';
	import { io } from '$lib/realtime';
	import { onMount } from 'svelte';

	let title,
		id,
		joinBtn,
		actionsContainer,
		gameHistory = [];

	const actions = [
			{ id: 0, name: 'Stein' },
			{ id: 1, name: 'Saks' },
			{ id: 2, name: 'Papir' },
		],
		winMatrix = [
			[2, 0, 1],
			[1, 2, 0],
			[0, 1, 2],
		],
		outcomes = ['Vinn', 'Tap', 'Uavgjort'];

	onMount(() => {
		console.log('Client mounted');

		io.emit('gnopgnip', 'ping');
		let connectionAttempt = setInterval(
			() => io.emit('gnopgnip', 'ping'),
			2000
		);

		io.on('gnopgnip', (x) => {
			if (x == 'pingpong') {
				clearInterval(connectionAttempt);

				title.innerText = 'Connected';
				id.innerText = `id: ${io.id}`;

				joinBtn.style.display = 'block';
			}
		});

		io.on('gameStarted', startGame);
		io.on('waitingForPlayers', awaitingPlayers);
		io.on('gameFinished', (g) => (gameHistory = [...gameHistory, g]));
	});

	function joinGame() {
		console.log('Attempting to join a game');

		joinBtn.style.display = 'none';
		io.emit('join');
	}

	function startGame() {
		/* 
        Show action buttons
        */
		console.log('joined game');
		console.log('game started');

		actionsContainer.style.display = 'flex';
	}

	function awaitingPlayers() {
		/* 
        Show waiting status
        */
		console.log('joined game');
		console.log('game is waiting for another player');

		actionsContainer.style.display = 'none';
	}

	function sendAction(id) {
		console.log(`Sending "${actions[id].name}" to server`);

		actionsContainer.style.display = 'none';
		io.emit('action', id);
	}
</script>

<!-- START OF TEMPORARY -->
<div>
	<h1 bind:this={title}>Not connected</h1>
	<p bind:this={id} />
</div>
<!-- END OF TEMPORARY -->

<main>
	<div class="user-interactable">
		<button bind:this={joinBtn} on:click={joinGame} class="ghost"
			>Join</button
		>
		<div bind:this={actionsContainer} class="ghost actions-container">
			{#each actions as action}
				<button on:click={() => sendAction(action.id)}>
					{action.name}
				</button>
			{/each}
		</div>
	</div>
	<div class="gamehistory-container">
		{#each gameHistory as game}
			<div class="gamehistory-item">
				<h2>
					{outcomes[
						winMatrix[game.actions[game.players.indexOf(io.id)]][
							game.actions[[1, 0][game.players.indexOf(io.id)]]
						]
					]}
				</h2>
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
</main>

<style>
	main {
		display: grid;
		grid-template-columns: 1fr 10rem;
		gap: 1rem;
	}

	.ghost {
		display: none;
	}

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
		display: flex;
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
</style>
