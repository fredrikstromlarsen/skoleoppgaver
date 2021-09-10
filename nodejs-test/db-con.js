var mysql = require('mysql')

var con = mysql.createConnection({
    host: "localhost",
    user: "fredrik",
    password: ""
})

con.connect(function (err) {
    if (err) throw err;
    console.log("Connected to MySQL DB");
    con.query("SHOW DATABASES", function (err, result) {
        if (err) throw err;
        console.log(result);
    });
});