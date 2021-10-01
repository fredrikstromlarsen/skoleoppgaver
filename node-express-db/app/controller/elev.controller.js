const Elev = require('../models/elev.model.js');
exports.create = (req, res) => {
	if (!req.body) {
		res.status(400).send({
			message: 'Content can not be empty!',
		});
	}
	const elev = new Elev({
		Fornavn: req.body.Fornavn,
		Etternavn: req.body.Etternavn,
		Klasse: req.body.Klasse,
		Hobby: req.body.Hobby,
		Kjonn: req.body.Kjonn,
		Datamaskin: req.body.Datamaskin,
	});
	Elev.create(elev, (err, data) => {
		if (err)
			res.status(500).send({
				message:
					err.message ||
					'Some error occurred while creating the Elev.',
			});
		else res.send(data);
	});
};
exports.findAll = (req, res) => {
	Elev.getAll((err, data) => {
		if (err)
			res.status(500).send({
				message:
					err.message ||
					'Some error occurred while retrieving elever.',
			});
		else res.send(data);
	});
};
exports.findOne = (req, res) => {
	Elev.findById(req.params.elevId, (err, data) => {
		if (err) {
			if (err.kind === 'not_found') {
				res.status(404).send({
					message: `Not found Elev with id ${req.params.elevId}.`,
				});
			} else {
				res.status(500).send({
					message:
						'Error retrieving Elev with id ' +
						req.params.elevId,
				});
			}
		} else res.send(data);
	});
};
exports.update = (req, res) => {
	// Validate Request
	if (!req.body) {
		res.status(400).send({
			message: 'Content can not be empty!',
		});
	}

	Elev.updateById(
		req.params.elevId,
		new Elev(req.body),
		(err, data) => {
			if (err) {
				if (err.kind === 'not_found') {
					res.status(404).send({
						message: `Not found Elev with id ${req.params.elevId}.`,
					});
				} else {
					res.status(500).send({
						message:
							'Error updating Elev with id ' +
							req.params.elevId,
					});
				}
			} else res.send(data);
		}
	);
};
exports.delete = (req, res) => {
    Elev.remove(req.params.elevId, (err, data) => {
        if (err) {
            if (err.kind === "not_found") {
                res.status(404).send({
                    message: `Not found Elev with id ${req.params.elevId}.`
                });
            } else {
                res.status(500).send({
                    message: "Could not delete Elev with id " + req.params.elevId
                });
            }
        } else res.send({ message: `Elev was deleted successfully!` });
    });
};

exports.deleteAll = (req, res) => {
	Elev.removeAll((err, data) => {
		if (err)
			res.status(500).send({
				message:
					err.message ||
					'Some error occurred while removing all elever.',
			});
		else res.send({ message: `All Elever were deleted successfully!` });
	});
};

