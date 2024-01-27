<?php
$filename = __DIR__ . '/public/data/plats.json';

//Fonction pour charger les plats à partir du fichier JSON 
function loadPlatsFromFile() {
	global $filename;
	//tous les plats
	$platsData = [];
	if (file_exists($filename)){
		$platsData = json_decode(file_get_contents($filename), true);
	}
	return $platsData;
}

$_GET = filter_input_array(INPUT_GET, FILTER_VALIDATE_INT);
$id = $_GET['id'] ?? '';

if ($id) {
    // Obtenir le contenu repas du fichier json
    $lesPlats = loadPlatsFromFile() ?? [];

    if (count($lesPlats)) {
        $platIndex = array_search($id, array_column($lesPlats, 'id'));
        array_splice($lesPlats, $platIndex, 1);
        file_put_contents($filename, json_encode($lesPlats));
    }
}

header('Location:  /repaspartages.php');