<script lang="ts">
	export let gameHistory: Array<any>;
</script>

{#each gameHistory as game}
	<script lang="ts">
		export let gameResults: Array<String> = [game.users[0], game.users[1], 'Tie'];
	</script>
	<div>
		<span>{game.users[0]}</span>
		<span>{game.users[1]}</span>
	</div>
	<div>
		<span>{game.actions[0]}</span>
		<span>{game.actions[1]}</span>
	</div>
	<div>
		<span>{gameResults[game.winner]}</span>
	</div>
{/each}
