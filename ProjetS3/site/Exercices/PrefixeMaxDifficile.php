<?php
	$title="Préfixe max : plus difficile";
	include("../ressources/header.php");
	require('../ressources/function/adresseReseau.php');
	//include_once('../ressources/connectBDD.php');
    include_once('../ressources/function/fonction_utilisateur.php');
    require("../ressources/function/function_prefixeMaxDifficile.php");
?>
<div class="container">
	<?php sousReseauxDifficile($title,$bdd); ?>
</div>
<?php
	include("../ressources/footer.php");
?>
