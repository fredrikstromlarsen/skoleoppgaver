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
						let game = [],
							queue = [],
							gameHistory = [];

						io.on('connection', (socket) => {
							// Player joined
							// Find room !-> Create room
							// Start game <- Wait for game
							// Gather both player actions
							// Send result && register game in gameHistory
							// Play again || Join new game

							if (game.length < 2) {
								game.push({ id: socket.id, action: null });
								socket.emit('userinfo', socket.id);

								console.log('Game array updated: ', game);

								// If two users are in the game, start it
								console.log('Game.length ===', game.length);
								if (game.length === 2) {
									io.emit('status', 'gameStarted', game);

									socket.on('action', (actionid) => {
										let playerIndex = game.findIndex((player) => player.id === socket.id);
										game[playerIndex].action = actionid;

										console.log(`Received action from ${game[playerIndex].id}: `, actionid);

										if (game[0].action !== null && game[1].action !== null) {
											gameHistory = [...gameHistory, game];
											io.emit('status', 'gameEnded', gameHistory);
											console.log('Game History:<br>', gameHistory);
										}
									});
								} else socket.emit('status', 'waitingForgame', game, []);
							}

							socket.on('disconnect', () => {
								// Check if socket.id is in the game array
								// If so, remove it from the game
								let x = game.findIndex((player) => player.id === socket.id);
								if (x > -1) {
									console.log(`${socket.id} left the game`);

									game.splice(x, 1);

									// Check if there are game in queue. If so, move the first spectator to game array.
									if (queue.length != 0) {
										game.push({ id: queue[0], action: null });
										queue.splice(0, 1);

										io.emit('status', 'gameStarted', game);
									} else io.emit('status', 'waitingForGame', game);
								} else {
									// Check if socket.id is in the queue array
									// If so, remove it from the queue
									let y = queue.indexOf(socket.id);
									if (y > -1) {
										console.log(`${socket.id} left the queue`);
										queue.splice(y, 1);
									}
								}
							});
						});
					}
				}
			]
		}
	}
};

export default config;
