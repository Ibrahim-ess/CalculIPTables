<?php
    $title="Administration";//Titre de la page
    require("../ressources/header.php");//Inclusion du header et du menu
?>
<!-- CORPS -->
<div class="container"><!-- début du corps -->
    <h1>Administration</h1>
    <div class="list-group col-md-10 col-md-offset-1">
        <div href="#" class="list-group-item active">
            <h4 class="list-group-item-heading">Page d'administration</h4>
            <p class="list-group-item-text"></p>
        </div>
	<a href="liste_utilisateurs.php" class="list-group-item">
	   <h4 class="list-group-item-heading">Liste utilisateurs <small>Liste des utilisateurs avec leurs résultats</small></h4>
	</a>
        <a href="admin_trame.php" class="list-group-item"><!-- Lien vers la page d'administration de l'exercice sur les structures de trames dans le base de données. -->
            <h4 class="list-group-item-heading">Ajouter une trame <small>Ajouter à la table trame une trame pour l'excercice des structures de trames</small></h4>
        </a>
    </div>
</div><!-- fin du corps -->
<!-- CORPS -->
<?php
    require("../ressources/footer.php");//Inclusion du footer et des différents scripts nécéssaires au fonctionnement du site.
?>
