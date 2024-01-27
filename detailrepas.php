<?php

session_status() === PHP_SESSION_ACTIVE ?: session_start();
$fileusers = __DIR__ . '/public/data/users.json';
//Fonction pour charger les utilisateurs à partir du fichier JSON 
function loadUsersFromFile()
{
	global $fileusers;
	$usersData = [];
	if (file_exists($fileusers)) {
		$usersData = file_get_contents($fileusers);
	}
	return json_decode($usersData, true);
}
$fileplats = __DIR__ . '/public/data/plats.json';
//Fonction pour charger les plats à partir du fichier JSON 
function loadPlatsFromFile()
{
	global $fileplats;
	//tous les plats
	$platsData = [];
	if (file_exists($fileplats)) {
		$platsData = json_decode(file_get_contents($fileplats), true);
	}
	return $platsData;
}

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = $_GET['id'] ?? '';
//si pas de id alors redireger vers index.php
if (!$id) {
	header('Location: /');
	die();
} else {
	//si id défini alors charger données corespondant à id a partir du fichier json
	$lesrepas = loadPlatsFromFile();
	$repasIndex = array_search($id, array_column($lesrepas, 'id'));
	if (isset($repasIndex)) {
		$details = $lesrepas[$repasIndex];
	}
}
//si pas de détails pour id alors redireger vers index.php
if (!$details) {
	header('Location: /');
	die();
}
//Chercher le pseudo de l'utilisateur qui a partagé le plat à partir de userid du plat
$users = loadUsersFromFile();
$pseudoShare = '';
$userShareIndex = array_search($details['userid'], array_column($users, 'id'));
//echo $userShareIndex;
//echo $users[$userShareIndex]['pseudo'];
if (isset($userShareIndex)) {
	$pseudoShare = $users[$userShareIndex]['pseudo'];
}
//echo $pseudoShare;
//Si l'utilistaeur est connecter on cherche son pseudo
$pseudo_user = '';
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
	// L'utilisateur est connecté
	$userid = $_SESSION['user_id'];

	// On cherche les information de l'utilisateur dont id est $userid

	foreach ($users as $user) {
		if ($user['id'] === $userid) {
			$pseudo_user = $user['pseudo'];
		}
	}
}
//Calculer et afficher les notes:
//Vérifiez s'il y a des commentaires pour le repas et comptez-les
$totalReviews = count($details['reviews']) ?? 0;

// Initialiser les totals stars  repas à  0
$average_rating = 0;
$total_plat_rating = 0;
$five_star_review = 0;
$four_star_review = 0;
$three_star_review = 0;
$two_star_review = 0;
$one_star_review = 0;
$review_content = $details['reviews'] ?? [];

$five_star_progress = 0;
$four_star_progress = 0;
$three_star_progress = 0;
$two_star_progress = 0;
$one_star_progress = 0;

foreach ($review_content as $rev) {
	if ($rev['stars'] == '5') {
		$five_star_review++;
	} elseif ($rev['stars'] == '4') {
		$four_star_review++;
	} elseif ($rev['stars'] == '3') {
		$three_star_review++;
	} elseif ($rev['stars'] == '2') {
		$two_star_review++;
	} elseif ($rev['stars'] == '1') {
		$one_star_review++;
	}

	$total_plat_rating += $rev['stars'];
}
if ($totalReviews != 0) {
	$average_rating = $total_plat_rating / $totalReviews;
	$average_rating = number_format($average_rating, 1);
	$five_star_progress = ($five_star_review / $totalReviews) * 100;
	$four_star_progress = ($four_star_review / $totalReviews) * 100;
	$three_star_progress = ($three_star_review / $totalReviews) * 100;
	$two_star_progress = ($two_star_review / $totalReviews) * 100;
	$one_star_progress = ($one_star_review / $totalReviews) * 100;
}
// Vérifier si le formulaire d'avis a été soumis
?>
<!DOCTYPE html>
<html lang="fr">

<head>
	<?php require_once 'includes/head.php' ?>
	<title>Détail Plat</title>
	<link href="public/css/index.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js" integrity="sha512-nnzkI2u2Dy6HMnzMIkh7CPd1KX445z38XIu4jG1jGw7x5tSL3VBjE44dY4ihMU1ijAQV930SPM12cCFrB18sVw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

</head>

<body>
	<?php require_once 'includes/header.php' ?>
	<section class="banner-accueil d-flex justify-content-center align-items-center pt-5 mb-5">
		<div class="container my-5 py-5">
			<div class="row justify-content-center align-items-center">
				<div class="col-md-10 text-center">
					<h1 class="text-capitalize redressed py-3 banner-desc">FoodieShare</h1>
					<h3 class="redressed py-5 text-light fs-2 fst-italic d-none d-lg-block">Votre meilleur portail pour partager vos plats favoris<br />
						Faites partie de notre grande famille</h3>
				</div>
			</div>
		</div>
	</section>

	<div class="mb-5">
		<div class="articles-container">
			<div class="container">
				<div class="row">
					<div class="col-2"></div>
					<div class="col-8">
						<div class="card border-0">
							<h2 class="text-center pb-5"><?= $details['nomplat'] ?></h2>
							<div class="img-container w-100" style="background-image:url(<?= $details['image'] ?>); height: 550px"></div>
							<p class="card-text text-start mt-5"><?= $details['description'] ?></p>
							<div class="d-flex justify-content-between align-items-center">
								<p>Catégorie : <?= ucwords($details['categorie']) ?></p>
								<p>Lieu : <?= ucwords($details['localisation']) ?></p>
								<p class="text-body-secondary"><?= $details['prix'] ?>$</p>
							</div>
							<div class="mb-4 d-flex justify-content-between">
								<div>Partagé par : <span class="fw-bold"><?= $pseudoShare ?></span></div>
								<div>Date de partage : <span class="fw-bold"><?= $details['datePartage'] ?></span></div>
							</div>
							<!--Affichage des notes, de la note moyenne et les nombres d'avis -->
							<div class="row">
								<div class="col-2">
									<div class="d-flex justify-content-start">
										<svg style="display:none;">
											<defs>
												<symbol id="fivestars">
													<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd" />
													<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd" transform="translate(24)" />
													<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd" transform="translate(48)" />
													<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd" transform="translate(72)" />
													<path d="M12 .587l3.668 7.568 8.332 1.151-6.064 5.828 1.48 8.279-7.416-3.967-7.417 3.967 1.481-8.279-6.064-5.828 8.332-1.151z M0 0 h24 v24 h-24 v-24" fill="white" fill-rule="evenodd" transform="translate(96)" />
												</symbol>
											</defs>
										</svg>
										<div class="rating ">
											<progress class="rating-bg" value="<?= $average_rating ?>" max="5"></progress>
											<svg>
												<use xlink:href="#fivestars" />
											</svg>
										</div>
									</div>
								</div>
								<div class="col-3 text-start">
									<p class="text-warning mb-4">
										<b><span id="average_rating"><?= $average_rating ?></span> / 5</b>
									</p>
								</div>
							</div>
							<div class="row">
								<p class="text-start text-primary"><span class="fw-bold" id="total_review"><?= $totalReviews ?></span> Avis</p>
							</div>
						</div>
						<div class="row">
							<div class="col-6">
								<p>
								<div class="progress-label-left"><b>5</b> <i class="fas fa-star test-warning"></i></div>
								<div class="progress-label-right">(<span id="total_five_star_review"><?= $five_star_review ?></span>)</div>
								<div class="progress">
									<div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="five-star-proggress" style="width: <?= $five_star_progress . '%' ?>;"></div>
								</div>
								</p>
								<p>
								<div class="progress-label-left"><b>4</b> <i class="fas fa-star test-warning"></i></div>
								<div class="progress-label-right">(<span id="total_four_star_review"><?= $four_star_review ?></span>)</div>
								<div class="progress">
									<div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="four-star-proggress" style="width: <?= $four_star_progress . '%' ?>;"></div>
								</div>
								</p>
								<p>
								<div class="progress-label-left"><b>3</b> <i class="fas fa-star test-warning"></i></div>
								<div class="progress-label-right">(<span id="total_three_star_review"><?= $three_star_review ?></span>)</div>
								<div class="progress">
									<div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="three-star-proggress" style="width: <?= $three_star_progress . '%' ?>;"></div>
								</div>
								</p>
								<p>
								<div class="progress-label-left"><b>2</b> <i class="fas fa-star test-warning"></i></div>
								<div class="progress-label-right">(<span id="total_two_star_review"><?= $two_star_review ?></span>)</div>
								<div class="progress">
									<div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="two-star-proggress" style="width: <?= $two_star_progress . '%' ?>;"></div>
								</div>
								</p>
								<p>
								<div class="progress-label-left"><b>1</b> <i class="fas fa-star test-warning"></i></div>
								<div class="progress-label-right">(<span id="total_one_star_review"><?= $one_star_review ?></span>)</div>
								<div class="progress">
									<div class="progress-bar bg-warning" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="one-star-proggress" style="width: <?= $one_star_progress . '%' ?>;"></div>
								</div>
								</p>
							</div>

							<div class="col-6 text-end">
								<h3 class="mt-4 mb-5">Partager votre Avis sur ce repas</h3>
								<button type="button" name="add_review" id="add_review" class="btn btn-primary">Ajouter Avis</button>
							</div>
						</div>
						<!--Affichage des avis-->
						<div class="row">
							<h4 class="mt-4 mb3 text-primary fw-bold">Les avis précedents :</h4>
							<?php foreach (array_reverse($review_content) as $rev) : ?>
								<h4 class="text-start mt-2 mb-4">
									<i class="fas fa-star <?= ((int)$rev['stars']  >= 1) ? 'star-dark' : 'star-light'; ?>  mr-1" data-rating="1"></i>
									<i class="fas fa-star <?= ((int)$rev['stars']  >= 2) ? 'star-dark' : 'star-light'; ?>  mr-1" data-rating="2"></i>
									<i class="fas fa-star <?= ((int)$rev['stars']  >= 3) ? 'star-dark' : 'star-light'; ?>  mr-1" data-rating="3"></i>
									<i class="fas fa-star <?= ((int)$rev['stars']  >= 4) ? 'star-dark' : 'star-light'; ?>  mr-1" data-rating="4"></i>
									<i class="fas fa-star <?= ((int)$rev['stars']  >= 5) ? 'star-dark' : 'star-light'; ?>  mr-1" data-rating="5"></i>
									<span class="fs-6 ms-5 p-1"><?= $rev['datetime'] ?></span>
								</h4>
								<p><?= $rev['comment'] ?></p>
								<p class="text-end fst-italic fw-bold"><?= $rev['pseudo'] ?></p>
								<hr />
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php require_once 'includes/footer.php' ?>
</body>

<!--Formulaire de saisie des avis-->
<div id="review_modal" class="modal" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Transmettre un avis </h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<h4 class="text-center mt-2 mb-4">
					<i class="fas fa-star star-light submit_star mr-1" id="submit_star_1" data-rating="1"></i>
					<i class="fas fa-star star-light submit_star mr-1" id="submit_star_2" data-rating="2"></i>
					<i class="fas fa-star star-light submit_star mr-1" id="submit_star_3" data-rating="3"></i>
					<i class="fas fa-star star-light submit_star mr-1" id="submit_star_4" data-rating="4"></i>
					<i class="fas fa-star star-light submit_star mr-1" id="submit_star_5" data-rating="5"></i>
				</h4>
				<div class="form-group">
					<input type="text" name="plat_id" id="plat_id" value="<?= $details['id'] ?>" class="d-none">
					<?php if ($pseudo_user) : ?>
						<input type="text" id="user_name_connected" class="d-none mb-3" value="<?= $pseudo_user ?>">
					<?php else : ?>
						<input type="text" name="user_name" id="user_name" class="form-control mb-3" placeholder="Saisir votre nom">
					<?php endif; ?>
				</div>
				<div class="form-group">
					<textarea name="user_review" id="user_review" class="form-control" placeholder="Saisir votre avis ici"></textarea>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary" id="save_review">Soumettre</button>
			</div>
		</div>
	</div>
</div>

<script>
	$(document).ready(function() {
		var rating_data = 0;
		$('#add_review').click(function() {
			$('#review_modal').modal('show');
			reset_background();
			$('#user_name').val('');
			$('#user_review').val('');
		});
		$(document).on('mouseenter', '.submit_star', function() {
			if (rating_data === 0) {
				var rating = $(this).data('rating');
				reset_background();
				for (var i = 1; i <= rating; i++) {
					$('#submit_star_' + i).addClass('text-warning');
				}
			}
		});

		function reset_background() {
			for (var i = 1; i <= 5; i++) {
				$('#submit_star_' + i).addClass('star-light');
				$('#submit_star_' + i).removeClass('text-warning');
			}
		}
		$(document).on('click', '.submit_star', function() {
			rating_data = $(this).data('rating');
			reset_background();
			for (var i = 1; i <= rating_data; i++) {
				$('#submit_star_' + i).addClass('text-warning');
			}

		});
		$('#save_review').click(function() {
			var user_name_connected = $('#user_name_connected').val();
			var user_name = $('#user_name').val();
			var user_review = $('#user_review').val();
			var plat_id = $('#plat_id').val();

			if (user_name_connected != null && user_name_connected != '') {
				user_name = user_name_connected;
			}
			if ((user_name == '') || user_review == '' || rating_data == 0) {
				alert("Veillez saisir tous les donnees ");
				return false;
			} else {
				$.ajax({
					url: "submit_rating.php",
					method: "POST",
					data: {
						plat_id: plat_id,
						'stars': rating_data,
						'pseudo': user_name,
						'comment': user_review
					},
					success: function(data) {
						$('#review_modal').modal('hide');
						alert(data);
					}
				})
			}
		});
	});
</script>

</html>