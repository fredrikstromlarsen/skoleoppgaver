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
						let users = [];

						io.on('connection', (socket) => {
							// Tilfeldig base64
							// let gameHistory = [];
							let actions = [];
							let winner;
							const userid = Date.now();
							let username = `user${userid}`;

							users[userid] = username;
							io.emit('loaded', userid);

							socket.on('setUsername', (name) => {
								users[userid] = name;
								io.emit('test', users);
							});

							/*/
							 * winMatrix[0][0] = stein + stein = uavgjort (2),
							 * winMatrix[0][1] = stein + saks = spiller0 (0),
							 * winMatrix[0][2] = stein + papir = spiller1 (1),
							/*/
							const winMatrix = [
								[2, 0, 1],
								[1, 2, 0],
								[0, 1, 2]
							];

							// socket.emit('name', value);

							// Receive incoming messages and broadcast them
							socket.on('action', (action, player) => {
								// Wait for both actions to be sent,
								// then save the game on the server
								// in gameHistory. Emit result to
								// both clients.
								actions[player] = action;
								if (actions[0] && actions[1]) {
									winner = winMatrix[actions[0]][actions[1]];
									/*
									game = {
										players: [gameid['users'][0], gameid['users'][1]],
										actions: [actions[0], actions[1]],
										winner: gameid['users'][winner]
									};
									gameHistory = [...gameHistory, [...actions, winner]];
									io.emit('gameEnded', {
										gameHistory: gameHistory,
										message: message
										// time: new Date().toLocaleString()
									});
									*/
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
