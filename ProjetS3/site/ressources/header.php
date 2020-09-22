<!-- Header -->
<!DOCTYPE html>
<?php

    if (!isset($_SESSION))
    	session_start();
    if(isset($_REQUEST['login']) && !isset($_SESSION['connect'])){
        require_once('login.php');
        if(authentication()){
            $_SESSION['connect']=getUser();
	    $_SESSION['exo']=basename($_SERVER['PHP_SELF'],'.php');
            include_once('connectBDD.php');
            include_once('function/fonction_utilisateur.php');
            connectionUser($_SESSION['connect'],$bdd);
        }
    }
    if(isset($_SESSION['connect'])){
        require_once('login.php');
	$_SESSION['exo']=basename($_SERVER['PHP_SELF'],'.php');
        if (isset($_REQUEST['logout']) && $_REQUEST['logout']!='success') {
            deconnexion();
        }
    }
    

?>
<html>
    <head>
        <meta charset="UTF-8">
        <?php echo "<title>$title</title>"; ?>
        <script src="/projet/ProjetS3/site/js/jquery-1.11.3.min.js"></script>
        <script src="/projet/ProjetS3/site/js/bootstrap.js"></script>
        <!-- <script src="/CalculIP/js/npm.js"></script>-->
        <link href="/projet/ProjetS3/site/css/bootstrap.css" rel="stylesheet">
        <link href="/projet/ProjetS3/site/css/style_principal.css" rel="stylesheet">
        <link rel="icon" type="/projet/ProjetS3/site/image/ico" href="/CalculIP/images/favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
	    <meta name="keywords" content="TCP/IP, Excercices, corrigés">
        <meta name="description" content="Site d'exercices corrigés autour de TCP/IP: masques, CIDR, tables de routage etc.">
    </head>
    <body>
        <header>
            <?php
                include("menu.php");
            ?>
        </header>
