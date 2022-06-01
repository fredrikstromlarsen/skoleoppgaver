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

						let room = {
								id: 0,
								players: [],
								gid: null,
							},
							gameHistory = [];

						function newGame(rid) {
							gameHistory[gameHistory.length] = {
								players: room.players,
								actions: [],
								room: rid,
								time: new Date().toLocaleString,
							};
							return gameHistory.length - 1;
						}

						io.on('connection', (socket) => {
							socket.on('gnopgnip', (x) => {
								socket.emit('gnopgnip', `${x}pong`);
							});

							socket.on('join', () => {
								/* Find room with 1 player
									if found:
										add player to room
										send success status
									else:
										create room
										wait for players
								*/
								console.log(
									`${socket.id} wants to join a room`
								);

								room.players[room.players.length] = socket.id;
								socket.room = room.id;

								console.log(
									`${socket.id} joined room ${room.id}`
								);

								if (room.players.length == 2) {
									console.log(
										`game at room ${room.id} is starting`
									);

									let gid = newGame(room.id);
									room.gid = gid;

									console.log('room:', room);

									io.emit('gameStarted');
									// socket.to(room.players[0]).to(room.players[1]).emit('gameStarted');
								} else {
									console.log(
										`room ${room.id} is waiting for another player`
									);
									socket.emit('waitingForPlayers');
								}
							});

							socket.on('action', (id) => {
								let playerIndex = gameHistory[
									room.gid
								].players.indexOf(socket.id);
								gameHistory[room.gid].actions[playerIndex] = id;

								let currentGame = gameHistory[room.gid];

								if (
									currentGame.actions[0] != null &&
									currentGame.actions[1] != null
								) {
									io.emit('gameFinished', currentGame);
									io.emit('gameStarted');
								}
							});

							socket.on('disconnect', () => {
								let x = room.players.indexOf(socket.id);

								if (x > -1) {
									room.players.splice(x, 1);
									io.emit('waitingForPlayers');

									console.log(
										`${socket.id} has disconnected from game ${socket.room}:`,
										room
									);
								}
							});
						});
					},
				},
			],
		},
	},
};

export default config;
