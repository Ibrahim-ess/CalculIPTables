<?php
	$title="Calcul de sous-réseaux";
	include("../ressources/header.php");
	//include_once('../ressources/connectBDD.php');
	include_once('../ressources/function/fonction_utilisateur.php');
	require('../ressources/function/fonctionsReseaux.php');
	require("../ressources/function/function_sousReseaux.php");
?>
<div class="container">
    <?php sousReseaux($title,$bdd); ?>
</div>
<?php
	include("../ressources/footer.php");
?>
