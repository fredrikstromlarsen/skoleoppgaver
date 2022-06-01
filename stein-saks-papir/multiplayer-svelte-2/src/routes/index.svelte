<script>
	// import { Socket } from 'socket.io-client';
    import { io } from '$lib/realtime';
	import { onMount } from 'svelte';

    let title, id, joinBtn, actionsContainer, gameHistory = [];

    const actions = [
            { id: 0, name: "Stein" },
            { id: 1, name: "Saks" },
            { id: 2, name: "Papir" }
        ],
        winMatrix = [[2, 0, 1],[1, 2, 0],[0, 1, 2]],
        outcomes = ["Vinn", "Tap", "Uavgjort"];
        

    onMount(() => {
        console.log("Client mounted");

        io.emit("gnopgnip", "ping");
        io.on("gnopgnip", (x) => {
            if (x == "pingpong") {
                title.innerText = "Connected";
                id.innerText = `id: ${io.id}`;

                joinBtn.style.display = "block";
            }
        });

        io.on("gameStarted", startGame);
        io.on("waitingForPlayers", awaitingPlayers);
        io.on("gameFinished", (g) => gameHistory = [...gameHistory, g]);
    });

    function joinGame() {
        console.log("Attempting to join a game");

        joinBtn.style.display = "none";
        io.emit("join");
    }

    function startGame() {
        /* 
        Show action buttons
        */
        console.log("joined game");
        console.log("game started");

        actionsContainer.style.display = "flex";
    }

    function awaitingPlayers() {
        /* 
        Show waiting status
        */
        console.log("joined game");
        console.log("game is waiting for another player");
    }

    function sendAction (id) {
        console.log(`Sending "${actions[id].name}" to server`);

        actionsContainer.style.display = "none";
        io.emit("action", id);
    }
    
</script>


<!-- START OF TEMPORARY -->
<div>
    <h1 bind:this={title}>Not connected</h1>
    <p bind:this={id}></p>
</div>
<!-- END OF TEMPORARY -->

<main>
    <button bind:this={ joinBtn } on:click={ joinGame } class="ghost">Join</button>
    <div bind:this={ actionsContainer } class="ghost actions-container">
        {#each actions as action}
            <button on:click={ () => sendAction(action.id) }>{action.name}</button>
        {/each}
    </div>
</main>
<div class="sidebar">
    {#each gameHistory as game}
        <h2>{outcomes[winMatrix[game.players.indexOf(io.id)][[1,0][game.players.indexOf(io.id)]]]}</h2>
        <div>
            <p>{game.players[game.players.indexOf(io.id)]}</p>
            <p>{game.actions[game.players.indexOf(io.id)]}</p>
        </div>
        <div>
            <p>{game.players[[1, 0][game.players.indexOf(io.id)]]}</p>
            <p>{game.actions[[1, 0][game.players.indexOf(io.id)]]}</p>
        </div>
    {/each}
</div>

    
<style>
    .ghost {
        display: none;
    }

    .actions-container {
        gap: 0.5rem;
    }
</style>