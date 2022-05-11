<!-- CLIENT SIDE -->
<script lang="ts">
	import { io } from '$lib/realtime';
	import { onMount } from 'svelte';
	import History from '../History.svelte';

	let username: string = '';
	let uid: string = '';
	export let gameHistory: Array<Object> = [];
	const actions: Array<any> = [
		{ id: 0, name: 'Stein' },
		{ id: 1, name: 'Saks' },
		{ id: 2, name: 'Papir' }
	];

	// Kjører når siden laster inn hos klienten
	onMount(() => {
		io.on('loaded', (userid) => {
			uid = userid;
			username = `user${uid}`;
		});

		io.on('waitingForLobby', () => {
			// Venteanimasjon
		});

		io.on('joinedLobby', (opponentUsername) => {
			// Oppdaterer siden med gameid og opponentUsername
			// Gjør det mulig å starte spillet
		});

		io.on('gameStarted', () => {
			// Gi spillere mulighet til å trykke stein saks eller papir.
		});

		io.on('gameEnded', (result) => {
			/* 
            result = {
                users: ['userid', 'userid'],
                actions: ['userid-action', 'userid-action'],
                winner: 'userid'
            } 
            */
			// gameHistory = [...gameHistory, result];
		});

		io.on('playAgain', () => {
			// Spill igjen mot samme spiller.
		});

		io.on('test', (value) => {
			console.log(value);
		});
	});

	// Sett brukernavn ved å sende til server sammen med brukerid.

	function setUsername() {
		return io.emit('setUsername', username);
	}

	function sendAction(action: number) {
		if (!action) return;
		io.emit('action', action); // Send the message
	}
</script>

<!-- {#if gameHistory.length > 0} -->
<!-- <History /> -->
<!-- {/if} -->
<div>
	<label for="usernameInput">Brukernavn: </label>
	<input
		id="usernameInput"
		type="text"
		pattern="[A-Za-z0-9-_.,:;\s]{(1, 24)}"
		bind:value={username}
		required
	/>
	<button on:click={setUsername}>Bytt brukernavn</button>
</div>

{#each actions as action}
	<button on:click={sendAction(action.id)}>{action.name}</button>
{/each}
