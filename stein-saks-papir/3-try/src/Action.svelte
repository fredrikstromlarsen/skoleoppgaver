<script>
    export function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    export let actions;
    export let actionType;

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

<button> <!-- on:click={gameInit(action)}> -->
    <abbr title="{capitalize(actions[actionType][0])}">{actions[actionType][1]}</abbr>
</button>