<?php 
    $title="Évaluation";
    require('ressources/connectBDD.php'); 
    require('ressources/header.php');
    require('ressources/function/function_evaluation.php');
    $tabExercice=array(
            "masque",
            "notationCidr",
            "structureTrame",
            "trouverClasse",
            "sousReseauxFacile",
            "analyseTrame",
            "trouverClasseInverse"
        );
    
    if(!isset($_SESSION['note_exercices']) || count($_SESSION['note_exercices'])<=0){
        $_SESSION['exercices']=array_rand($tabExercice,4);
        $_SESSION['note_exercices']=array(0);
        $_SESSION['mode_eval']=true;
    }
    print_r($_SESSION['exercices']);
    echo $tabExercice[ $_SESSION['exercices'][0]];
    echo '<div class="container"><h1 class="text-center">Évaluation</h1>';
    lancer_exercice($tabExercice[ $_SESSION['exercices'][0]],$bdd);
    
?>



<?php
echo '</div>';
    require('ressources/footer.php');
?>
