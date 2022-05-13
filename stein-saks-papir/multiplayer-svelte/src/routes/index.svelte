<!-- CLIENT SIDE -->
<script lang="ts">
	import { io } from '$lib/realtime';
	import { onMount } from 'svelte';
	import History from '../History.svelte';

	let username: string, uid: number;
	let gameActive: boolean = false;
	export let gameHistory: Array<object>;
	const actions: Array<object> = [
		{ id: 0, name: 'Stein' },
		{ id: 1, name: 'Saks' },
		{ id: 2, name: 'Papir' }
	];

	// Testing
	let waitingForPlayers: boolean = false,
		joinedLobby: boolean = false;
	let status: string = '';
	let players: Array<String> = [];

	// Kjører når siden laster inn hos klienten
	onMount(() => {
		io.on('waitingForPlayers', () => {
			// Venteanimasjon
			console.log('waitingForPlayers');
			waitingForPlayers = true;
		});

		io.on('gameStarted', (game) => {
			// Gi spillere mulighet til å trykke stein saks eller papir.
			console.log('gameStarted');
			gameActive = true;
			players = game.players;
		});

		io.on('gameEnded', (result) => {
			gameActive = false;
			/* 
            result = {
                users: ['userid', 'userid'],
                actions: ['userid-action', 'userid-action'],
                winner: 'userid'
            } 
            */
			// gameHistory = [...gameHistory, result];
			console.log('gameEnded, result: ', result);
		});

		// io.emit('playAgain', () => {
		// Spill igjen mot samme spiller.
		// console.log('playAgain');
		// });
		io.on('status', (message) => {
			status = message;
			if (message === 'opponentDisconnected') {
				console.log('Opponent disconnected');

				// Reload siden
				window.location.reload(true);
			}
		});

		io.on('test', (value) => {
			console.log(value);
		});
	});

	function sendAction(action: number) {
		if (!action) return;
		io.emit('action', action); // Send the message
	}
</script>

<!-- {#if gameHistory.length > 0} -->
<!-- <History /> -->
<!-- {/if} -->
<div>
	<p>joinedLobby: {joinedLobby.toString()}</p>
	<p>players: {players[0]} & {players[1]}</p>
	<p>gameActive: {gameActive.toString()}</p>
	<p>status: {status}</p>
</div>

{#if gameActive === true}
	{#each actions as action}
		<button on:click={sendAction(action.id)}>{action.name}</button>
	{/each}
{/if}
