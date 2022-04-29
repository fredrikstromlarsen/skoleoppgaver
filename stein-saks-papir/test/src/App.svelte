<script>
	// Stein-, saks-, papirmekanikk
	let result = 0
	let test
	let machineAction
	const player = ["player", "machine", "tie"];

	export function game(playerAction){
		machineAction = Math.floor(Math.random() * 3);

		if (playerAction === machineAction) result = 2;
		else if ((playerAction + 1) % 3 === machineAction) result = 0;
		else result = 1;

		test = player[result]
	}

	

</script>

<main>
	<h1>{test}</h1>
	<button on:click={() => game(0)}>Stein</button>
	<button on:click={() => game(1)}>Saks</button>
	<button on:click={() => game(2)}>Papir</button>
</main>

<style>
	h1 {
		color: red;
	}
</style>