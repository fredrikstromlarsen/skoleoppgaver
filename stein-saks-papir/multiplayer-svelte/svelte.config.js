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
						const io = new Server(server.httpServer),
							winMatrix = [
								[2, 0, 1],
								[1, 2, 0],
								[0, 1, 2]
							];
						let players = [];

						io.on('connection', (socket) => {
							// Player joined
							// Find room !-> Create room
							// Start game <- Wait for players
							// Gather both player actions
							// Send result && register game in gameHistory
							// Play again || Join new game
							console.log(socket.id);
							if (players.length < 2) {
								players.push(socket);
							}
							if (players.length === 2) {
								socket.emit('status', 'gameStarted');

								let actions = {};
								let winner;

								socket.on('action', (actionid) => {
									actions[socket.id] = actionid;
									actions = [...actions];
								});

								if (actions.length === 2) {
									winner = winMatrix[actions[0]][actions[1]];
								}
							}
						});
					}
				}
			]
		}
	}
};

export default config;
