<?php
	$title="Préfixe max : Facile";
	include("../ressources/header.php");
	require('../ressources/function/adresseReseau.php');
	//include_once('../ressources/connectBDD.php');
    include_once('../ressources/function/fonction_utilisateur.php');
    require("../ressources/function/function_prefixeMaxFacile.php");
?>
<div class="container">
    <?php sousReseauxFacile($title,$bdd); ?>
</div>
<?php
	include("../ressources/footer.php");
?>
