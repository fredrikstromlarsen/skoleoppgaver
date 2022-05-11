<!-- CLIENT SIDE -->
<script lang="ts">
	import { io } from '$lib/realtime';
	import { onMount } from 'svelte';

	let gameHistory: Array<Object> = [];
	let gameResults: Array<String> = ['', '', 'Uavgjort'];

	// Kjører når siden laster inn hos klienten
	onMount(() => {
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
			gameHistory = [...gameHistory, result];
		});

		io.on('playAgain', () => {
			// Spill igjen mot samme spiller.
		});
	});

	function sendMessage(action: number) {
		if (!action) return;
		io.emit('message', action); // Send the message
	}
</script>

{#if gameHistory.length > 0}
	{#each gameHistory as game}
		<div>
			<span>{game.users[0]}</span>
			<span>{game.users[1]}</span>
		</div>
		<div>
			<span>{game.actions[0]}</span>
			<span>{game.actions[1]}</span>
		</div>
		<div>
			<!-- Hvis en av spillerene vant: vis vinneren, ellers, vis "Tie". -->
			{(gameResults = [game.users[0], game.users[1], 'Tie'])}
			{gameResults[game.winner]}`}
		</div>
	{/each}
{/if}

<button on:click={sendMessage(0)}>Stein</button>
<button on:click={sendMessage(1)}>Saks</button>
<button on:click={sendMessage(2)}>Papir</button>
