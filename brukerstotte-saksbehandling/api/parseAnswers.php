<?php

$answers = json_decode(base64_decode($_POST["answers"]));
/* 
Inn:
[
    "hjelp/feil/annet", 
    "beskrivelse", 
    "url",
    "kategori",
    "e-post",  
] 
Ut:
{
    "type": "SRQ",
    "impact": 0,
    "urgency": 0,
    "description": "beskrivelse",
    "page": "url",
    "category": "kategori",
    "status": 0 (default),
    "registered": "2020-01-01 00:00:00",
    "started": null,
    "finislocalhosthed": null
    "email": "e-post"
}
*/

// Type: avhengig av kategori, beskrivelse og om det er en feil/endring/annet
// Endring = 1, Feil = 0.
// SRQ hvis Endring, og kategori == ...
$typeWeighing = [
    [
        0.75, // Hjelp
        0, // Feil
        1 // Annet
    ],
    [
        // Kategori
    ],
    [
        // Beskrivelse
    ]
];

switch ($answers[0]) {
    case 0:
        // hjelp
        break;
    case 1:
        // feil
        break;
    case 1:
        // annet
        break;
}

$description = $answers[1];
$page = $answers[2];
$category = substr($answers[3], 0, 31);
$status = 0;
$registered = new DateTime();
$started = null;
$finished = null;
$email = $answers[4];


// Impact/urgency
switch ($description) {
    case str_contains($description, "localhost"):
        $impact = 2;
        $urgency = 1;
        break;
    case str_contains($description, "youtu"):
        $impact = 3;
        $urgency = 2;
        break;
    case str_contains($description, "office"):
        $impact = 1;
        $urgency = 1;
        break;
    case str_contains($description, "viken"):
        $impact = 2;
        $urgency = 2;
        break;
}
