<script>
	import Player from './Player.svelte'
	import Machine from './Machine.svelte'
	import Score from './Score.svelte'

	let scoreBoard = {"player": 0, "machine": 0}
</script>

<header>
	<h2>Deg</h2>
	<Score status={scoreBoard} />
	<h2>Maskin</h2>
</header>
<main>
	<Player />
	<!-- <Result result={} /> -->
	<Machine />
</main>

<style>
	header, main {
		display: flex;
		padding: 1rem 2rem;
	}

	header {
		justify-content: space-around;
		align-items: center;
	}

	main {
		justify-content: space-between;
	}
</style>