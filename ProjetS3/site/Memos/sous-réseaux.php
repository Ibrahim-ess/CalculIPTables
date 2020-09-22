<?php
$title="Sous-réseaux";
require("../ressources/header.php");?>


<!-- CORPS -->
<div class="container">
    <h1>Sous-réseaux</h1>
    <div class="panel panel-default">
        <div class="panel-heading">
                <h3 class="panel-title">Mémo sur les sous-réseaux</h3>
        </div>
        <div class="panel-body">
            <p class="justify">Le principe des sous-réseaux repose sur les masques et la notation CIDR. Il est donc fortement conseillé de faire les exercices correspondant à ces deux concepts avant de faire celui des sous-réseaux. Ici, on vous demande de créer une adresse IP qui permet de regrouper plusieurs adresses IP.
                    Pour cela, il faut procéder par étapes:</p>

                <p><strong>1ère étape:</strong></p>

<p class="justify"> Il faut d'abord décomposer toutes les adresses IP sous forme de bits. On vous conseille de les décomposer
                    les uns en-dessous des autres. </p>

                <p><strong>2ème étape:</strong></p>

<p class="justify"> Ensuite, il faut partir de la gauche et compter le nombre de bits à 1 qu'ils ont en commun.</p>

<p class="justify"> Ce nombre sera derrière le slash (notation CIDR).
Appelons ce nombre X. Ecrivez X fois 1 et ensuite remplissez avec des 0
                    jusqu'à ce que vous ayez 32 bits.</p>

                <p><strong>3ème étape:</strong></p>

<p class="justify"> Transposez en décimal et vous avez le sous-réseau.</p>
        </div>
    </div>
</div>

<?php require("../ressources/footer.php");?>
