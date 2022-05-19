<!-- CLIENT SIDE -->
<script lang="ts">
	import { io } from '$lib/realtime';
	import { Socket } from 'socket.io-client';
	import { onMount } from 'svelte';

	const actions: Array<any> = [
		{ id: 0, name: 'Stein' },
		{ id: 1, name: 'Saks' },
		{ id: 2, name: 'Papir' }
	];
	let status: string = '',
		data: Array<any> = [],
		id: string;

	function showResult(game: any) {
		const winMatrix: Array<Array<number>> = [
			[2, 0, 1],
			[1, 2, 0],
			[0, 1, 2]
		];
	}

	onMount(() => {
		io.on('uid', (uid: string) => {
			id = uid;
		});
	});
	io.on('status', (sm: string, d: Array<any>) => {
		statusMessage(sm, d);
	});

	function statusMessage(sm: string, d: Array<any>) {
		console.log('status: ', sm, d);
		status = sm;
		data = d;
	}
</script>

<div>
	<p>Statusmelding: {status}</p>
</div>

<div>
	You: {id}
	Opponent: {data[0].id} or {data[1].id}
</div>

<!-- <div id="actions" style="display: none;"> -->
<!-- {#each actions as action} -->
<!-- <button on:click={() => sendAction(action.id)}>{action.name}</button> -->
<!-- {/each} -->
<!-- </div> -->
