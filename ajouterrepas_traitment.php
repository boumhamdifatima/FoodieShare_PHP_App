<?php 
    $filename = __DIR__ . '/public/data/plats.json';

    //Fonction pour charger les plats à partir du fichier JSON 
    function loadPlatsFromFile() {
        global $filename;
        $platsData = [];
        if (file_exists($filename)) {
            $platsData = json_decode(file_get_contents($filename), true) ?? [];
        }
        return $platsData;
    }

    //Fonction pour sauvegarder les plats dans le fichier JSON
    function savePlatsToFile($plats) {
        global $filename;
        $platsData = json_encode($plats, JSON_PRETTY_PRINT);
        file_put_contents($filename, $platsData);
    }

	session_status() === PHP_SESSION_ACTIVE ?: session_start();

	$nomplat = '';
	$categorie = '';
	$description = '';
	$prix = 0.00;
	$localisation = '';
	$image = 'public/uploads/plat_par_defaut.jpg';//image par défaut
	$userid = 0;

	// Vérifier si l'utilisateur est connecté
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
		// L'utilisateur est connecté
		$email = $_SESSION['user'];
		$userid = $_SESSION['user_id'];

		// Vérifier si les variables existent et qu'elles ne sont pas vides
		if(!empty($_POST['nomplat']) && !empty($_POST['categorie']) && !empty($_POST['description']) && !empty($_POST['prix']) 
				&& !empty($_POST['localisation']))
    	{
			//verifier si une photo du plat a ete telversée et la sauvgarder
			if (isset($_FILES['image']) && ($_FILES['image']['size'] > 0)){
				$file = $_FILES['image'];
				//print_r($file);

				$fileName = $_FILES['image']['name'];
				$fileTmpName = $_FILES['image']['tmp_name'];
				$fileSize = $_FILES['image']['size'];
				$fileError = $_FILES['image']['error'];
				$fileType = $_FILES['image']['type'];

				$fileExt = explode('.', $fileName);
				$fileActuelExt = strtolower(end($fileExt));

				$allowed = array('jpg', 'jpeg', 'png');

				if(in_array($fileActuelExt, $allowed)){
					if($fileError ===0){
						if ($fileSize < 1000000){
							$fileNameNew = uniqid('',true).".".$fileActuelExt;
							$fileDestination = 'public/uploads/'.$fileNameNew;
							move_uploaded_file($fileTmpName, $fileDestination);
							$image = $fileDestination;
							
						}else{ header('Location: ajouterrepas.php?reg_err=img_file_big'); die();}
						//echo "Your file is too big!";
					}else{ header('Location: ajouterrepas.php?reg_err=img_upload_error'); die();}
					//echo "There wos an error uploading your file!";
				}else{ header('Location: ajouterrepas.php?reg_err=img_file_type'); die();}
				//echo "You can not upload files of this type!";
			} 
			/* On fait le nettoyage des données pour eviter les injections de HTML ou de js */
			$_POST = filter_input_array(INPUT_POST, [
				'nomplat' => FILTER_SANITIZE_SPECIAL_CHARS,
				'categorie' => FILTER_SANITIZE_SPECIAL_CHARS,
				'description' => FILTER_SANITIZE_SPECIAL_CHARS,
				'prix' => FILTER_SANITIZE_NUMBER_FLOAT,
				'localisation' => FILTER_SANITIZE_SPECIAL_CHARS
			]
			);

			//récupération des valeurs saisies
			$nomplat = $_POST['nomplat'];
			$categorie = $_POST['categorie'];
			$description = $_POST['description'];
			$prix = $_POST['prix'];
			$localisation = $_POST['localisation'];

			//Validation des données saisies
			if(strlen($nomplat) <= 100){ // On verifie que la longueur du nomplat <= 100
				if(strlen($description) <= 1000){ // On verifie que la longueur de description <= 1000
					if(filter_var($prix, FILTER_VALIDATE_FLOAT)){ // Si le prix est de la bonne forme	
							
							// On stock l'id du plat
							$id = time();

							// On stock la date de partage
							$datePartage = date("d-m-y");

							//liste des plats existants
							$plats = loadPlatsFromFile();

							//on insère le nouveau plat
							$plats = [...$plats, [
								'id' => $id,
								'nomplat' => $nomplat,
								'categorie' => $categorie,
								'description' => $description,
								'prix' => number_format($prix/100,2),
								'localisation' => $localisation,
								'image' => $image,
								'userid' => $userid,
								'datePartage' => $datePartage,
								'reviews' => []
							],
							];

							//print_r($plats);
							savePlatsToFile($plats);

							// On redirige avec le message de succès
							header('Location:ajouterrepas.php?reg_err=success');
							die();

					}else{ header('Location: ajouterrepas.php?reg_err=prix_form'); die();}
				}else{ header('Location: ajouterrepas.php?reg_err=description_length'); die();}
			}else{ header('Location: ajouterrepas.php?reg_err=nomplat_length'); die();} 
		}

	} else {
		// Redirigez vers la page de connexion login
		header('Location: login.php');
		die();
	}

?>