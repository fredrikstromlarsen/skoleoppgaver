var http = require('http');
var url = require('url');
var fs = require('fs');

http.createServer(function (req, res) {
	var q = url.parse(req.url, true);
	var filename = '.' + q.pathname;
	fs.readFile(filename, function (err, data) {
		if (err && q != '/') {
			res.writeHead(404, { 'Content-Type': 'text/html' });
			return res.end('This file does not exist');
		}
		res.writeHead(200, { 'Content-Type': 'text/html' });
		res.write(data);
		res.end();
	});
}).listen(80);
