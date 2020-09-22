<?php
    require('fonction_utilisateur.php');
    require('../connectBDD.php');
   
    if(!empty($_POST['UserID']) && $_POST['UserID']!=-1 && isset($_POST['nom']) && isset($_POST['isSuccess'])) {
        exerciceFait(htmlspecialchars($_POST['UserID']),$_POST['nom'],htmlspecialchars($_POST['isSuccess']),$bdd);
    }
    //echo $_POST;
?>
