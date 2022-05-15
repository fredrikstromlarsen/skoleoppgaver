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
						let gameHistory = [];

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
								// TODO: Join room

								if (players.length === 2) {
									socket.emit('status', 'gameStarted', []);

									let game = [
										{ playerid: `${players[0].id}`, action: '' },
										{ playerid: `${players[1].id}`, action: '' }
									];
									let winner;

									socket.on('action', (actionid) => {
										let playerindex = game.indexOf({ playerid: socket.id, action: '' });
										game[playerindex].action = actionid;

										// Reassign variable to update its value globally.
										game = [...game];
									});

									if (game[0].action != '' && game[1].action != '') {
										winner = winMatrix[game[0].action][game[1].action];
										socket.emit('status', 'gameEnded', [game, winner]);
										gameHistory = [...gameHistory, game];
									}
								}
							}
							socket.on('disconnect', () => {
								// Check if socket.id is in the players array
								// If so, remove it from the game
								if (players.indexOf(socket) > -1) {
									console.log("Player removed from game", socket.id);
									players.splice(players.indexOf(socket), 1);
								} else
									console.log("A player left the queue");
							});
						});
					}
				}
			]
		}
	}
};

export default config;
