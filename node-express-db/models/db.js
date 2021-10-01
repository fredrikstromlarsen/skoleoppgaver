const mysql = require('mysql');
const databaseConfig = require('../config/db.config');
var con = mysql.createConnection({
	host: databaseConfig.HOST,
	user: databaseConfig.USER,
	password: databaseConfig.PASSWORD,
	database: databaseConfig.DB,
});
con.connect(function (err) {
	if (err) throw err;
	console.log('Connected to database');
});
module.exports = con;
