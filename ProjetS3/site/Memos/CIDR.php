<?php
$title="CIDR et Masque";
require("../ressources/header.php");?>


<!-- CORPS -->
<div class="container">
    <h1>Notation CIDR et masque </h1>
    <div class="panel panel-default">
        <div class="panel-heading">
                <h3 class="panel-title">Mémo sur le masque</h3>
        </div>
        <div class="panel-body">
            <p>Pour créer un masque, il faut tout simplement regarder le nombre après le slash pour savoir combien de bits (parmi les 8X4=32 bits)
            il faut mettre à 1 pour obtenir le masque. </p>
            </br>
            <p><strong>NB:</strong> Il est conseillé de faire l'exercice sur les masques avant celui qui est sur la notation CIDR. </p>
            
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
                <h4 class="panel-title">Mémo sur la notation CIDR</h4>
        </div>
        <div class="panel-body">
            <p>Pour obtenir l'adresse réseau, il suffit de décomposer le masque sous forme de bits et de faire un XOR avec l'adresse IP. </p>
            </br>
            <p><strong>NB:</strong> Il est conseillé de faire l'exercie sur la notation CIDR avant celui des sous-réseaux. </p>
            
        </div>
    </div>
</div>



<?php require("../ressources/footer.php");?>
