<?php

	$filename = __DIR__ . '/public/data/plats.json';

	//Fonction pour charger les plats d'un utilisateur avec son id à partir du fichier JSON 
	function loadUserPlatsFromFile($idUser) {
		global $filename;
		//tous les plats
		$platsData = [];
		if (file_exists($filename)){
			$platsData = json_decode(file_get_contents($filename), true);
		}
		//sercher les plats de l'utilisateur
		$userPlatsData = [];
		foreach($platsData as $plat){
			if($plat['userid'] === $idUser){
				$userPlatsData = [...$userPlatsData, $plat];
			}
		}
		return $userPlatsData;
	}

	session_status() === PHP_SESSION_ACTIVE ?: session_start();
	$email = '';
	$userid = 0;

	// Vérifier si l'utilisateur est connecté
	if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
		// L'utilisateur est connecté
		$email = $_SESSION['user'];
		$userid = $_SESSION['user_id'];
		
		// On cherche les plats de l'utilisateur dont id est $userid
        $userPlats = loadUserPlatsFromFile($userid);
		
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
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold active" id="v-pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#v-pills-disabled" type="button" role="tab" aria-controls="v-pills-disabled" aria-selected="false" onclick="window.location.href='repaspartages.php';">Repas partagés</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-messages-tab" data-bs-toggle="pill" data-bs-target="#v-pills-messages" type="button" role="tab" aria-controls="v-pills-messages" aria-selected="false" onclick="window.location.href='ajouterrepas.php';">Ajouter nouveau repas</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-profile-tab" data-bs-toggle="pill" data-bs-target="#v-pills-profile" type="button" role="tab" aria-controls="v-pills-profile" aria-selected="false" onclick="window.location.href='gererprofil.php';">Gérer profil</button>
						<button class="btn-hover border border-primary nav-link mb-2 fw-bold" id="v-pills-settings-tab" data-bs-toggle="pill" data-bs-target="#v-pills-settings" type="button" role="tab" aria-controls="v-pills-settings" aria-selected="false" onclick="window.location.href='sedeconnecter.php';">Se déconnecter</button>
					</div>
				</div>
				<div class="pt-2 m-2 w-100">				
					<div class="tab-content" id="v-pills-tabContent">
						<div class="tab-pane fade" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab" tabindex="0"></div>
						<div class="tab-pane fade show active" id="v-pills-disabled" role="tabpanel" aria-labelledby="v-pills-disabled-tab" tabindex="0">
							<h3 class="mb-4 fw-bold">Mes Plats Partagés</h3>
							<div class="container">
							<?php if(count($userPlats) === 0):?>
								<div class="text-danger">Aucun repas ou plat partagé !</div>
							<?php else:?>
								<ul class="list-group list-group-flush ms-5">
									<li class="list-group-item">
										<div class="row">
											<div class="col-2 fw-bold text-primary">Image</div>
											<div class="col-3 fw-bold text-primary">Nom du plat</div>
											<div class="col-2 fw-bold text-primary">Catégorie</div>
											<div class="col-1 fw-bold text-primary">Prix</div>
											<div class="col-2 fw-bold text-primary">Localisation</div>
										</div>
									</li>							
									<?php foreach($userPlats as $plat):?>
									<li class="list-group-item">
										<div class="row">
											<div class="col-2" style="background: url(<?=$plat['image']?>) center no-repeat; height : 80px; background-size:cover;"></div>
											<div class="col-3 mt-4"><?=$plat['nomplat']?></div>
											<div class="col-2 mt-4"><?=ucwords($plat['categorie'])?></div>
											<div class="col-1 mt-4 text-end"><?=($plat['prix'])."$"?></div>
											<div class="col-2 mt-4"><?=ucwords($plat['localisation'])?></div>
											<div class="col-1 mt-4"><a class="btn btn-primary" href="/detailrepas.php?id=<?=$plat['id']?>">Détail</a></div>
											<a class="col-1 mt-4" href="javascript:confirmDelete('/supprimer-repas.php?id=<?=$plat['id']?>')">
												<button class="btn btn-danger btn-small">Supprimer</button>
											</a>
										</div>
									</li>
									<?php endforeach;?>
								</ul>	
							<?php endif;?>
							</div>
						</div>
						<div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab" tabindex="0">...3</div>
						<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab" tabindex="0">...4</div>
					</div>				
				</div>
			</div>
		</div>
	</div>
	<?php require_once 'includes/footer.php'?>

</body>

<script>
function confirmDelete(delUrl) {
  if (confirm("Etes-vous sure de vouloir supprimer ce repas?")) {
   document.location = delUrl;
  }
}
</script>
</html>