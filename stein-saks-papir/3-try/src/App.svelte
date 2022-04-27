<script>
	import Player from './Player.svelte';
	import Machine from './Machine.svelte';
	import Historic from './Historic.svelte';

	export let scoreBoard = {"player": 0, "machine": 0};
    export const actions = [
		["rock", "✊"],
        ["paper", "🖐"],
        ["scissor", "✌"]
	];
</script>

<style>
	main {
		display: grid;
		grid-template-columns: repeat(3, 1fr);
		padding: 1rem 2rem;
	}

</style>

<main>
	<Player {actions} />
	<Historic {actions} {scoreBoard} />
	<Machine {actions} />
</main>