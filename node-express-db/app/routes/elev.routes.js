module.exports = (app) => {
	const elev = require('../controllers/elev.controller.js');
	app.post('/elev', elev.create);
	app.get('/elev', elev.findAll);
	app.get('/elev/:ElevId', elev.findOne);
	app.put('/elev/:ElevId', elev.update);
	app.delete('/elev/:ElevId', elev.delete);
	app.delete('/elev', elev.deleteAll);
};