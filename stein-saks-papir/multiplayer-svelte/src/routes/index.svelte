<!-- CLIENT SIDE -->
<script lang="ts">
	import { io } from '$lib/realtime';
	import { onMount } from 'svelte';

	let userid: number;

	let gameHistory: Array<Object> = [];

	onMount(() => {
        // 

		io.on('game', (game) => {
			// Listen to the message event
			gameHistory = [...gameHistory, game];
		});
		io.on('result', (winner) => {
			
		});
		io.on('player', (uid) => {
			// Another listener for the name:
			userid = uid;
		});
	});


	function sendMessage(action: number) {
		if (!action) return;
		io.emit('message', action); // Send the message
	}
</script>

{#each gameHistory as game}
	<div>
		{game.result}
	</div>
{/each}

<button on:click={sendMessage(0)}>Stein</button>
<button on:click={sendMessage(1)}>Saks</button>
<button on:click={sendMessage(2)}>Papir</button>
