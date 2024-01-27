<?php

session_status() === PHP_SESSION_ACTIVE ?: session_start();

$fileplats = __DIR__ . '/public/data/plats.json';

$repas = [];
$categories = [];

$_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$selectedCat = $_GET['cat'] ?? '';



$cattmp = [];
if (file_exists($fileplats)) {
	$repas = json_decode(file_get_contents($fileplats), true) ?? [];
	$cattmp = array_map(fn ($a) => $a['categorie'], $repas);
	$categories = array_reduce($cattmp, 'count_categories', []);
	$articlesParCategorie = array_reduce($repas, 'classifier_articles', []);
}

function count_categories($accumulateur, $valeur_courante)
{
	if (isset($accumulateur[$valeur_courante])) {
		$accumulateur[$valeur_courante]++;
	} else {
		$accumulateur[$valeur_courante] = 1;
	}
	return $accumulateur;
}

function classifier_articles($acc, $repas)
{
	if (isset($acc[$repas['categorie']])) {
		$acc[$repas['categorie']][] = $repas;
	} else {
		$acc[$repas['categorie']] = [$repas];
	}
	return $acc;
}

// If a category is selected, filter the repas by that category
if ($selectedCat) {
	$repas = array_filter($repas, function ($rep) use ($selectedCat) {
		return $rep['categorie'] === $selectedCat;
	});
}

// Nombre de repas à afficher par page
$repasPerPage = 3;
// Calcul du nombre total de repas dans le tableau $repa
$totalRepas = count($repas);
// Calcul du nombre total de pages, arrondi à l'entier supérieur
$totalPages = ceil($totalRepas / $repasPerPage);
// Vérification de l'existence du paramètre "page" dans l'URL et s'il est numérique. Sinon, définir la page par défaut à 1
$current_page = isset($_GET['page']) && is_numeric($_GET['page']) ? intval($_GET['page']) : 1;
// S'assurer que la page courante ne dépasse pas le nombre total de pages
$current_page = min($current_page, $totalPages);
// S'assurer que la page courante est au moins 1
$current_page = max($current_page, 1);
// Calcul de l'index de départ pour les repas à afficher sur la page courante
$start = ($current_page - 1) * $repasPerPage;
// Extraire les repas à afficher sur la page courante à partir du tableau $repas
$currentRepas = array_slice($repas, $start, $repasPerPage);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
	<?php require_once 'includes/head.php' ?>
	<title>Liste Plats</title>
	<link href="public/css/index.css" rel="stylesheet">
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
	<div>
		<div class="articles-container">
			<div class="categorie-container">
				<ul>
					<li class="<?= empty($selectedCat) ? 'cat-active' : '' ?>">
						<a href="/listerepas.php">Tous les repas <span class="small">(<?= count($cattmp) ?>)</span></a>
						<hr />
					</li>
					<?php foreach ($categories as $categorie => $catNum) : ?>

						<li class="<?= $selectedCat === $categorie ? "cat-active" : '' ?>">
							<a href="/listerepas.php?cat=<?= $categorie ?>"><?= ucfirst($categorie) ?><span class="small">(<?= $catNum ?>)</span></a>
							<hr />
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="container">
				<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
					<?php foreach ($currentRepas as $r) : ?>
						<div class="col">
							<div class="card shadow-sm">
								<a href="detailrepas.php?id=<?= $r['id'] ?>" class="btn btn-light">
									<div class="card-body">
										<div class="card-body">
											<h4 class="text-center pb-2"><?= $r['nomplat'] ?></h4>
											<div class="img-container w-100" style="background-image:url(<?= $r['image'] ?>); height: 250px"></div>
											<p class="card-text text-start pt-4"><?= $r['description'] ?></p>
											<div class="d-flex justify-content-between align-items-center">
												<p>Lieu : <?= ucwords($r['localisation']) ?></p>
												<p class="text-body-secondary"><?= $r['prix'] ?>$</p>
											</div>
											<?php
											//Vérifiez s'il y a des commentaires pour le repas et comptez-les
											$totalReviews = count($r['reviews']) ?? 0;

											// Initialize the total stars for this repas to 0
											$average_rating = 0;
											$total_plat_rating = 0;

											foreach ($r['reviews'] as $rev) {
												$totalReviews++;
												$total_plat_rating += $rev['stars'];
											}
											if ($totalReviews != 0) {
												$average_rating = $total_plat_rating / $totalReviews;
											}
											$average_rating = number_format($average_rating, 1);
											?>
											<div class="row">
												<div class="col-5">
													<div class="d-flex justify-content-center">
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
												<p class="text-end text-primary"><span class="fw-bold" id="total_review"><?= $totalReviews ?></span> Avis</p>
											</div>
										</div>
									</div>
								</a>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<!-- Pagination  -->
				<div class="pagination">
					<?php for ($i = 1; $i <= $totalPages; $i++) : ?>
						<?php if ($i == $current_page) : ?>
							<span><?php echo $i; ?></span>
						<?php else : ?>
							<a href="?<?= ($selectedCat) ? 'cat=' . $selectedCat . '&' : '' ?>page=<?php echo $i; ?>"><?php echo $i; ?></a>
						<?php endif; ?>
					<?php endfor; ?>
				</div>
			</div>

		</div>
	</div>

	<?php require_once 'includes/footer.php' ?>

</body>

</html>