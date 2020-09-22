<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
<?php
    $title="Structure de trame";//Titre de la page
    //require("../ressources/connectBDD.php");//Inlcusion du code nécéssaire à la connexion à la base de données
    require('../ressources/header.php');//Inclusion du header et du menu
    include('../ressources/function/function_structureTrame.php');//Inclusion de la fonction structureTrame()
    structureTrame($title,$bdd);//Appel de la fontion structureTrame() qui affiche les différents champs à remplir.
    include("../ressources/footer.php");//Inclusion du footer et des différents scripts nécéssaires au fonctionnement du site.
?>
