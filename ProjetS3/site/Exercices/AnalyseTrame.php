<?php
    $title="Analyse de trame"; //Titre de la page
    require('../ressources/header.php');  //Inclusion du header et du menu
    //include_once('../ressources/connectBDD.php');
    require('../ressources/function/function_analyseTrame.php'); //Inclusion des fonction analyseTrame() et script_analyseTrame()
    include('../ressources/function/fonction_utilisateur.php'); //Inclusion des fonctions nécéssaires à l'ajout du résultat de l'exercice pour l'étudiant (réussi ou non) dans la base de données.
?>
<!-- CORPS -->
<div class="container"><!-- début du corps -->
    <div class="row" >
        <div class="col-md-12 col-xs-12">
            <!-- Appel de la fonction analyseTrame() qui affiche les différents champs à remplir ainsi que la trame à analyser. -->
            <?php $valReturn= analyseTrame($title,$bdd); ?>
        </div>
    </div>
</div><!-- fin du corps -->
<!-- CORPS -->
<?php
    include("../ressources/footer.php"); //Inclusion du footer et des différents scripts nécéssaires au fonctionnement du site.
    script_analyseTrame($valReturn[0],$valReturn[1],$valReturn[2]);//Appel de la fonction qui inclut le script permettant de vérifier la réponse de l'utilisateur.
?>
