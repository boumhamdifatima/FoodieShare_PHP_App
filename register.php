<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php'?>
    <title>Enregistrement</title>
	<link rel="stylesheet" href="public/css/register.css">
</head>

<body>
    <?php require_once 'includes/header.php'?>
	<section class="banner-register d-flex justify-content-center align-items-center pt-5">
      <div class="container my-5 py-5">
        <div class="row justify-content-center align-items-center">
          <div class="col-md-10 text-center">
            <h1 class="text-capitalize redressed py-3 banner-desc">FoodieShare</h1>
            <h3 class="redressed py-5 text-light fs-2 fst-italic d-none d-lg-block">Votre meilleur portail pour partager vos plats favoris<br/>
				Faites partie de notre grande famille
			</h3>
          </div>
        </div>
      </div>
    </section>

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
		
		<form action="register_traitment.php" method="post">
			<h2 class="text-center">S'enregistrer</h2> 
			<div class="form-group">
				<input type="text" name="nom" id="nom" class="form-control mb-2" placeholder="Nom et prénom" required="required" autocomplete="off">
			</div>      
			<div class="form-group">
				<input type="text" name="pseudo" id="pseudo" class="form-control mb-2" placeholder="Pseudo (3-16 characters)" pattern="\w{3,16}" required="required" autocomplete="off">
			</div>
			<div class="form-group">
				<input type="email" name="email" id="email" class="form-control mb-2" placeholder="Courriel" required="required" autocomplete="off">
			</div>
			<div class="form-group">
				<input type="password" name="password" id="password" class="form-control mb-2" placeholder="Mot de passe" required="required" autocomplete="off">
			</div>
			<div class="form-group">
				<input type="password" name="password_retype" id="password_retype" class="form-control mb-2" placeholder="Confirmer mot de passe" required="required" autocomplete="off">
			</div>
			<div class="form-group">
				<input type="text" name="ville" id="ville" class="form-control mb-2" placeholder="Ville" required="required" autocomplete="off">
			</div>
			<div class="form-group d-flex justify-content-between">
				<span class=" mb-2">Préférences alimentaires</span>	
				<div class="mb-2">
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="preferences[]" value="vegetarien" id="vegetarien" checked>
						<label class="form-check-label" for="vegetarien">
							Végétarien
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="preferences[]" value="poisson" id="poisson">
						<label class="form-check-label" for="poisson">
							Poisson
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="preferences[]" value="viande" id="viande">
						<label class="form-check-label" for="viande">
							Viande
						</label>
					</div>
					<div class="form-check">
						<input class="form-check-input" type="checkbox" name="preferences[]" value="poulet" id="poulet">
						<label class="form-check-label" for="poulet">
							Poulet
						</label>
					</div>
				</div>
				<div></div>
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block">S'enregistrer</button>
			</div>   
		</form>
		<div>
			<p class="text-center fw-semibold">Vous avez déjà un compte?</p>
		</div>
		<div class="text-center">
			<a href="login.php">CONNEXION</a>
		</div>
	</div>
	
	<?php require_once 'includes/footer.php'?>

</body>

</html>