<?php
     try{
        $bdd = new PDO('mysql:host=localhost;dbname=calcul_ip;charset=utf8', 'calcul_ip', 'JUSTFIXIT!');
    }
    catch(Exception $e){
        die('Erreur : ' . $e->getMessage());
    }
?>
