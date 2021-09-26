const mysql = require('mysql');
const databaseConfig = require('./config/db.config.js');

/* database configuration credentials */
var con = mysql.createConnection({
	host: databaseConfig.HOST,
	user: databaseConfig.USER,
	password: databaseConfig.PASSWORD,
	database: databaseConfig.DB,
});
/* sql query */
var query = 'SELECT * FROM elev';

/* connect to database */
con.connect(function (err) {
	if (err) throw err;
	console.log('Connected to database');
});

module.exports = connection;
