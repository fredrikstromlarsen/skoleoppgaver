<script>
    import Action from './Action.svelte';

    export let actions;
    export let playerAction;
    export function setPlayerAction(a){
        console.log(a);
        playerAction = [actions[a][0], actions[a][1]];
        playerAction
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
    <p>{ playerAction }</p>
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