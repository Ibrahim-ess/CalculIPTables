<?php
    $title="Masque";
    include("../ressources/header.php");
    //include_once('../ressources/connectBDD.php');
    include_once('../ressources/function/fonction_utilisateur.php');
    include("../ressources/function/FonctionBoxe.php");
    require("../ressources/function/function_masque.php");
?>
<div class="container">
    <?php masque($title,$bdd); ?>
</div>
<?php
    include("../ressources/footer.php");
    script_masque();
?>
