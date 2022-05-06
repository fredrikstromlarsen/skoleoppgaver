// SERVER SIDE

// Samme som React "useState".
// import { writable } from 'svelte/store';

export const io = new WebSocket('wss://127.0.0.1:3000');

io.addEventListener('open', () => {
    console.log("Socket open");
});

io.addEventListener('message', () => {
	console.log("Received data");
});

io.addEventListener('close', () => {
	console.log("Socket close");
});


export const test = "hello";