<script>
    export let status; // = {"player": 0, "machine": 0}
</script>

<div class="scoreboard">
    <h2>
        <span>{status["player"]}</span>
        -
        <span>{status["machine"]}</span>
    </h2>
</div>