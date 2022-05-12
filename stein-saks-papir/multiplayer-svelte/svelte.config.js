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
						let gameHistory = [],
							games = [];

						io.on('connection', (socket) => {
							function findGame() {
								// Find a game with just one player in it
								return games.find((game) => game.players.length === 1);
							}

							function createGame(socket) {
								// Create a new game and join it
								const game = {
									id: games.length,
									players: [socket.id]
								};
								socket.join(game.id);
								socket.emit('waitingForPlayers', game);
								joinedGameID = game.id;
								games = [...games, game];
							}
							// Run findGame(), then createGame() if there is none available.
							let availableGame = findGame();
							let joinedGameID;

							if (availableGame) {
								// Join the game
								availableGame.players.push(socket.id);
								games[availableGame.id] = availableGame;
								socket.join(availableGame.id);
								socket.emit('joinedLobby', availableGame.players);
								socket.to(availableGame.id).emit('gameStarted', availableGame);

								joinedGameID = availableGame.id;
							} else createGame(socket);

							// io.to(joinedGameID).emit('gameEnded', result);
							// io.to(joinedGameID).on('playAgain', () => {
							// 
							// })
							/*
							socket.on('action', (action, player) => {
								// Wait for both actions to be sent,
								// then save the game on the server
								// in gameHistory. Emit result to
								// both clients.
								actions[player] = action;
								if (actions[0] && actions[1]) {
									winner = winMatrix[actions[0]][actions[1]];
									
									game = {
										players: [gameid['users'][0], gameid['users'][1]],
										actions: [actions[0], actions[1]],
										winner: gameid['users'][winner]
									};
									gameHistory = [...gameHistory, [...actions, winner]];
									io.to(joinedGameID).emit('gameEnded', {
										gameHistory: gameHistory,
										message: message
										// time: new Date().toLocaleString()
									});
									
								}
							});
							*/
							socket.on('disconnect', () => {
								// Remove user from game
								games.splice(games[joinedGameID].players[socket.id], 1);

								// Check if game is empty
								if (games[joinedGameID].players.length === 0) {
									// Remove game
									games.splice(joinedGameID, 1);
								}
								// Update list of games
								games = [...games];
							});
						});
					}
				}
			]
		}
	}
};

export default config;
