<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php'?>
    <title>Login</title>
	<link rel="stylesheet" href="public/css/register.css">
</head>

<body>
    <?php require_once 'includes/header.php'?>
	<section class="banner-login d-flex justify-content-center align-items-center pt-5">
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

	<div class="login-form container">
		<?php 
			if(isset($_GET['login_err']))
            {
                $err = htmlspecialchars($_GET['login_err']);

				switch($err)
				{
					case 'password':
					?>
						<div class="alert alert-danger">
							<strong>Erreur</strong> mot de passe incorrect
						</div>
					<?php
					break;

					case 'email':
					?>
						<div class="alert alert-danger">
							<strong>Erreur</strong> email incorrect
						</div>
					<?php
					break;

					case 'already':
					?>
						<div class="alert alert-danger">
							<strong>Erreur</strong> compte non existant
						</div>
					<?php
					break;
				}
            }
        ?> 
            
		<form action="login_traitment.php" method="post">
			<h2 class="text-center">Connexion</h2>  
			<hr class="border border-primary border-2 opacity-25 mb-5">     
			<div class="form-group">
				<input type="email" name="email" class="form-control mb-2" placeholder="Email" required="required" autocomplete="off">
			</div>
			<div class="form-group">
				<input type="password" name="password" class="form-control mb-2" placeholder="Mot de passe" required="required" autocomplete="off">
			</div>
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-block">Connexion</button>
			</div>
			<hr class="border border-primary border-2 opacity-25 mt-5">
			<p class="text-end fw-semibold">Vous n'avez pas de compte?</p>
			<p class="text-end fs-5"><a href="register.php">S'enregistrer</a></p>
		</form>
    </div>

	<?php require_once 'includes/footer.php'?>

</body>

</html>