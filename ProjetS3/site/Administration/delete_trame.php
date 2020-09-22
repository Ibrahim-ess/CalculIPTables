<?php
    session_start();// démarrage de la session
    $errors = array();//création d'un tableau pour les éventuelles erreurs
    require('../ressources/connectBDD.php');//Inlcusion du code nécéssaire à la connection à la base de données
    $delete_name=$_POST['nom'];
    $ajour_req = 'delete from trame where nom=:nom';//Préparation de la requete supprimant les lignes de la table trame ayant comme nom $delete_name
        try{
            $req = $bdd->prepare($ajour_req);
            $req->bindValue(":nom",$delete_name);
        	$req->execute();
        }
        catch(PDOException $e)
        {
            $errors[]= $e->getMessage();
            //die('<p> Erreur['.$e->getCode.'] : '.$e->getMessage().'</p>');
        }
    if(!empty($errors)){//Si erreur(s), on retourne la tableau
        $_SESSION['errors'] = $errors;
    }
    else{//Sinon on affecte 1 à la variable $_SESSION['success']
        $_SESSION['success'] = 1;
    }
    header('Location: admin_trame.php');//On retourne sur la page d'administration
?>
