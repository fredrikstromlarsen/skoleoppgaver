<!-- CLIENT SIDE -->
<script lang="ts">
    import { test } from "../stores/store";   
    import { io } from "$lib/realtime";
    import { onMount } from 'svelte';
    
    // Runs on page load (client side)
	onMount(() => {
        console.log("onMount()");
        // Connect to the socket.io server
        const io = require('socket.io-client');
        const socket = io('http://localhost:3000');

        console.log(io, socket);
	});

    console.log(test);


    let count = 100;

</script>

<svelte:head>
    <title>Stein Saks Papir</title>
</svelte:head>

<h1>{count}</h1>
<button on:click={()=>{count=count+1}}>+</button>
<button on:click={()=>{count=count-1}}>-</button>
<button on:click={()=>{count=0}}>Reset</button>