const express = require('express');
const app = express();
const port = 80;
const mysql = require('mysql');
var con = mysql.createConnection({
	host: 'localhost',
	user: 'root',
	password: '',
	database: 'Dromtorp',
});
var query = 'SELECT * FROM elev';

con.connect(function (err) {
	if (err) throw err;
	console.log('Connected to MySQL DB');
	con.query(query, function (err, result) {
		if (err) throw err;
		app.get('/', (req, res) => {
			res.send(result);
		});
	});
});

app.listen(port, () => {
	console.log(`Example app listening at http://localhost:${port}`);
});
