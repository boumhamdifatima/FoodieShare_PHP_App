<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php'?>
    <title>Accueil</title>
    <link href="public/css/index.css" rel="stylesheet">
</head>

<body>
    <?php require_once 'includes/header.php'?>
    <section class="banner-accueil d-flex justify-content-center align-items-center pt-5 mb-5">
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

    <!-- Marketing messaging and featurettes
    ================================================== -->
    <!-- Wrap the rest of the page in another container to center all the content. -->
    <div class="p-2">
        <div class="container mt-5">

            <!-- Two columns of text below the carousel -->
            <div class="row mb-5">
                <div class="col-2"></div>
                <div class="col-4 text-center">
                    <img src="public/img/repas2.jpg" class="bd-placeholder-img rounded-circle" width="140" height="140" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                    </img>
                    <h2 class="fw-normal">Repas Étudiant</h2>
                    <p>Découvrez la liste "Repas Étudiant" sur FoodieShare! Des plats simples, délicieux et économiques adaptés aux besoins et au budget des étudiants. Que vous cherchiez des recettes pour une soirée d'étude ou pour un repas rapide entre deux cours, cette sélection vous garantit des saveurs inoubliables sans vider votre porte-monnaie. Rejoignez la communauté FoodieShare et délectez-vous de ces pépites culinaires! 🍜🍔🥗📚</p>
                    <p><a class="btn btn-secondary" href="listerepas.php">Liste repas</a></p>
                </div><!-- /.col-4 -->

                <div class="col-4 text-center" >
                    <img src="public/img/chercher.jpg" class="bd-placeholder-img rounded-circle" width="140" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder" preserveAspectRatio="xMidYMid slice" focusable="false">
                    <title>Placeholder</title>
                    <rect width="100%" height="100%" fill="var(--bs-secondary-color)" />
                    </img>
                    <h2 class="fw-normal">Recherchez Votre Produit!</h2>
                    <p>Plongez dans notre outil de recherche intuitif pour trouver exactement ce que vous cherchez! Entrez simplement un titre ou un mot-clé pour parcourir une variété d'options. Affinez encore plus votre quête en utilisant nos filtres pratiques basés sur le prix et la localisation. Trouvez le produit parfait au bon prix, tout près de chez vous. 🔍💰📍</p>
                    <p><a class="btn btn-secondary" href="recherche-filtrage.php">Chercher repas</a></p>
                </div><!-- /.col-4 -->
                <div class="col-2"></div>
            </div><!-- /.row -->

            <!-- START THE FEATURETTES -->
            <div class="row mb-5">
                <hr class="featurette-divider mb-5">
                <div class="row featurette align-items-center">
                    <div class="col-md-7">
                        <h2 class="featurette-heading fw-normal lh-1">Burger Deluxe</h2>
                        <p class="lead">Un delicieux burger avec du fromage fondant.</p>
                    </div>
                <div class="col-md-5">
                    <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="https:\/\/www.lobels.com\/images\/uploaded\/wp-content\/uploads\/2013\/09\/4651_00ClassicBeefCheeseburgerCCWBBrochure.jpg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false"></img>
                </div>
                </div>
            </div>
            <div class="row mb-5">
                <hr class="featurette-divider mb-5">

                <div class="row featurette align-items-center">
                    <div class="col-md-7 order-md-2">
                        <h2 class="featurette-heading fw-normal lh-1">Salade Mediterranienne</h2>
                        <p class="lead">Une salade fraiche avec des legumes de saison.</p>
                    </div>
                    <div class="col-md-5 order-md-1">
                        <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500" src="https:\/\/www.lesoeufs.ca\/assets\/RecipeThumbs\/MedBowl-EGG.jpg" role="img" aria-label="Placeholder: 500x500" preserveAspectRatio="xMidYMid slice" focusable="false">

                        </img>
                    </div>
                </div>
            </div>
            <hr class="featurette-divider mb-5">
            <!-- /END THE FEATURETTES -->
        </div>
	<?php require_once 'includes/footer.php'?>

</body>

</html>