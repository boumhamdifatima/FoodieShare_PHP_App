<?php
session_status() === PHP_SESSION_ACTIVE ?: session_start();
?>
<header>
  <nav class="cc-navbar navbar position-fixed navbar-expand-md w-100" data-bs-theme="dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="../">
        <img src="public/img/logo.png" alt="FoodieShare" class="logo">
      </a>
      <a class="navbar-brand text-uppercase mx-0 py-3 d-none d-lg-block fw-bolder fs-2" href="../">FoodieShare</a>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item pe-4">
            <a class="nav-link" aria-current="page" href="../">Accueil</a>
          </li>
          <li class="nav-item pe-4">
            <a class="nav-link" href="../listerepas.php">Liste Des Repas</a>
          </li>
          <li class="nav-item pe-4">
            <a class="nav-link" href="../moncompte.php">Mon Compte</a>
          </li>
          <!--Ajouter la condition si l'utilisateur est connecté  pour afficher se déconnecter sur le header-->
          <!--Sinon afficher login sur le header-->
          <li class="nav-item pe-4">
            <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) : ?>
              <a class="nav-link" href="../sedeconnecter.php">Se Déconnecter</a>
            <?php else : ?>
              <a class="nav-link" href="../login.php">Login</a>
            <?php endif; ?>
          </li>
        </ul>
      </div>
      <a class="nav-link text-center text-light" href="../recherche-filtrage.php"><i class="fa-solid fa-magnifying-glass fa-2xl" style="color: #0860f7;"></i></a>
    </div>
  </nav>
</header>