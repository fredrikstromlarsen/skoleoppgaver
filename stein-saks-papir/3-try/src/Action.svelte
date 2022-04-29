<script>
    export function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Imported values from Player.svelte
    export let actions;
    export let actionType;
    // export let playerAction;
    export let setPlayerAction;

</script>

<style>
    button {
        height: 7rem;
        width: 7rem;

        border-radius: 0.25rem; 

        background: #f2f2f2;
        border: none;
    }
</style>

<!-- <button on:click={playerAction=actionType}>  -->
<button on:click={setPlayerAction(actionType)}> 
    <abbr title="{capitalize(actions[actionType][0])}">{actions[actionType][1]}</abbr>
</button>