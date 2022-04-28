<script>
    export let actions;
    
    let gameStarted = 0;
    let machineAction, randomIndex, machineAbbrContent, machineAbbrTitle;

    export function chooseAction() {
        randomIndex = Math.floor(Math.random() * actions.length);
        return actions[randomIndex];
    }

    export function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }


    if (gameStarted === 1) {
        machineAction = chooseAction();
        machineAbbrTitle = capitalize(machineAction[0]);
        machineAbbrContent = machineAction[1];
    } else {
        machineAbbrTitle = "Waiting for you...";
        machineAbbrContent = "❓";
    }
</script>

<style>
    h2 {
        text-align: right;
    }
</style>

<div>
    <h2>Maskin</h2>
    <div>
        <abbr title="{machineAbbrTitle}">{machineAbbrContent}</abbr>
    </div>
</div>