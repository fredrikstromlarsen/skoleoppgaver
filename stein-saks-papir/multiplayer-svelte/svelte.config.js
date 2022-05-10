import adapter from '@sveltejs/adapter-auto';
import preprocess from 'svelte-preprocess';
import { Server } from 'socket.io';

/** @type {import('@sveltejs/kit').Config} */
const config = {
	// Consult https://github.com/sveltejs/svelte-preprocess
	// for more information about preprocessors
	preprocess: preprocess(),

	kit: {
		adapter: adapter(),
		vite: {
			plugins: [
				{
					name: 'sveltekit-socket-io',
					configureServer(server) {
						const io = new Server(server.httpServer);

						io.on('connection', (socket) => {
							// Generate a random username and send it to the client to display it
							let username = Math.round(Math.random() * 999999);
							let gameHistory = [];
							let actions = [];
							let winner;
							const winMatrix = [
								[2, 0, 1],
								[1, 2, 0],
								[0, 1, 2]
							];

							socket.emit('name', username);

							// Receive incoming messages and broadcast them
							socket.on('action', (action, player) => {
								// Wait for both actions to be sent,
								// then save the game on the server
								// in gameHistory. Emit result to
								// both clients.
								actions[player] = action;
								if (actions[0] && actions[1]) {
									winner = winMatrix[actions[0]][actions[1]];
									io.emit('result', winner);
									gameHistory = [...gameHistory, [...actions, winner]];
								}
								io.emit('game', {
									player: id,
									message: message
									// time: new Date().toLocaleString()
								});
							});
						});
					}
				}
			]
		}
	}
};

export default config;
