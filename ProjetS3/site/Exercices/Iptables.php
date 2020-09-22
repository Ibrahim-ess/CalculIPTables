<?php
    $title="Analyse de trame"; //Titre de la page
    require('../ressources/header.php');  //Inclusion du header et du menu
    //include_once('../ressources/connectBDD.php');
    include('../ressources/function/fonction_utilisateur.php'); //Inclusion des fonctions nécéssaires à l'ajout du résultat de l'exercice pour l'étudiant (réussi ou non) dans la base de données.
?>
<div class="container">
            <h1>IpTables </h1>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Exercice</h3>
                </div>
                <div class="panel-body">
                    <div class="lead bg-info well">
                        <p>Soit une entreprise qui utiise des adresses privée[...]</p>
                        <p>On veux assurer les éléments suivants:</p>
                     </div>
                     <div class="panel panel-default">
                        <div class="lead bg-info well">
                            <h3 class="panel-title">Niveau 1:</h3>
                        </div>
                        <div class="panel-body">
                    <div class="lead bg-info well">
                        <p>Tout ce qui n’est pas autorisé est interdit.</p>
                        <br>
                        <h3 class="panel-title">iptables -P 
                            <select>
                                <option>INPUT</option>
                                <option>FORWARD</option>
                                <option>OUTPUT</option>
                            </select> 
                        DROP</h3>
                        <br>
                        <h3 class="panel-title">iptables 
                            <select>
                                <option>-d</option>
                                <option>-A</option>
                                <option>-P</option>
                                <option>-s</option>
                            </select>  
                        OUTPUT DROP</h3>
                        <br>
                        <h3 class="panel-title">iptables -P INPUT 
                            <select>
                                <option>DROP</option>
                                <option>ACCEPT</option>
                                <option>LOG</option>
                                <option>REJECT</option>
                            </select> </h3>
                     </div>
                     </div>
                    </div> 
                </div>
                <center>
                    <input type="button" value="Valider">
                    <input type="button" value="Voir réponse">
                </center>
            </div>
        </div>



        <footer class="footer text-center">
            <div>
                <img class="img_footer" style="float: left;left: 0;" src="images/IUT_Villetaneuse_Logo.png" alt="logo iut villetaneuse"/>
                <img class="img_footer" style="float: right;right: 0;" src="images/Logo-UP13-noirS.png" alt="logo université paris 13"/>
            </div>
            <div class="container">
                <p class="text-muted">GPL v3</p>
            </div>
        </footer>
    </body>
</html>




        