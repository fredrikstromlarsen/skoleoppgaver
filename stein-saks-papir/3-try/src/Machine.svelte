<script>
    function chooseAction() {
        availableActions = ["rock", "paper", "scissor"];
        randomIndex = Math.floor(Math.random() * availableActions.length);
        return availableActions[randomIndex];
    }

    const machine = document.getElementById("machine");

    if (gameStarted === 1) {
        machineAction = chooseAction();
        machine.innerHTML = machineAction;
    } else {
        machine.innerHTML = '<abbr title="Waiting for you">❓</abbr>';
    }
</script>

<style>
</style>

<div id="machine">
</div>