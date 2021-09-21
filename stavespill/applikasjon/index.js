const express = require('express');
const app = express();
const port = 80;

const mysql = require('mysql');
const con = mysql.createConnection({
	host: 'localhost',
	user: 'root',
	password: '',
	database: 'stavespill',
});
con.connect(function (err) {
	if (err) throw err;
	console.log('Connected to MySQL DB');

	var query = 'SELECT * FROM words';
    con.query(query, function (err, result) {
		if (err) throw err;
        console.log('"' + query + '" ran with no errors.');
        
        // Start en nettside på localhost:80, og benytt ./public mappa for statiske assets
        app.listen(port, () => app.use(express.static(`public`)));
        
        
	});
});
