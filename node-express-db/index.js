const http = require('http');
const url = require('url');
const fs = require('fs');
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
		console.log('MySQL query ran');
	});
});

app.listen(port, () =>
	console.info(`App listening on port ${port}`)
	
	app.use(express.static(`public`))
);
/* 
http.createServer(function (req, res) {
	var q = url.parse(req.url, true);
	var filename = '.' + q.pathname;
	console.log(`filename = ${filename}`);

	fs.readFile(filename, function (err, data) {
		if (err && q != '/') {
			res.writeHead(404, { 'Content-Type': 'text/html' });
			return res.end('This file/folder does not exist');
		}
		res.writeHead(200, { 'Content-Type': 'text/html' });
		res.write(
			data
		);
		res.end();
	});
}).listen(80);
 */