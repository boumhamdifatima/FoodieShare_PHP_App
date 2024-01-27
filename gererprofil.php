<?php

	$filename = __DIR__ . '/public/data/users.json';

	//Fonction pour charger les utilisateurs à partir du fichier JSON 
	function loadUsersFromFile() {
		global $filename;
		$usersData = [];
		if (file_exists($filename)){
			$usersData= file_get_contents($filename);
		}
		return json_decode($usersData, true);
	}

	session_status() === PHP_SESSION_ACTIVE ?: session_start();
	
	$nom = '';
	$pseudo = '';
	$email = '';
	$ville = '';
	$preferences = '';
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
				$nom = $user['nom'];
				$pseudo = $user['pseudo'];
				$ville = $user['ville'];
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
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-home-tab" data-bs-toggle="pill" data-bs-target="#v-pills-home" type="button" role="tab" aria-controls="v-pills-home" aria-selected="true" onclick="window.location.href='moncompte.php';">Préférences alimentaires</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#v-pills-disabled" type="button" role="tab" aria-controls="v-pills-disabled" aria-selected="false" onclick="window.location.href='repaspartages.php';">Repas partagés</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false" onclick="window.location.href='ajouterrepas.php';">Ajouter nouveau repas</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold active" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false" onclick="window.location.href='gererprofil.php';">Gérer profil</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false" onclick="window.location.href='sedeconnecter.php';">Se déconnecter</button>
					</div>
				</div>
				<div class="pt-2 m-2 w-75">				
					<div class="tab-content" id="v-pills-tabContent">
						<div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0"></div>
						<div class="tab-pane fade" id="v-pills-disabled" role="tabpanel" aria-labelledby="v-pills-disabled-tab" tabindex="0">...2</div>
						<div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" tabindex="0">...3</div>
						<div class="tab-pane fade show active" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">
							<h3 class="mb-4">Gérer profil</h3>
							<div class="login-form container">
								<?php 
									if(isset($_GET['reg_err']))
									{
										$err = htmlspecialchars($_GET['reg_err']);

										switch($err)
										{
											case 'success':
											?>
												<div class="alert alert-success">
													<strong>Succès</strong> Enregistrement réussie !
												</div>
											<?php
											break;

											case 'password':
											?>
												<div class="alert alert-danger">
													<strong>Erreur</strong> Mot de passe différent
												</div>
											<?php
											break;

											case 'email':
											?>
												<div class="alert alert-danger">
													<strong>Erreur</strong> Email non valide
												</div>
											<?php
											break;

											case 'email_length':
											?>
												<div class="alert alert-danger">
													<strong>Erreur</strong> Courriel trop long
												</div>
											<?php 
											break;

											case 'pseudo_length':
											?>
												<div class="alert alert-danger">
													<strong>Erreur</strong> Pseudo trop long
												</div>
											<?php
											break;

											case 'alreadyemail':
											?>
												<div class="alert alert-danger">
													<strong>Erreur</strong> Compte deja existant
												</div>
											<?php
											break;

											case 'alreadypseudo':
											?>
												<div class="alert alert-danger">
													<strong>Erreur</strong> Pseudo deja existant
												</div>
											<?php
											break;

											case 'nom_length':
											?>
												<div class="alert alert-danger">
													<strong>Erreur</strong> Nom et prénom trop long
												</div>
											<?php
											break;

											case 'ville_length':
											?>
												<div class="alert alert-danger">
													<strong>Erreur</strong> Ville trop longue
												</div>
											<?php
											break;
											
											case 'preferences_length':
											?>
												<div class="alert alert-danger">
													<strong>Erreur</strong> Préférences alimentaires trop longues
												</div>
											<?php
											break;

											case 'empty_elt':
												?>
													<div class="alert alert-danger">
														<strong>Erreur</strong> Veuillez saisir tous les champs
													</div>
												<?php
												break;

										}
									}
								?>
								
								<form action="gererprofil_traitment.php" method="post"> 
									<div class="form-group">
										<div class="d-flex align-items-center mb-2">
											<span class="w-25 fw-semibold">Nom et prénom</span>
											<input type="text" name="nom" id="nom" class="form-control mb-2" value=<?=$nom?> required="required" autocomplete="off">
										</div>
									</div>      
									<div class="form-group">
										<div class="d-flex align-items-center mb-2">
											<span class="w-25 fw-semibold">Pseudo</span>
											<input type="text" name="pseudo" id="pseudo" pattern="\w{3,16}" title="(3-16 characters)" class="form-control mb-2" value=<?=$pseudo?> required="required" autocomplete="off">
										</div>
									</div>
									<div class="form-group">
										<div class="d-flex align-items-center mb-2">
											<span class="w-25 fw-semibold">Courriel</span>
											<input type="email" name="email" id="email" class="form-control mb-2" value=<?=$email?> required="required" autocomplete="off">
										</div>
									</div>
									<div class="form-group">
										<div class="d-flex align-items-center mb-2">
											<span class="w-25 fw-semibold">Nouveau mot de passe</span>
											<input type="password" name="password" id="password" class="form-control mb-2" autocomplete="off">
									</div>
									<div class="form-group">
										<div class="d-flex align-items-center mb-2">
											<span class="w-25 fw-semibold">Confirmer nouveau mot de passe</span>
											<input type="password" name="password_retype" id="password_retype" class="form-control mb-2" autocomplete="off">
										</div>
									</div>
									<div class="form-group">
										<div class="d-flex align-items-center mb-2">
											<span class="w-25 fw-semibold">Ville</span>
											<input type="text" name="ville" id="ville" class="form-control mb-2" value=<?=$ville?> required="required" autocomplete="off">
										</div>
									</div>
									<div class="input-group mb-2">									
										<span class="w-25 mb-2 fw-semibold">Préférences alimentaires</span>
										<div class="mb-2">
											<div class="form-check">
												<?php $checked = (in_array("vegetarien", $preferences)?"checked":"")?>
												<input class="form-check-input" type="checkbox" name="preferences[]" value="vegetarien" id="vegetarien" <?=$checked?>>
												<label class="form-check-label" for="vegetarien">
													Végétarien
												</label>
											</div>
											<div class="form-check">
												<?php $checked = (in_array("poisson", $preferences)?"checked":"")?>
												<input class="form-check-input" type="checkbox" name="preferences[]" value="poisson" id="poisson" <?=$checked?>>
												<label class="form-check-label" for="poisson">
													Poisson
												</label>
											</div>
											<div class="form-check">
												<?php $checked = (in_array("viande", $preferences)?"checked":"")?>
												<input class="form-check-input" type="checkbox" name="preferences[]" value="viande" id="viande" <?=$checked?>>
												<label class="form-check-label" for="viande">
													Viande
												</label>
											</div>
											<div class="form-check">
												<?php $checked = (in_array("poulet", $preferences)?"checked":"")?>
												<input class="form-check-input" type="checkbox" name="preferences[]" value="poulet" id="poulet" <?=$checked?>>
												<label class="form-check-label" for="poulet">
													Poulet
												</label>
											</div>
										</div>
									</div>
									<div class="form-group d-flex justify-content-end">
										<button type="submit" class="btn btn-primary btn-block fw-bold">Mettre à jour profil</button>
									</div>   
								</form>
							</div>
						</div>
					</div>				
				</div>
			</div>
		</div>
	</div>

	<?php require_once 'includes/footer.php'?>
	

</body>

</html>