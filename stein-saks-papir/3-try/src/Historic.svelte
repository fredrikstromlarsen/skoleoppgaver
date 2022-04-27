<script>
    import Score from './Score.svelte';

    export function getResult(actions) {
        if (actions[0] === actions[1]) return 2;
        else if (
            (actions[0] === 0 && actions[1] === 2) ||
            (actions[0] === 1 && actions[1] === 0) ||
            (actions[0] === 2 && actions[1] === 1)
        ) return 0;
        else return 1;
    }
    
    export let scoreBoard;
 
    let gameHistory = [
        [0, 1],
        [2, 1],
        [0, 2],
        [1, 1],
        [0, 2]
    ];

    export let actions;
    const results = ["🟩", "🟥", "🟨"];

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

</style>


<div class="container">
    <Score status={scoreBoard} />

    <div class="data-container">
        <!-- % Win/lose/draw, total games -->
    </div>

    <div class="result-container">
        {#each gameHistory.reverse() as game}
        <div class="item">
            <span>{actions[game[0]][1]}</span>
            <span>{results[getResult([game[0], game[1]])]}</span>
            <span>{actions[game[1]][1]}</span>
        </div>
        {/each}
    </div>
</div>