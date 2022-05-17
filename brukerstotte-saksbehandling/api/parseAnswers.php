<?php
error_reporting(-1);

if (!isset($_POST["answers"])) die(header("Location: ../"));

// Connect to DB
require("./connect.php");

$answers = str_replace(" ", "+", json_decode(base64_decode($_POST["answers"])));
// $test = "WzAsIklmIHRoZXJlIGFyZSBtb3JlIHRoYW4gemVybyByb3dzIHJldHVybmVkLCB0aGVuIHRoZSBxdWVyeSB3YXMgc3VjY2Vzc2Z1bC4iLCJodHRwczovL3d3dy55b3V0dWJlLmNvbS93YXRjaD92PXpXU2h5dzREb2swIiwiRmVpbCBwYXNzb3JkL2JydWtlcm5hdm4iLCJmcmVkcmlzdEB2aWtlbi5ubyJd";
// $answers = json_decode(base64_decode($test));
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

file_put_contents("test.txt", $_POST["answers"]);

$description = base64_decode($answers[1]);
$page = base64_decode($answers[2]);
$category = substr(base64_decode($answers[3]), 0, 31);
$registered = new DateTime();
$registered = $registered->format('Y-m-d H:i:s');
$email = base64_decode($answers[4]);

// Impact/urgency
switch ($description) {
    case str_contains($description, "youtu") || str_contains($page, "youtu"):
        $impact = 3;
        $urgency = 3;
    case str_contains($description, "localhost") || str_contains($page, "localhost") || str_contains($description, "viken") || str_contains($page, "viken"):
        $impact = 3;
        $urgency = 2;
    case str_contains($description, "office") || str_contains($page, "office"):
        $impact = 3;
        $urgency = 1;
}
// Type: avhengig av kategori, beskrivelse og om det er en feil/endring/annet
// Endring = 1, Feil = 0.
// SRQ hvis Endring, og kategori == ...
$typeWeighing = [
    [
        "Jeg trenger hjelp" => 0.75,
        "Jeg vil rapportere en feil" => 0,
        "Noe annet" => 1
    ],
    [
        "Registreringsfeil" => 0,
        "Glemt passord" => 1,
        "Feil passord/brukernavn" => 0.5,
        "Ingen tilgang" => 0.75,
        "Programvarefeil" => 0.5,
        "Manglende utstyr" => 1,
        "Endring av rettigheter" => 1,
        "Programvareinstallasjon" => 1,
        "Skrivefeil/grammatikk" => 1,
        "Ingen internettilkobling" => 0,
        "Brannmurendring" => 1,
        "Blokkert nettsted" => 1,
    ]
];

$typeWeighingDescription = 0.5;
switch ($description) {
        // endring
    case str_contains($description, "endring") || str_contains($description, "endre") || str_contains($description, "bytte"):
        $typeWeighingDescription = 1;
        break;

    case str_contains($description, "hendelse") || str_contains($description, "feil"):
        $typeWeighingDescription = 0;
        break;
}

$typeWeight = ($typeWeighing[0][base64_decode($answers[0])] + $typeWeighing[1][$category] + $typeWeighingDescription) / 3;
$type = ["INC", "CHG"][$typeWeight > 0.5];

if (
    $type == "CHG" &&
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
    $type = "SRQ";
    $urgency = 3;
    $impact = 3;
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
print_r($result);
