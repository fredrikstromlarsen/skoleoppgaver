/* const bodyParser = require('body-parser'); */
const express = require('express');
const app = express();
const port = 8080;

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
require('./app/routes/elev.routes.js')(app);
app.listen(port, () => {
	console.log(`Server is running on port ${port}`);
});
app.get('/', (req, res) => {
	res.json({ message: "Hello World, This Works" });
});