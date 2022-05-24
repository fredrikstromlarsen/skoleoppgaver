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
						let players = [],
							queue = [],
							gameHistory = [];

						io.on('connection', (socket) => {
							// Player joined
							// Find room !-> Create room
							// Start game <- Wait for players
							// Gather both player actions
							// Send result && register game in gameHistory
							// Play again || Join new game

							socket.on('changeName', (username) => {
								// Dunno if this works
								socket.username = username;
							});

							if (players.length < 2) {
								let username = `Player${players.length + 1}`;
								socket.username = username;

								players.push(socket);
								socket.emit('uid', socket);

								console.log(`${socket.username} connected. id: ${socket.id}`);

								if (players.length === 2) {
									io.emit('status', 'gameStarted', players, '');

									let game = [
										{ playerid: `${players[0]}`, action: '' },
										{ playerid: `${players[1]}`, action: '' }
									];

									socket.on('action', (actionid) => {
										console.log(
											`Received action from Player${players.indexOf(socket.id) + 1}`,
											actionid
										);
										let playerindex = game.indexOf({ playerid: socket.id, action: '' });
										game[playerindex].action = actionid;

										// Reassign variable to update its value globally.
										game = [...game];
									});

									if (game[0].action != '' && game[1].action != '') {
										io.emit('status', 'gameEnded', players, game);
										gameHistory = [...gameHistory, game];
									}
								} else socket.emit('status', 'waitingForPlayers', players, '');
							} else {
								let username = `Spectator${queue.length + 1}`;
								socket.username = username;

								queue.push(socket);
								queue = [...queue];

								socket.emit('uid', socket);
								socket.emit('status', 'gameFull', players, queue);
							}

							socket.on('disconnect', () => {
								// Check if socket.id is in the players array
								// If so, remove it from the game
								let x = players.indexOf(socket.id);
								if (x > -1) {
									console.log('Player left the game', socket.id);
									players.splice(x, 1);

									// Send status message
									io.emit('status', 'waitingForPlayers', players, '');

									// Check if there are players in queue. If so, move the first spectator to players array.
									if (queue.length != 0) {
										players.push(queue[0]);
										queue.splice(0, 1);

										players = [...players];
										queue = [...queue];

										io.emit('status', 'gameStarted', players, '');
									}
								} else console.log('A player left the queue');
							});
						});
					}
				}
			]
		}
	}
};

export default config;
