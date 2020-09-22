<?php
    $title="Classe de l'IP : Trouver la classe correspondante";
    require("../ressources/header.php");
    //include_once('../ressources/connectBDD.php');
    include_once('../ressources/function/fonction_utilisateur.php');
    require('../ressources/function/function_trouverClasse.php');
?>
<div class="container">
    <?php trouverClasse($title,$bdd); ?>
</div>
<?php require("../ressources/footer.php");?>

