module.exports = (app) => {
	const Elev = require('../controller/elev.controller');
	app.post('/elev', Elev.create);
	app.get('/elev', Elev.findAll);
	app.get('/elev/:ElevId', Elev.findOne);
	app.put('/elev/:ElevId', Elev.update);
	app.delete('/elev/:ElevId', Elev.delete);
	app.delete('/elev', Elev.deleteAll);
};