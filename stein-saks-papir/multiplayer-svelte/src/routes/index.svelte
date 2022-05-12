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

	// Kjører når siden laster inn hos klienten
	onMount(() => {
		io.on('waitingForPlayers', () => {
			// Venteanimasjon
			console.log('waitingForPlayers');
		});

		io.on('joinedLobby', (players) => {
			// Oppdaterer siden med gameid og opponentUsername
			// Gjør det mulig å starte spillet
			console.log('joinedLobby');
			console.log(players);
		});

		io.on('gameStarted', () => {
			// Gi spillere mulighet til å trykke stein saks eller papir.
			console.log('gameStarted');
			gameActive = true;
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
{#if gameActive === true}
	{#each actions as action}
		<button on:click={sendAction(action.id)}>{action.name}</button>
	{/each}
{/if}
