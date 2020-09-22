<?php
    function lancer_exercice($nomFichier,$bdd){
        require('ressources/function/function_'.$nomFichier.'.php');
        switch($nomFichier){
            case "masque":
                include("ressources/function/FonctionBoxe.php");
                masque("",$bdd);
                break;
            case "notationCidr": 
                include("ressources/function/FonctionBoxe.php");
                notationCidr("",$bdd);
                break;
            case "structureTrame": structureTrame("",$bdd);
                break;
            case "trouverClasse": trouverClasse("",$bdd);
                break;
            case "sousReseauxFacile": sousReseauxFacile("",$bdd);
                break;
            case "analyseTrame": analyseTrame("",$bdd);
                break;
            case "trouverClasseInverse": trouverClasseInverse("",$bdd);
                break;
        }
    }

?>
