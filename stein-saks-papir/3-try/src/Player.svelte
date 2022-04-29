<script>
    import Action from './Action.svelte';

    // Imported values from App.svelte
    export let actions;

    // Export values to be used in other components
    export let playerAction = 0;
    export function setPlayerAction(actionType) {
        playerAction = actionType;
    }
</script>

<style>
    .player {
        height: 100%;
        width: 1fr;

        display: flex;
        flex-direction: column;

        gap: 0.75rem;
    }
</style>

<div>
    <h2>Deg</h2>
    <!-- <p>{ actions[playerAction][1] }</p> -->
    <div class="player">
        <Action actionType={0} {actions} {setPlayerAction} />
        <Action actionType={1} {actions} {setPlayerAction} />
        <Action actionType={2} {actions} {setPlayerAction} />
        <!-- <Action actionType={3} {actions}/> --> <!-- Automatic: Only Scissor -->
        <!-- <Action actionType={4} {actions}/> --> <!-- Automatic: Only Rock -->
        <!-- <Action actionType={5} {actions}/> --> <!-- Automatic: Only Paper -->
        <!-- <Action actionType={6} {actions}/> --> <!-- Automatic: Random values--> 
    </div>
</div>