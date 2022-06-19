<?php
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
    "finished": null
    "email": "e-post"
}
*/

// if (!isset($_POST["answers"])) die(header("Location: ../"));

// Verbose error logging
ini_set('display_errors', 1);
ini_set('error_reporting', -1);

// Connect to DB
require("./connect.php");

// Sorta secure, but not really.
$answers = str_replace(" ", "+", json_decode(base64_decode($_POST["answers"])));

// Don't look
$description = base64_decode($answers[1]);
$page = base64_decode($answers[2]);
$category = substr(base64_decode($answers[3]), 0, 31);
$registered = new DateTime();
$registered = $registered->format('Y-m-d H:i:s');
$email = base64_decode($answers[4]);
$urgency = 3;
$impact = 3;

// Impact/urgency
// This project is for a not-so-important task, don't worry.
// 3 (low), 1 (high)
switch ($description) {
    case str_contains($description, "youtu") || str_contains($page, "youtu"):
        $impact = 3;
        $urgency = 3;
        break;
    case str_contains($description, "localhost") || str_contains($page, "localhost") || str_contains($description, "viken") || str_contains($page, "viken"):
        $impact = 3;
        $urgency = 2;
    case str_contains($description, "office") || str_contains($page, "office"):
        $impact = 3;
        $urgency = 1;
}

// Incident, Change, Service Request, Problem
$types = ["INC", "CHG", "SRQ", "PRB"];

// Type: dependent on category, description, and whether it's an error, change or other
$typeWeights = [[], [], []];

// Magic.
$answerWeights = [
    [
        "Jeg trenger hjelp" => [3, 0, 3, 0],
        "Jeg vil rapportere en feil" => [2, 1, 2, 1],
        "Noe annet" => [0, 0, 0, 0]
    ],
    [
        "Registreringsfeil" => [2, 0, 3, 1],
        "Glemt passord" => [0, 0, 3, 0],
        "Feil passord/brukernavn" => [2, 0, 3, 0],
        "Ingen tilgang" => [3, 1, 2, 1],
        "Programvarefeil" => [3, 0, 1, 1],
        "Manglende utstyr" => [1, 0, 3, 0],
        "Endring av rettigheter" => [0, 1, 3, 0],
        "Programvareinstallasjon" => [2, 0, 2, 0],
        "Skrivefeil/grammatikk" => [0, 3, 0, 0],
        "Ingen internettilkobling" => [3, 0, 2, 1],
        "Brannmurendring" => [0, 3, 2, 0],
        "Blokkert nettsted" => [1, 0, 2, 1],
        "Virus/skadevare" => [3, 0, 1, 3],
        "Sikkerhetshull" => [3, 1, 0, 2],
        "Feil med utstyr" => [0, 1, 3, 0]
    ]
];

$typeWeights[0] = $answerWeights[0][base64_decode($answers[0])];
$typeWeights[1] = $answerWeights[1][base64_decode($answers[3])];

$typeWeights[2] = [0, 0, 0, 0];
switch ($description) {
    case str_contains($description, "endring"):
    case str_contains($description, "endre"):
        $typeWeights[2] = [0, 3, 2, 0];
        break;
    case str_contains($description, "bytte"):
        $typeWeights[2] = [0, 2, 3, 0];
        break;
    case str_contains($description, "hendelse"):
    case str_contains($description, "feil"):
    case str_contains($description, "gal"):
        $typeWeights[2] = [3, 0, 2, 1];
        break;
}

function sum_multi($arr, $template)
{
    $sum = $template;
    $sum = array_map(function ($a, $b, $c) {
        return $a + $b + $c;
    }, ...$arr);
    return $sum;
}

$typeWeightResults = sum_multi($typeWeights, [0, 0, 0, 0]);
$typeIndex = array_search(max($typeWeightResults), $typeWeightResults);
$type = $types[$typeIndex];

if (
    in_array($category, [
        "Registreringsfeil",
        "Glemt passord",
        "Feil passord/brukernavn",
        "Ingen tilgang",
        "Programvarefeil",
        "Manglende utstyr",
        // "Blokkert nettsted",
        "Programvareinstallasjon"
    ])
) {
    $urgency = 3;
    $impact = 3;
}


switch ($category) {
    case "Brannmurendring":
        $urgency = 2;
        $impact = 1;
        if ($type == "SRQ")
            $type = "CHG";
        break;
    case "Virus/skadevare":
    case "Sikkerhetshull":
        $urgency = 1;
        $impact = 2;
        break;
}

if ($type == "INC") {
    if (
        $urgency < 3 &&
        $impact < 3 &&
        ($urgency == 1 || $impact == 1)
    ) {
        $type = "MI";
    } else {
        $urgency = 2;
        $impact = 3;
    }
}

// Encode user inputted values to base64
$description = base64_encode($description);
$page = base64_encode($page);
$category = base64_encode($category);
$email = base64_encode($email);

echo "type: $type<br>impact: $impact<br>urgency: $urgency<br>description: " . base64_decode($description) . "<br>page: " . base64_decode($page) . "<br>category: " . base64_decode($category) . "<br>status: 0<br>registered: $registered<br>email: " . base64_decode($email);

// Insert into DB
$sql = "INSERT INTO `tickets` (`type`, `impact`, `urgency`, `description`, `category`, `page`, `status`, `registered`, `started`, `finished`, `email`) VALUES ('$type', $impact, $urgency, '$description', '$category', '$page', 0, '$registered', NULL, NULL, '$email');";

// Execute $sql
$result = $connection->query($sql);
$connection->close();
