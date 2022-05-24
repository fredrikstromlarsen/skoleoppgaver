<!-- CLIENT SIDE -->
<script lang="ts">
	import { io } from '$lib/realtime';
	import { Socket } from 'socket.io-client';
	// import History from 'src/History.svelte';
	import { onMount } from 'svelte';

	const actions: Array<any> = [
		{ actionid: 0, name: 'Stein' },
		{ actionid: 1, name: 'Saks' },
		{ actionid: 2, name: 'Papir' }
	];
	export let status: string = '',
		players: Array<any> = [],
		userid: string,
		playerName: string,
		actionsContainer: any;

	function showResult(game: any) {
		const winMatrix: Array<Array<number>> = [
			[2, 0, 1],
			[1, 2, 0],
			[0, 1, 2]
		];
	}

	onMount(() => io.on('uid', (uid: string) => (userid = uid)));

	io.on('status', (sm: string, p: Array<string>, d: any) => {
		status = sm;
		players = p;

		console.log('status: ', sm, 'players: ', p, 'data: ', d);

		if (sm === 'gameStarted') initGame();
		else if (sm === 'gameFinished') showResult(d);
		else if (sm === 'gameFull') return;
		else if (sm === 'waitingForPlayers') showLobby();
	});

	function initGame() {
		if (actionsContainer) {
			actionsContainer.style.display = 'flex';
		} else alert("Couldn't find actionsContainer");
	}

	function showLobby() {
		if (actionsContainer) {
			actionsContainer.style.display = 'none';
		}
	}
</script>

<div>
	<p>Statusmelding: {status}</p>
	<p>Data: {players.toString()}</p>
	<p>You: {playerName} (id: {userid})</p>
</div>

<div bind:this={actionsContainer} style="display: none;">
	{#each actions as action}
		<button on:click={() => io.emit(action.id)}>{action.name}</button>
	{/each}
</div>
