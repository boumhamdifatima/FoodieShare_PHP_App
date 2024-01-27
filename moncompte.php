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

	session_status() === PHP_SESSION_ACTIVE ?: session_start();
	
	$preferences = [];
	$email = '';
	$userid = 0;

	// Vérifier si l'utilisateur est connecté
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
		// L'utilisateur est connecté
		$email = $_SESSION['user'];
		$userid = $_SESSION['user_id'];
		
		// On cherche les information de l'utilisateur dont id est $userid
        $users = loadUsersFromFile();
		$nontrouve = true;
        foreach ($users as $user) {
            if ($user['id'] === $userid) {
			    $preferences = $user['preferences'];
				$nontrouve = false;
            }
        }

		// Si l'utilisateur n'est pas trouvé
		if($nontrouve){
			header('Location: login.php?login_err=already');
        	die();
		}
		
	} else {
		// Redirigez vers la page de connexion login
		header('Location: login.php');
		die();
	}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php'?>
    <title>Mon Compte</title>
	<link rel="stylesheet" href="public/css/moncompte.css">
</head>

<body>
    <?php require_once 'includes/header.php'?>
	<section class="banner-compte d-flex justify-content-center align-items-center pt-5">
      <div class="container my-5 py-5">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-10 text-center">
            <h1 class="text-capitalize redressed py-3 banner-desc">FoodieShare</h1>
            <h3 class="redressed py-5 text-light fs-2 fst-italic d-none d-lg-block">Votre meilleur portail pour partager vos plats favoris<br/>
				Faites partie de notre grande famille</h3>
          </div>
        </div>
      </div>
    </section>

	<div>
		<div class="container">
			<div class="d-flex align-items-start mb-5">
				<div class="sidebar mb-5">
					<div class="nav flex-column nav-pills me-3 w-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
						<div class="user-picture d-flex align-items-end justify-content-center mb-2 mt-2">
							<p class="text-light fw-bold"><?=$email?></p>
						</div>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold active" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true" onclick="window.location.href='moncompte.php';">Préférences alimentaires</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#v-pills-disabled" type="button" role="tab" aria-controls="v-pills-disabled" aria-selected="false" onclick="window.location.href='repaspartages.php';">Repas partagés</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false" onclick="window.location.href='ajouterrepas.php';">Ajouter nouveau repas</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false" onclick="window.location.href='gererprofil.php';">Gérer profil</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false" onclick="window.location.href='sedeconnecter.php';">Se déconnecter</button>
					</div>
				</div>
				<div class="pt-2 m-2 ">				
					<div class="tab-content" id="v-pills-tabContent">
						<div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0">
							<h3 class="mb-4">Mes préférences alimentaires</h3>
							<ul class="list-group list-group-flush ms-5">
								<?php $i = 1;?>
								<?php foreach($preferences as $prefer):?>
									<li class="list-group-item"><span class="badge bg-primary rounded-pill me-3"><?=$i?> </span><?=$prefer?></li>
									<?php $i++;?>
								<?php endforeach;?>
							</ul>				
						</div>
						<div class="tab-pane fade" id="v-pills-disabled" role="tabpanel" aria-labelledby="v-pills-disabled-tab" tabindex="0">...2</div>
						<div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" tabindex="0">...3</div>
						<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">...4</div>
					</div>				
				</div>
			</div>
		</div>
	</div>
	<?php require_once 'includes/footer.php'?>

</body>

</html>