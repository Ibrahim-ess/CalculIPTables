<?php
    $title="Profil";
    require("../ressources/header.php");
    require('../ressources/connectBDD.php');
    require_once('../ressources/function/fonction_utilisateur.php');
?>
<!-- CORPS -->
<div class="container">
    <?php
        
        if(empty($_SESSION['connect'])){
            echo '<div class="text-center lead"><div class="alert alert-danger" role="alert">Il faut être connecté pour accéder à cette page !</div><a href="?login=" class=" btn btn-primary btn-lg active" role="button">Se connecter</a></div>';
            
        }else{
    ?>
    <h1 class="page-header">Profil</h1>
    <div class="row">
        <div class="col-md-12">
            <h3 class="sub-header">Statistiques sur les exercices présents sur le site </h3>
            <h4 class="sub-header">Statistiques sur les exercices faits au moins une fois:</h4>
            <?php
                afficheProgExercices($_SESSION['connect'],$bdd);
            ?>
            <h4 class="sub-header">Statistiques sur tous les exercices faits :</h4>
            <?php
                afficheStatsExercicesFaits($_SESSION['connect'],$bdd);
            ?>
            <h4 class="sub-header">Récapitulatifs :</h4>
            <div class="table-responsive">
                <?php
                    afficheTabExercices($_SESSION['connect'],$bdd);
                ?>
          </div>
        </div>
    </div>
<?php 
}
?>
</div>
<?php
require("../ressources/footer.php"); 
?>
