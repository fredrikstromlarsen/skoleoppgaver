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

ini_set('display_errors', 1);
ini_set('error_reporting', -1);

// Connect to DB
require("./connect.php");

// $answers = str_replace(" ", "+", json_decode(base64_decode($_POST["answers"])));

// file_put_contents("test.txt", $_POST["answers"]);
// die();
$test = file_get_contents("test.txt");
$answers = json_decode(base64_decode($test));

$description = base64_decode($answers[1]);
$page = base64_decode($answers[2]);
$category = substr(base64_decode($answers[3]), 0, 31);
$registered = new DateTime();
$registered = $registered->format('Y-m-d H:i:s');
$email = base64_decode($answers[4]);
$urgency = 3;
$impact = 3;

// Impact/urgency
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

$types = [
    "INC" => 0,
    "CHG" => 0,
    "SRQ" => 0,
    "PRB" => 0
];
// Type: avhengig av kategori, beskrivelse og om det er en feil/endring/annet
$typeWeights = [[], [], []];
$answerWeights = [
    [
        "Jeg trenger hjelp" => [1, 0, 1, 0],
        "Jeg vil rapportere en feil" => [1, 1, 0, 1],
        "Noe annet" => [1, 1, 1, 1]
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
        "Sikkerhetshull" => [1, 3, 0, 2],
        "Feil med utstyr" => [0, 1, 3, 0]
    ]
];

$typeWeights[0] = $answerWeights[0][base64_decode($answers[0])];
$typeWeights[1] = $answerWeights[1][base64_decode($answers[3])];
echo "answer = " . $answers[3] . "<br>answerWeights[0][answer] = " . print_r($answerWeights[1][base64_decode($answers[3])], true) . "<br>typeWeights = " . print_r($typeWeights, true);
// 1+1, 1+0, 1+2, 1+1
// 2, 1, 3, 2

$typeWeights[2] = [0, 0, 0, 0];
switch ($description) {
    case str_contains($description, "endring") || str_contains($description, "endre"):
        $typeWeights[2] = [0, 3, 2, 0];
        break;
    case str_contains($description, "bytte"):
        $typeWeights[2] = [0, 2, 3, 0];
        break;
    case str_contains($description, "hendelse") || str_contains($description, "feil") || str_contains($description, "gal"):
        $typeWeights[2] = [3, 0, 2, 1];
        break;
}

function sum_multi($arr, $template)
{
    $sum = $template;
    for ($i = 0; $i < count($arr); $i++) {
        for ($j = 0; $j < count($arr[$i]); $j++) {
            $sum[$i] += $arr[$i][$j];
        }
    }
    return $sum;
}

$typeWeightResults = sum_multi($typeWeights, [0, 0, 0, 0]);
echo "<br><br>";
print_r($typeWeightResults);


die();


$type = ["INC", "CHG"][$typeWeight > 0.5];

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
        break;
    case "Virus/skadevare" || "Sikkerhetshull":
        $urgency = 1;
        $impact = 2;
        break;
}

if ($type == "INC") {
    $urgency = 2;
    $impact = 3;
    if (
        $urgency < 3 &&
        $impact < 3 &&
        ($urgency == 1 || $impact == 1)
    ) {
        $type = "PRB";
    }
}

// Encode user inputted values to base64
$description = base64_encode($description);
$page = base64_encode($page);
$category = base64_encode($category);
$email = base64_encode($email);

echo "type: $type<br>impact: $impact<br>urgency: $urgency<br>description: $description<br>page: $page<br>category: $category<br>status: 0<br>registered: $registered<br>email: $email";

// Insert into DB
$sql = "INSERT INTO `tickets` (`type`, `impact`, `urgency`, `description`, `category`, `page`, `status`, `registered`, `started`, `finished`, `email`) VALUES ('$type', $impact, $urgency, '$description', '$category', '$page', 0, '$registered', NULL, NULL, '$email');";

// Execute $sql
$result = $connection->query($sql);
