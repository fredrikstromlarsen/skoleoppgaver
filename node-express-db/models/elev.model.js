const sql = require("./db");

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
    sql.query("INSERT INTO Elev SET ?", newElev, (err, res) => {
        if (err)
            console.log("error: " + err);
        result(err, null);
        return;
    });
    console.log('created elev: ', { id: res.insertId, ...newElev });
	result(null, { id: res.insertId, ...newElev });
}

Elev.findById = (elevId, result) => {
	sql.query(
		`SELECT * FROM elev WHERE ElevID = ${elevId}`,
		(err, res) => {
			if (err) {
				console.log('error: ', err);
				result(err, null);
				return;
			}

			if (res.length) {
				console.log('found elev: ', res[0]);
				result(null, res[0]);
				return;
			}
			result({ kind: 'not_found' }, null);
		}
	);
};

Elev.getAll = (result) => {
	sql.query('SELECT * FROM elev', (err, res) => {
		if (err) {
			console.log('error: ', err);
			result(null, err);
			return;
		}

		console.log('elev: ', res);
		result(null, res);
	});
};

Elev.updateById = (id, elev, result) => {
	sql.query(
		'UPDATE elev SET Fornavn = ?, Etternavn = ?, Klasse = ?, Hobby = ?, Kjonn = ?, Datamaskin = ? WHERE ElevID = ?',
		[elev.Fornavn, elev.Etternavn, elev.Klasse, elev.Hobby, elev.Kjonn, elev.Datamaskin, id],
		(err, res) => {
			if (err) {
				console.log('error: ', err);
				result(null, err);
				return;
			}

			if (res.affectedRows == 0) {
				result({ kind: 'not_found' }, null);
				return;
			}

			console.log('updated elev: ', { id: id, ...elev });
			result(null, { id: id, ...elev });
		}
	);
};

Elev.remove = (id, result) => {
	sql.query('DELETE FROM elev WHERE ElevID = ?', id, (err, res) => {
		if (err) {
			console.log('error: ', err);
			result(null, err);
			return;
		}

		if (res.affectedRows == 0) {
			result({ kind: 'not_found' }, null);
			return;
		}

		console.log('deleted elev with id: ', id);
		result(null, res);
	});
};

Elev.removeAll = (result) => {
	sql.query('DELETE FROM elev', (err, res) => {
		if (err) {
			console.log('error: ', err);
			result(null, err);
			return;
		}

		console.log(`deleted ${res.affectedRows} elev`);
		result(null, res);
	});
};

module.exports = Elev;