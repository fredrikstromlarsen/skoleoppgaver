<script>
    // Import values from App.svelte
    export let actions;
    export let machineAction;

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    
    // Initialize variables
    let machineAbbrContent, machineAbbrTitle, machineActions;
    
    function initializeMachine() {
        if (machineAction) {
            machineActions = actions[machineAction];
            machineAbbrTitle = capitalize(machineActions[0]);
            machineAbbrContent = machineActions[1];
        } else {
            machineAbbrTitle = "Waiting for you";
            machineAbbrContent = "❓";
        }
    }

    initializeMachine()
</script>

<style>
    h2 {
        text-align: right;
    }
    .machine-container {
        display: flex;
        justify-content: right;
        align-items: center;
        height: 100%;
        width: 100%;
    }
</style>

<div>
    <h2>Maskin</h2>
    <div class="machine-container">
        <abbr title="{machineAbbrTitle}">{machineAbbrContent}</abbr>
    </div>
</div>