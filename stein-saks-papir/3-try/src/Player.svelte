<script>
    import Action from './Action.svelte';

    export let actions;
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
    <div class="player">
        
        <Action actionType={0} {actions}/>
        <Action actionType={1} {actions}/>
        <Action actionType={2} {actions}/>
    </div>
</div>