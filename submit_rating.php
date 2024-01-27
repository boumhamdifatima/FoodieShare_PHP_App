<?php
$filename = __DIR__ . '/public/data/plats.json';


//Fonction pour sauvegarder les avis dans le fichier plats JSON
function savereviewsToFile($plats) {
	global $filename;
	$platsData = json_encode($plats, JSON_PRETTY_PRINT);
	file_put_contents($filename, $platsData);
}

if(isset($_POST['stars']))
{
	
	//echo $_POST['plat_id'];
	$_POST = filter_input_array(INPUT_POST, [
		'plat_id' => FILTER_SANITIZE_SPECIAL_CHARS,
		'pseudo' => FILTER_SANITIZE_SPECIAL_CHARS,
		'stars' => FILTER_SANITIZE_SPECIAL_CHARS,
		'comment' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
	]
	);
	$idPlat = $_POST['plat_id'];
	
	$data = array(
		'pseudo' => $_POST["pseudo"],
		'stars' => $_POST["stars"],
		'comment' => $_POST["comment"],
		'datetime' => date("d-m-y")
	);
	
	// charger les avis à partir du fichier plats JSON 
	$reviews = [];
	$plats = [];
	if (file_exists($filename)) {
		$plats = json_decode(file_get_contents($filename), true) ?? [];
	}
	$platIndex = array_search($idPlat, array_column($plats, 'id'));
	
	if(isset($platIndex)){
		//liste des avis existants
		$reviews =  $plats[$platIndex]['reviews'];
		//on insère le nouveau avis
		$reviews = [...$reviews, $data];
		//sauvgarder les avis
		$plats[$platIndex]['reviews'] = $reviews;
		saveReviewsToFile($plats);
		
		//Reponse pour ajax en cas de succes
		echo "Votre Avis est ajouté avec succés";

	} else
	{
		//Reponse pour ajax en cas d'échouer
		echo "L ajout de l avis a échoué!!!";
	}
	
}
?>