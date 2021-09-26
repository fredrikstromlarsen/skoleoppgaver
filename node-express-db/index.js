/* const bodyParser = require('body-parser'); */
const express = require('express');
const app = express();
const port = 8080;

/* parse json requests. */
app.use(express.json());
/* parse x-www-form-urlencoded requests. */
app.use(express.urlencoded({ extended: true }));

/* set port, listen for requests */
app.listen(port, () => {
	console.log(`Server is running on port ${port}`);
});

/* simple route */
app.get('/', (req, res) => {
	res.json({ message: "Hello World, This Works" });
});