<!-- CLIENT SIDE -->
<script lang="ts">
	import { io } from '$lib/realtime';
	import { onMount } from 'svelte';
	// import History from '../History.svelte';

	const actions: Array<any> = [
		{ id: 0, name: 'Stein' },
		{ id: 1, name: 'Saks' },
		{ id: 2, name: 'Papir' }
	];

	let status: string = '',
		data: Array<any> = [];

	function showInput() {
		var inputContainer: any = document.getElementById('actions');
		console.log('Setting visibility of input', inputContainer.style.display);
		if (inputContainer.style.display == 'none') inputContainer.style.display = 'flex';
		else inputContainer.style.display = 'none';
		console.log('Visibility of input has been set', inputContainer.style.display);
	}

	function sendAction(actionid: number) {
		if (!actionid) return;
		io.emit('action', actionid); // Send the message
	}

	function showResult(game: any) {
		const container = document.getElementById('result');
		const outcomes = ['Player1 wins', 'Player2 wins', 'Draw'];
		const winMatrix: Array<Array<number>> = [
			[2, 0, 1],
			[1, 2, 0],
			[0, 1, 2]
		];

		const win = winMatrix[game.player][game.computer];

		let markup: string = `
			<tr>
				<td>${game.player[0]}</td>
				<td>${game.player[1]}</td>
				<td>${outcomes[win]}</td>
			</tr>`;

		container?.insertAdjacentHTML('beforeend', markup);
	}

	// Kjører når siden laster inn hos klienten
	onMount(() => {
		io.on('status', (sm: string, d: Array<any>) => {
			console.log('status: ', sm, d);

			status = sm;

			if (sm === 'gameStarted') {
				showInput();
			} else if (sm === 'gameEnded') {
				showResult(d);
			}
		});
	});
</script>

<div>
	<p>Statusmelding: {status}</p>
	<p>Data: {[...data]} ({data.length})</p>
</div>
<div id="actions" style="display: none;">
	{#each actions as action}
		<button on:click={() => sendAction(action.id)}>{action.name}</button>
	{/each}
</div>
<table id="result" border="1">
	<tr>
		<th>Player1</th>
		<th>Player2</th>
		<th>Vinner</th>
	</tr>
</table>

<style>
	#actions {
		gap: 1rem;
	}
</style>
