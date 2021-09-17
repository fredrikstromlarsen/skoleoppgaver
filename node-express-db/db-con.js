var mysql = require('mysql');
var con = mysql.createConnection({
	host: 'localhost',
	user: 'root',
	password: '',
	database: 'Dromtorp',
});
var query = "SELECT * FROM elev";

con.connect(function (err) {
	if (err) throw err;
	console.log('Connected to MySQL DB');
	con.query(query, function (err, result) {
		if (err) throw err;
		console.log(result);
	});
});
