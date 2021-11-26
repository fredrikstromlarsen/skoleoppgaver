const express = require('express');
const app = express();
const port = 3000;

const cors = require('cors');

const mysql = require('mysql');
const con = mysql.createConnection({
	host: 'localhost',
	port: '3306',
	user: 'root',
	password: '',
	database: 'Dromtorp',
});

app.use(cors());

app.get('/', (req, res) => {
	res.send();

	con.connect();
	con.query('SELECT * FROM elev', function (error, results, fields) {
		if (error) throw error;
		console.log('Output of sql statement: ' + JSON.stringify(results));
	});
	con.end();
});

app.listen(port, () => {
	console.log(`Example server listening on port ${port}`);
});
