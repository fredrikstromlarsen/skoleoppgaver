<!-- CLIENT SIDE -->
<script lang="ts">
	import { io } from '$lib/realtime';
	import { Socket } from 'socket.io-client';
	import { onMount } from 'svelte';

	// Create typescript type: Dictionary
	interface Dictionary<T> {
	    [Key: string]: T;
	}	

	const actions: Array<any> = [
			{ actionid: 0, name: 'Stein' },
			{ actionid: 1, name: 'Saks' },
			{ actionid: 2, name: 'Papir' }
		],
		// 2 = tie, 0 = player, 1 = opponent
		winMatrix: Array<Array<number>> = [
			[2, 0, 1],
			[1, 2, 0],
			[0, 1, 2]
		];

	const player: Dictionary<any> = {
		id: "",
		name: "",
		index: 0
	},
	opponent: Dictionary<any> = {
		name: "",
		index: 0
	};

	let status: string = '',
		data: any = [],
		gameHistory: Array<any> = [],
		actionsContainer: any;

	function showResult(game: any) {
		// Get index of player & opponent
		player.index = game.findIndex((p: any) => p.id === player.id);
		opponent.index = game.findIndex((p: any) => p.id !== player.id);

		// Get player & opponent actions
		const playerAction = game[player.index].action;
		const opponentAction = game[opponent.index].action;

		// Get result
		const result = winMatrix[playerAction][opponentAction];

		return result;
	}

	io.on('userinfo', (id: string, un: string) => {
		player.id = id;
		player.name = un;
	});

	io.on('status', (sm: string, d: any) => {
		status = sm;

		console.log('status: ', sm, 'data: ', d);

		player.index = d.findIndex((player: any) => player.id === player.id);
		opponent.index = d.findIndex((player: any) => player.id !== player.id);

		if (sm === 'gameStarted') initGame(d);
		else if (sm === 'gameFinished') showResult(d);
		else if (sm === 'gameFull') return;
		else if (sm === 'waitingForPlayers') showLobby();
	});

	function actionsVisibility(show: boolean) {
		actionsContainer.style.display = show ? 'flex' : 'none';
	}

	function initGame(data: Array<any>) {
		actionsVisibility(true);

		opponent.name
	}

	function showLobby() {
		actionsVisibility(false);
	}
</script>

<div>
	<p>Statusmelding: {status}</p>
	<p>Data: {data.toString()}</p>
	<p>You: {player.name}</p>
	<p>Opponent: {opponent.name}</p>
</div>

<!-- bind:this istedenfor id="actionsContainer" og document.getElementById("actionsContainer"); -->
<div bind:this={actionsContainer} style="display: none;">
	{#each actions as action}
		<button
			on:click={() => {
				io.emit(action.id);
				actionsVisibility(false);
			}}>{action.name}</button
		>
	{/each}
</div>

{#if gameHistory.length > 0}
	<div>
		<h2>Spillhistorikk</h2>
		{#each gameHistory as archivedGame}
			<script lang="ts">
				let playerIndex: number = archivedGame.findIndex((p: Array<any>) => p.id === player.id);
				let opponentIndex: number = archivedGame.findIndex((p: Array<any>) => p.id !== player.id);
				let x: number = winMatrix[archivedGame[playerIndex].action][archivedGame[opponentIndex].action];
			</script>
			<div>
				<p>
					{archivedGame[0].username} - {archivedGame[1].username}
				</p>
				<p>
					{#if x === 0}
						<span class="winner">{archivedGame[0].action}</span> -
						<span class="loser">{archivedGame[1].action}</span>
					{:else if x === 1}
						<span class="loser">{archivedGame[0].action}</span> -
						<span class="winner">{archivedGame[1].action}</span>
					{:else if x === 2}
						<span class="tie">{archivedGame[0].action}</span> -
						<span class="tie">{archivedGame[1].action}</span>
					{/if}
				</p>
			</div>
		{/each}
	</div>
{/if}
