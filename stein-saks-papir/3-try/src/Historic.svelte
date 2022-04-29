<script>
    // Imported values from App.svelte and Score.svelte
    export let scoreboard;
    export let gameHistory;
    export let actions;
    export let getResult;
    
    // Exported values
    const resultEmojis = ["🟩", "🟥", "🟨"];

    function percentage(score, totalGames=gameHistory.length) {
        return Math.round((score / totalGames) * 100);
    }

    let winPercentage = percentage(scoreboard[0]);
    let losePercentage = percentage(scoreboard[1]);
    // let drawPercentage = percentage(gameHistory.length - scoreboard.reduce((x,y) => x + y));
    let drawPercentage = percentage(gameHistory.length - scoreboard[0] - scoreboard[1]);

</script>

<style>
    .result-container {
        height: 70vh;
        width: min-content;

        margin: auto;

        overflow-y: auto;
    }

    .item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        
        padding: 1rem 2rem;
        width: 10rem;
        
        font-size: 1.4rem;
    }

    .item:not(:last-child) {
        border-bottom: 1px solid #ddd;
    }

    h2 {
        text-align: center;
    }
</style>


<div class="container">
    <h2>
        <span>{scoreboard["player"]}</span>
        -
        <span>{scoreboard["machine"]}</span>
    </h2>

    <div class="data-container">
        <p><span>{winPercentage}%</span> wins</p>
        <p><span>{drawPercentage}%</span> draws</p>
        <p><span>{losePercentage}%</span> losses</p>
    </div>

    <div class="result-container">
        {#each gameHistory.reverse() as game}
        <div class="item">
            <span>{actions[game[0]][1]}</span>
            <span>{resultEmojis[getResult(game[0], game[1])]}</span>
            <span>{actions[game[1]][1]}</span>
        </div>
        {/each}
    </div>
</div>