<?php 
    $filename = __DIR__ . '/public/data/users.json';

    //Fonction pour charger les utilisateurs à partir du fichier JSON 
    function loadUsersFromFile() {
        global $filename;
        $usersData = [];
        if (file_exists($filename)) {
            $usersData = json_decode(file_get_contents($filename), true) ?? [];
        }
        return $usersData;
    }

    //Fonction pour sauvegarder les utilisateurs dans le fichier JSON
    function saveUsersToFile($users) {
        global $filename;
        $usersData = json_encode($users, JSON_PRETTY_PRINT);
        file_put_contents($filename, $usersData);
    }
    
	session_status() === PHP_SESSION_ACTIVE ?: session_start();
	
	$nom = '';
	$pseudo = '';
	$email = '';
	$ville = '';
	$preferences = [];
	$userid = 0;
	$password = '';
	$password_retype = '';

	// Vérifier si l'utilisateur est connecté
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
		// L'utilisateur est connecté
		$userid = $_SESSION['user_id'];

		// Vérifier si les variables existent et qu'elles ne sont pas vides
		if(!empty($_POST['nom']) && !empty($_POST['pseudo']) && !empty($_POST['email']) 
				&& !empty($_POST['ville']) && (isset($_POST['preferences'])) && (count($_POST['preferences']) > 0))
    	{
			//affectation des preferences
			$preferences = $_POST['preferences'];
			/* On fait le nettoyage des données pour eviter les injections de HTML ou de js */
			if (!empty($_POST['password']) || !empty($_POST['password_retype'])){
				
				$_POST = filter_input_array(INPUT_POST, [
					'nom' => FILTER_SANITIZE_SPECIAL_CHARS,
					'pseudo' => FILTER_SANITIZE_SPECIAL_CHARS,
					'email' => FILTER_SANITIZE_EMAIL,
					'password' => FILTER_SANITIZE_SPECIAL_CHARS,
					'password_retype' => FILTER_SANITIZE_SPECIAL_CHARS,
					'ville' => FILTER_SANITIZE_SPECIAL_CHARS
				]
				);
			}else {
				$_POST = filter_input_array(INPUT_POST, [
					'nom' => FILTER_SANITIZE_SPECIAL_CHARS,
					'pseudo' => FILTER_SANITIZE_SPECIAL_CHARS,
					'email' => FILTER_SANITIZE_EMAIL,
					'ville' => FILTER_SANITIZE_SPECIAL_CHARS
				]
				);
			}

			/* Affectation des valeurs saisis */
			$nom = $_POST['nom'];
			$pseudo = $_POST['pseudo'];
			$email = $_POST['email'];
			$ville = $_POST['ville'];
			if (!empty($_POST['password']) || !empty($_POST['password_retype'])){
				$password = $_POST['password'];
				$password_retype = $_POST['password_retype'];
			}

			// On vérifie si l'utilisateur existe
			$users = loadUsersFromFile();
			foreach ($users as $user) {
				if (($user['email'] === $email) && ($user['id'] != $userid)) {
					header('Location: gererprofil.php?reg_err=alreadyemail');
					die();
				}
				if (($user['pseudo'] === $pseudo && ($user['id'] != $userid))) {
					header('Location: gererprofil.php?reg_err=alreadypseudo');
					die();
				}
			}

			$email = strtolower($email); // on transforme toute les lettres majuscule en minuscule pour éviter que Abc@gmail.com et abc@gmail.com soient deux compte différents ..
        
			// Si la requete renvoie un 0 alors l'utilisateur n'existe pas
			if(strlen($nom) <= 100){ // On verifie que la longueur du nom <= 100
				if(strlen($pseudo) <= 100){ // On verifie que la longueur du pseudo <= 100
					if(strlen($email) <= 100){ // On verifie que la longueur du mail <= 100
						if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // Si l'email est de la bonne forme
							if(strlen($ville) <= 100){ // On verifie que la longueur du ville <= 100
									
								// On stock l'id utilisateur
								$id = $userid;

								//on cherche l'utilisateur correspondant a $userid
								$users = loadUsersFromFile();
								$userIndex = array_search($id, array_column($users, 'id'));

								if(empty($password)){
									$password = $users[$userIndex]['password'];
								}else {
									if($password === $password_retype){ // si les deux mdp saisis sont bon	
										// On hash le mot de passe 
										$password = password_hash($password, PASSWORD_DEFAULT);									
									}else{ header('Location: gererprofil.php?reg_err=password'); die();}
								}

								//on met à jours les informations de l'utilisateur
								$users[$userIndex]['nom'] = $nom;
								$users[$userIndex]['pseudo'] = $pseudo;
								$users[$userIndex]['email'] = $email;
								$users[$userIndex]['password'] = $password;
								$users[$userIndex]['ville'] = $ville;
								$users[$userIndex]['preferences'] = $preferences;

								//on sauvgarde les utilisateurs
								saveUsersToFile($users);
								$_SESSION['user'] = $email;

								// On redirige avec le message de succès
								header('Location:gererprofil.php?reg_err=success');
								die();
										
							}else{ header('Location: gererprofil.php?reg_err=ville_length'); die();}
						}else{ header('Location: gererprofil.php?reg_err=email'); die();}
					}else{ header('Location: gererprofil.php?reg_err=email_length'); die();}
				}else{ header('Location: gererprofil.php?reg_err=pseudo_length'); die();}
			}else{ header('Location: gererprofil.php?reg_err=nom_length'); die();} 
			
		}else{ header('Location: gererprofil.php?reg_err=empty_elt'); die();} 
		
	} else {
		// Redirigez vers la page de connexion login
		header('Location: login.php');
		die();
	}

?>