<?php
$title="Table de routage";
require("../ressources/header.php");?>
<!-- CORPS -->
<div class="container">
    <h1>Table de routage </h1>
    <div class="panel panel-default">
        <div class="panel-heading">
                <h3 class="panel-title">Table de routage</h3>
        </div>
        <div class="panel-body">
            <p><strong>1er cas:</strong> </p>
                    <p class="justify">Si le réseau de départ n'est relié qu'à un seul réseau via un routeur alors l'adresse par défaut est celui du routeur qui relie ces deux réseaux. 
            </p>
             <p><strong>2eme cas:</strong> </p>
                    <p class="justify">Si le réseau de départ est relié à plusieurs réseaux, il faut choisir le routeur par défaut en fonction d'où l'on veut aller. </p>

<p class="justify">En effet, si l'on veut aller vers la droite (Resp. vers la gauche), alors c'est le routeur qui mène vers le réseau à droite (Resp. à gauche) qui sera le default. 
            </p>

            <p><strong>Cas bonus:</strong> </p>

<p class="justify">Si, vous avez un schéma où il n'y a que deux réseaux, alors pas besoin de chemin par défaut. En effet, il suffit juste de mettre l'adresse
                    du routeur qui relie les deux réseaux dans la table de routage de chaque machine.
            </p>
        </div>
    </div>
</div>



<?php require("../ressources/footer.php");?>
