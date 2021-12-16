function updateAlternatives(count) {
	document.getElementById('question').innerHTML = jsonData[count][0];
	for (i = 0; i < jsonData[count][1].length; i++)
		answerButtons[i].innerHTML = jsonData[count][1][i];
	stopTime = 0;
	startTime = new Date().getTime();
	liveTimer = setInterval(() => {
		timerElement.innerHTML = Math.floor(
			(new Date().getTime() - startTime) / 1000
		);
	}, 200);
}
function checkAnswer(i) {
	clearInterval(liveTimer);
	if (incrementScore) clearInterval(incrementScore);
	timerElement.innerHTML = 0;
	stopTime = new Date().getTime();
	timeSpent = stopTime - startTime;
	questionCount++;
	body.style.animation = '';
	if (jsonData[count][2] == i) {
		body.style.animation = 'correct 1s ease-in-out';
		oldScore = scoreCount;
		n = 100;
		incrementScore = Math.floor(1000 - (timeSpent / 100) * ((timeSpent / 1000) * 0.5)) 
		
		if (incrementScore > 50) scoreCount += incrementScore;
		else scoreCount += 50;
		incrementScore = setInterval(() => {
			if (oldScore + n <= scoreCount) {
				oldScore += n;
				scoreCountElement.innerHTML = oldScore;
			} else n = n / 10;
			if (oldScore == scoreCount || n < 1) clearInterval(this);
		}, 50);
	} else {
		body.style.animation = 'wrong 1s ease-in-out';
	}
	questionCountElement.innerHTML = questionCount + ' / ' + questionCountTotal;
	setTimeout(() => {
		body.style.animation = '';
	}, 1000);
	if (count < jsonData.length - 1) {
		count++;
		updateAlternatives(count);
	} else {
		document.getElementById('gameOver').style.display = 'block'
		document.getElementById('resultQuestions').innerHTML = questionCount + " / " + questionCountTotal;
		document.getElementById('resultScore').innerHTML = scoreCount;
	};
}

const jsonData = [
	[
		'What is the scientific name of a butterfly?',
		['Apis', 'Coleoptera', 'Formicidae', 'Rhopalocera'],
		3,
	],
	[
		'How hot is the surface of the sun?',
		['1,233 K', '5,778 K', '12,130 K', '101,300 K'],
		1,
	],
	[
		'Who are the actors in The Internship?',
		[
			'Ben Stiller, Jonah Hill',
			'Courteney Cox, Matt LeBlanc',
			'Kaley Cuoco, Jim Parsons',
			'Vince Vaughn, Owen Wilson',
		],
		3,
	],
	[
		'What is the capital of Spain?',
		['Berlin', 'Buenos Aires', 'Madrid', 'San Juan'],
		2,
	],
	[
		'What are the school colors of the University of Texas at Austin?',
		[
			'Black, Red',
			'Blue, Orange',
			'White, Burnt Orange',
			'White, Old gold, Gold',
		],
		2,
	],
	[
		'What is 70 degrees Fahrenheit in Celsius?',
		['18.8889', '20', '21.1111', '158'],
		2,
	],
	[
		'When was Mahatma Gandhi born?',
		[
			'October 2, 1869',
			'December 15, 1872',
			'July 18, 1918',
			'January 15, 1929',
		],
		0,
	],
	[
		'How far is the moon from Earth?',
		[
			'7,918 miles (12,742 km)',
			'86,881 miles (139,822 km)',
			'238,400 miles (384,400 km)',
			'35,980,000 miles (57,910,000 km)',
		],
		2,
	],
	['What is 65 times 52?', ['117', '3120', '3380', '3520'], 2],
	[
		'How tall is Mount Everest?',
		[
			'6,683 ft (2,037 m)',
			'7,918 ft (2,413 m)',
			'19,341 ft (5,895 m)',
			'29,029 ft (8,847 m)',
		],
		3,
	],
	[
		'When did The Avengers come out?',
		['May 2, 2008', 'May 4, 2012', 'May 3, 2013', 'April 4, 2014'],
		1,
	],
	[
		'What is 48,879 in hexadecimal?',
		['0x18C1', '0xBEEF', '0xDEAD', '0x12D591'],
		1,
	],
];
var count = 0,
	scoreCount = 0,
	stopTime = 0,
	n = 100,
	liveTimer,
	incrementScore,
	questionCountTotal = jsonData.length,
	questionCount = 0,
	timerElement = document.getElementById('timeSpent'),
	questionCountElement = document.getElementById('questionCount'),
	scoreCountElement = document.getElementById('scoreCount');

const answerButtons = document.querySelectorAll('.btnAlt');
const body = document.querySelector('body');
updateAlternatives(count);
questionCountElement.innerHTML = questionCount + ' / ' + questionCountTotal;
scoreCountElement.innerHTML = scoreCount;