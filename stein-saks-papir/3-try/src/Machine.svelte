<script>
    
    export let machineMarkup;
    export let actions;
    
    let gameStarted = 0;
    let machineAction;
    let randomIndex;

    export function chooseAction() {
        randomIndex = Math.floor(Math.random() * actions.length);
        return actions[randomIndex];
    }

    export function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    if (gameStarted === 0) {
        machineAction = chooseAction();
        machineMarkup = '<abbr title="' + capitalize(machineAction[0]) + '">' + machineAction[1] + '</abbr>';
    } else machineMarkup = '<abbr title="Waiting for you">❓</abbr>';
</script>

<style>
    h2 {
        text-align: right;
    }

    abbr {
        margin: 8rem 0 0 auto !important;
    }
</style>

<div>
    <h2>Maskin</h2>
    <div>
        {@html machineMarkup} 
    </div>
</div>