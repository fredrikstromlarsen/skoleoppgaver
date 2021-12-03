const bg = document.getElementById('bg');
const header = document.getElementById('header');
const geolocation = document.getElementById('geolocation');
const timestamp = document.getElementById('timestamp');
const dataContainer = document.getElementById('times');
const compass = [
	'N',
	'NN&Oslash;',
	'N&Oslash;',
	'&Oslash;N&Oslash;',
	'&Oslash;',
	'&Oslash;S&Oslash;',
	'S&Oslash;',
	'SS&Oslash;',
	'S',
	'SSW',
	'SW',
	'WSW',
	'W',
	'WNW',
	'NW',
	'NNW',
];
var n = 50;
var c = 27;

var url, positionInfo;
function getLocation(index) {
    const cityLocationJSON = [
		// { UserLocation: getLocation() },
		{ Oslo: [59.91474087395643, 10.749578342601996] },
		{ Bergen: [60.39157561373171, 5.321394090914947] },
		{ Stavanger: [58.96863957875448, 5.72668425117518] },
		{ Trondheim: [63.43069936054296, 10.399965715812566] },
		{ Tromsø: [69.65031588066095, 18.951394588316383] },
		{ Bodø: [67.28200155894797, 14.401972120068379] },
		{ Lofoten: [67.99999285165653, 13.643100455501799] },
		{ Galdhøpiggen: [61.636574298247254, 8.31251289573659] },
	];
	var cityLocation = cityLocationJSON[0][index];
    console.log(cityLocation);
	navigator.geolocation.getCurrentPosition(function (position) {
		geolocation.innerHTML = 'Locating...';
		timestamp.innerHTML = 'Checking...';

		var d = new Date(position.timestamp);
		var month = Number(d.getMonth() + 1);
		var date = d.getDate();
		var hour = d.getHours();
		var minute = d.getMinutes();

		if (month < 10) {
			var month = '0' + Number(d.getMonth() + 1);
		}
		if (date < 10) {
			var date = '0' + d.getDate();
		}
		if (hour < 10) {
			var hour = '0' + d.getHours();
		}
		if (minute < 10) {
			var minute = '0' + d.getMinutes();
		}

		let positionInfo = {
			lat: position.coords.latitude,
			lon: position.coords.longitude,
			acc: position.coords.accuracy,
			timestamp: hour + ':' + minute + ', ' + date + '/' + month,
		};

		let url =
			'https://api.met.no/weatherapi/locationforecast/2.0/compact?lat=' +
			positionInfo.lat +
			'&lon=' +
			positionInfo.lon;
		var updateTimestamp = positionInfo.timestamp;

		findCity();
		getData(url);

		function findCity() {
			fetch(
				'https://geocode.xyz/' +
					positionInfo.lat +
					',' +
					positionInfo.lon +
					'?json=1'
			)
				.then(function (response) {
					return response.json();
				})
				.then(function (data) {
					if (data.region) {
						geolocation.innerHTML = data.region;
						timestamp.innerHTML = 'Last Update: ' + updateTimestamp;
					} else setTimeout(findCity(), 1000);
				});
		}

		async function getData() {
			let responseObj = await fetch(url, {
				"mode": 'cors',
				"Accept": 'application/json',
				'Content-Type': 'application/json',
				'User-Agent': 'WeatherApp/0.0.3 qdfibtlbl@relay.firefox.com',
			});
			let responseData = await responseObj.text();
			let responseDataJson = JSON.parse(responseData);
			if (!document.getElementById('w2t')) {
				addRows(n);

				function addRows(n) {
					for (i = 2; i <= n - 1; i++) {
						dataContainer.innerHTML +=
							'<div class="row"><div class="cell" id="w' +
							i +
							'ts"></div><div class="cell" id="w' +
							i +
							't"></div><div class="cell" id="w' +
							i +
							'ws"></div><div class="cell" id="w' +
							i +
							'wd"></div><div class="cell" id="w' +
							i +
							'h"></div><div class="cell" id="w' +
							i +
							'caf"></div><div class="cell" id="w' +
							i +
							'pa"></div></div>';
					}
				}
			}
			setTimeout(function () {
				for (i = 2; i <= n; i++) {
					document.getElementById('w' + i + 'pa').innerHTML =
						responseDataJson.properties.timeseries[i].data
							.next_1_hours.details.precipitation_amount + ' mm';
					document.getElementById('w' + i + 'caf').innerHTML =
						responseDataJson.properties.timeseries[i].data.instant
							.details.cloud_area_fraction + '%';
					document.getElementById('w' + i + 'h').innerHTML =
						responseDataJson.properties.timeseries[i].data.instant
							.details.relative_humidity + '%';
					document.getElementById('w' + i + 'wd').innerHTML =
						compass[
							Math.round(
								responseDataJson.properties.timeseries[i].data
									.instant.details.wind_from_direction /
									22.5 +
									0.5
							) - 1
						] +
						' / ' +
						responseDataJson.properties.timeseries[i].data.instant
							.details.wind_from_direction +
						'&#176;';
					document.getElementById('w' + i + 'ws').innerHTML =
						responseDataJson.properties.timeseries[i].data.instant
							.details.wind_speed + ' m/s';
					document.getElementById('w' + i + 't').innerHTML =
						responseDataJson.properties.timeseries[i].data.instant
							.details.air_temperature + ' C&#176;';

					let wtsm = responseDataJson.properties.timeseries[
						i
					].time.slice(5, -13);
					let wtsd = responseDataJson.properties.timeseries[
						i
					].time.slice(8, -10);
					let wtst = responseDataJson.properties.timeseries[
						i
					].time.slice(11, -4);
					document.getElementById('w' + i + 'ts').innerHTML =
						wtst + ', ' + wtsd + '/' + wtsm;
				}
			}, 200);
		}
	});
}
window.onload = function () {
	getLocation(0);
};
