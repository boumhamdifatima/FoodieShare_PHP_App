<?php 
    
    $filename = __DIR__ . '/public/data/users.json';

    //Fonction pour charger les utilisateurs à partir du fichier JSON 
    function loadUsersFromFile() {
        global $filename;
        $usersData = [];
        if (file_exists($filename)){
            $usersData = file_get_contents($filename);
        }
        return json_decode($usersData, true);
    }

    if(!empty($_POST['email']) && !empty($_POST['password'])) // Si il existe les champs email, password et qu'il sont pas vident
    {
        // nettoyage des données pour eviter les injections de HTML ou de js
        $_POST = filter_input_array(INPUT_POST, [
            'email' => FILTER_SANITIZE_EMAIL,
            'password' => FILTER_SANITIZE_SPECIAL_CHARS
        ]
        );
		/* Nettoyage XSS */
        $email = htmlspecialchars($_POST['email']); 
        $password = htmlspecialchars($_POST['password']);
        
        $email = strtolower($email); // email transformé en minuscule
        
		// On vérifie Si le mail est valide
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			header('Location: login.php?login_err=email');
			die();
		}

        // On vérifie si l'utilisateur existe
        $users = loadUsersFromFile();
        foreach ($users as $user) {
            if (strtolower($user['email']) === $email) {              
				// On vérifie Si le mot de passe correspod à celui saisi
				if (password_verify($password, $user['password'])) {
					// On crée la session
					session_start(); // Démarrage de la session
					$_SESSION['user'] = $user['email'];
					$_SESSION['pseudo'] = $user['pseudo'];
					$_SESSION['logged_in'] = true;
					$_SESSION['user_id'] = $user['id'];
					header('Location: /');
					die();
				} else {
					header('Location: login.php?login_err=password');
					die();
				}                
            }
        }
        // Si l'utilisateur n'est pas trouvé
        header('Location: login.php?login_err=already');
        die();

    }else { header('Location: login.php'); die();} // si le formulaire est envoyé sans aucune données
?>