<?php
    $title="Classe IP : Trouver une IP correspondante";
    include("../ressources/header.php");
    //include_once('../ressources/connectBDD.php');
    include_once('../ressources/function/fonction_utilisateur.php');
    require('../ressources/function/function_trouverClasseInverse.php');
    $message_reponse;
    $reponse;
?>
<div class="container">
    <?php trouverClasseInverse($title,$bdd); ?>
</div>
<?php
    include("../ressources/footer.php");
?>
