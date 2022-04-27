<script>
    export let status; // = {"player": 0, "machine": 0}
</script>

<style>
    h2 {
        text-align: center;
    }
</style>

<h2>
    <span>{status["player"]}</span>
    -
    <span>{status["machine"]}</span>
</h2>