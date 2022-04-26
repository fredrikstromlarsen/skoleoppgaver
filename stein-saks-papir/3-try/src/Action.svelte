<script>
    export function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    export let actionType;
    let action;

    switch(actionType) {
        case "rock":
            action = "✊";
            break;
        case "paper":
            action = "🖐️";
            break;
        case "scissor":
            action = "✌️";
            break;
    }

</script>

<style>
    button {
        padding: 1rem;
        margin: 0.5rem;

        font-size: 2rem;
    }
</style>

<button> <!-- on:click={gameInit(action)}> -->
    <abbr title="{capitalize(actionType)}">{action}</abbr>
</button>