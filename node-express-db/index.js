const http = require('http');
const url = require('url');
const fs = require('fs');
const express = require('express');
const app = express();
const port = 8080;
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
		console.log('MySQL query ran');
	});
});
app.use(express.static(`public`));
app.set('views', './views');
app.set('view engine', 'ejs');

app.listen(port, () => console.info(`App listening on port ${port}`))
