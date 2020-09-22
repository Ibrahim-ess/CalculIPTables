<?php
    $title="Notation CIDR S2";
    include("../ressources/header.php");
    //include_once('../ressources/connectBDD.php');
    include_once('../ressources/function/fonction_utilisateur.php');
    include("../ressources/function/FonctionBoxe.php");
    require("../ressources/function/function_notationCidr.php");
?>
<div class="container">
    <?php notationCidr($title,$bdd,true); ?>
</div>
<?php
    include("../ressources/footer.php");
?>
