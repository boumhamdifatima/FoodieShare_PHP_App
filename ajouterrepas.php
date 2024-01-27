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
	
	$email = '';
	$userid = 0;

	// Vérifier si l'utilisateur est connecté
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
		// L'utilisateur est connecté
		$email = $_SESSION['user'];
		$userid = $_SESSION['user_id'];
		
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
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold active" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false" onclick="window.location.href='ajouterrepas.php';">Ajouter nouveau repas</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false" onclick="window.location.href='gererprofil.php';">Gérer profil</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false" onclick="window.location.href='sedeconnecter.php';">Se déconnecter</button>
					</div>
				</div>
				<div class="pt-2 m-2 w-100">				
					<div class="tab-content" id="v-pills-tabContent">
						<div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0"></div>
						<div class="tab-pane fade" id="v-pills-disabled" role="tabpanel" aria-labelledby="v-pills-disabled-tab" tabindex="0">...2</div>
						<div class="tab-pane fade  show active" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" tabindex="0">
							<h3 class="mb-4">Ajouter mon plat préféré</h3>
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

										case 'prix_form':
										?>
											<div class="alert alert-danger">
												<strong>Erreur</strong> Le prix est non valide !
											</div>
										<?php
										break;

										case 'description_length':
										?>
											<div class="alert alert-danger">
												<strong>Erreur</strong> Déscription trop longue !
											</div>
										<?php
										break;

										case 'nomplat_length':
										?>
											<div class="alert alert-danger">
												<strong>Erreur</strong> Nom du plat trop long !
											</div>
										<?php 
										break;

										case 'img_file_big':
										?>
											<div class="alert alert-danger">
												<strong>Erreur</strong> Taille de l'image trés grande !
											</div>
										<?php
										break;

										case 'img_upload_error':
										?>
											<div class="alert alert-danger">
												<strong>Erreur</strong> Erreur pendant le chargement de l'image !
											</div>
										<?php
										break;

										case 'img_file_type':
										?>
											<div class="alert alert-danger">
												<strong>Erreur</strong> Type du fichier image nom permis !
											</div>
										<?php
										break;

					
									}
								}
							?>
								
							<form action="ajouterrepas_traitment.php" method="post" enctype="multipart/form-data">
								<div class="container">								
									<div class="row mb-2">
										<div class="col-2 fw-semibold">Nom du plat</div>
										<div class="col-6">
											<input class="form-control" type="text" name="nomplat" required="required">
										</div>
									</div>	
									<div class="row mb-2">
										<div class="col-2 fw-semibold">Catégorie du plat</div>
										<div class="col-3">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="categorie" value="vegetarien" id="vegetarien" checked>
												<label class="form-check-label" for="vegetarien">
													Végétarien
												</label>
											</div>
										</div>
										<div class="col-3">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="categorie" value="poisson" id="poisson">
												<label class="form-check-label" for="poisson">
													Poisson
												</label>
											</div>
										</div>	
									</div>
									<div class="row mb-2">
										<div class="col-2 fw-semibold"></div>
										<div class="col-3">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="categorie" value="viande" id="viande">
												<label class="form-check-label" for="viande">
													Viande
												</label>
											</div>
										</div>
										<div class="col-3">
											<div class="form-check">
												<input class="form-check-input" type="radio" name="categorie" value="poulet" id="poulet">
												<label class="form-check-label" for="poulet">
													Poulet
												</label>
											</div>
										</div>	
									</div>		
									<div class="row mb-2">
										<div class="col-2 fw-semibold">Description</div>
										<div class="col-8">
											<textarea name="description" class="form-control mb-2 w-100" aria-label="With textarea" rows="5"></textarea>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-2 fw-semibold">Prix</div>
										<div class="col-4">
											<input class=" form-control" type="text" name="prix" required="required" pattern="([0-9]{1,4})[.]([0-9]{2})" title="Format du prix est 0.00">
										</div>
										<div class="col-1 fs-5 =">$</div>
									</div>
									<div class="row mb-2">
										<div class="col-2 fw-semibold">Localistation</div>
										<div class="col-4">
											<select class="form-select" name="localisation" id="localisation" required="required" aria-label="Default select example">
												<option value = "" selected>Choisir une localisation</option>
												<option value="campus">Campus</option>
												<option value="proximite">À proximité du campus</option>
												<option value="loin">Loin du campus</option>
											</select>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-2 fw-semibold">Téléverser image</div>
										<div class="col-8">
											<input class="form-control" type="file" name="image">
										</div>
									</div>
									<div class="row">
										<div class="col-2"></div>
										<div class="col-8 text-end">
											<button type="reset" class="btn btn-secondary btn-block fw-bold ps-5 pe-5 me-2">Annuler</button>
											<button type="submit" class="btn btn-primary btn-block fw-bold ps-5 pe-5">Ajouter</button>
										</div>
									</div>								
								</div>
							</form>
						</div>
						<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">...4</div>
					</div>				
				</div>
			</div>
		</div>
	</div>
	<?php require_once 'includes/footer.php'?>

</body>

</html>