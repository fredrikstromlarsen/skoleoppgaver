const sql = require("./db.js");

/* Constructor */
const Elev = function (elev) {
    this.ElevID     = elev.Hobby;
    this.Fornavn    = elev.Fornavn;
    this.Etternavn  = elev.Etternavn;
    this.Klasse     = elev.Klasse;
    this.Hobby      = elev.Hobby;
    this.Kjonn      = elev.Kjonn;
    this.Datamaskin = elev.Datamaskin;
}

Elev.create = (newElev, result) => {
    sql.query("INSERT INTO Elev")
}