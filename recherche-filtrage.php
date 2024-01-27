<?php
// Définition des chemins vers les fichiers JSON contenant les données des plats et des utilisateurs.
$filename = __DIR__ . '/public/data/plats.json';
$usersFile = __DIR__ . '/public/data/users.json';
$repas = [];
// Démarrage de la session
session_start();
// Si le fichier des utilisateurs existe, lire et décomposer le contenu JSON.
if (file_exists($usersFile)) {
    $data = json_decode(file_get_contents($usersFile), true) ?? [];
} else {
    $data = [];
}
// Initialisation de tableaux pour stocker toutes les préférences et toutes les localisations.
$allPreferences = [];
$allLocations = [];
// Si le fichier contenant les informations sur les repas existe, lire et décomposer le contenu JSON.
if (file_exists($filename)) {
    $repas = json_decode(file_get_contents($filename), true) ?? [];
}

// Pour chaque utilisateur, fusionner ses préférences avec le tableau global des préférences.
foreach ($data as $user) {
    $allPreferences = array_merge($allPreferences, $user['preferences']);
}
// Pour chaque repas, ajouter sa localisation au tableau global des localisations.
foreach ($repas as $r) {
    $allLocations[] = $r['localisation'];
}
// Filtrer les préférences et les localisations pour ne garder que les valeurs uniques.
$uniquePreferences = array_unique($allPreferences);
$uniqueLocations = array_unique($allLocations);
// Récupération des paramètres GET pour la recherche et les filtres (prix, localisation, catégorie).
$searchTerm = $_GET['search'] ?? '';
$priceFilter = $_GET['price'] ?? '';
$locationFilter = $_GET['location'] ?? '';
$categoryFilter = $_GET['categorie'] ?? '';
$results = [];
// Parcourir tous les repas pour trouver ceux qui correspondent aux critères de recherche et de filtrage.
foreach ($repas as $r) {
    // Combinaison du nom du plat et de sa description pour faciliter la recherche.
    $combinedStr = strtolower($r['nomplat'] . ' ' . $r['description']);
    $matchSearch = empty($searchTerm) || strpos($combinedStr, strtolower($searchTerm)) !== false;
    $matchPrice = empty($priceFilter) || intval($r['prix']) <= intval($priceFilter);
    $matchLocation = empty($locationFilter) || $r['localisation'] == $locationFilter;
    $matchCategory = empty($categoryFilter) || $r['categorie'] == $categoryFilter;

    // Si le repas correspond à tous les critères, l'ajouter au tableau des résultats.
    if ($matchSearch && $matchPrice && $matchLocation && $matchCategory) {
        $results[] = $r;
    }
}
?>
<!doctype html>
<html lang="fr" data-bs-theme="auto">

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
    <div class="articles-container">
        <div class="form-recherche">
            <form method="get" action="recherche-filtrage.php">
                <input type="text" name="search" placeholder="Rechercher par nom ou description" value="<?= htmlspecialchars($searchTerm) ?>">
                <input type="number" name="price" placeholder="Prix max" value="<?= htmlspecialchars($priceFilter) ?>">
                <select name="location">
                    <option value="">Toutes les localisations</option>
                    <?php foreach ($uniqueLocations as $location) : ?>
                        <option value="<?= htmlspecialchars($location) ?>" <?= ($location == $locationFilter) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($location) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <select name="categorie">
                    <option value="">Toutes les préférences</option>
                    <?php foreach ($uniquePreferences as $preference) : ?>
                        <option value="<?= htmlspecialchars($preference) ?>"><?= htmlspecialchars($preference) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit">Rechercher</button>
            </form>
        </div>
        <div class="container">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($results as $r) : ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <a href="detailrepas.php?id=<?= $r['id'] ?>" class="btn btn-light">
                                <div class="card-body">
                                    <h4 class="text-center pb-2"><?= $r['nomplat'] ?></h4>
                                    <div class="img-container w-100" style="background-image:url(<?= $r['image'] ?>)"></div>
                                    <p class="card-text text-start pt-4"><?= $r['description'] ?></p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p>Lieu :<?= $r['localisation'] ?></p>
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
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php require_once 'includes/footer.php' ?>

</body>

</html>