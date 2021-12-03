const placesArr = [
	{
		name: 'Oslo',
		lat: 59.91474087395643,
		lon: 10.749578342601996,
	},
	{ name: 'Bergen', lat: 60.39157561373171, lon: 5.321394090914947 },
	{ name: 'Tromsø', lat: 69.65031588066095, lon: 18.951394588316383 },
	{ name: 'Bodø', lat: 67.28200155894797, lon: 14.401972120068379 },
];
async function getData(cityIndex) {
    document.getElementById('main').style.display = 'block';
	let city = placesArr[cityIndex];
	let url = 'https://api.met.no/weatherapi/locationforecast/2.0/compact?lat=' + city['lat'] + '&lon=' + city['lon'];

	let responseObject = await fetch(url, {
		'Mode': 'cors',
		'Accept': 'application/json',
		'Content-Type': 'application/json',
		'User-Agent': "Ina's Vær-App/0.0.1 jicobet118@shirulo.com",
	});
	let dataObject = await responseObject.text();
	let dataJson = JSON.parse(dataObject);
    console.log(dataJson.properties.timeseries[2].data.instant.details.air_temperature);

    document.getElementById('place').innerHTML = city['name'];
    
}