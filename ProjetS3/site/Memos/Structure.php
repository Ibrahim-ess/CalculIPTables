<?php
$title="Structure de trame";
require("../ressources/header.php");?>

<!-- CORPS -->
<div class="container">
    <h1>Structure de trame</h1>
    <div class="panel panel-default">
        <div class="panel-heading">
                <h3 class="panel-title">Mémo sur la structure de trame</h3>
        </div>
        <div class="panel-body">
            <p>Pour connaître la structure d'une trame veuillez lire vos cours ou l'annexe que l'on vous donne en contrôle.</p>
            <p>Conversion pour la taille des champs:</p>
            <p><b>Rappel : </b>1 octet = 8 bits</p>
            <p><b>Exemple : </b><br>Si la taille d'un champ est de 6 octets (par exemple dans la trame Ethernet, les champs pour l'adresse de destination et l'adresse source), sa taille en bits est de 6x8 = 48 bits.</p>
        </div>
    </div>
</div>
<?php require("../ressources/footer.php");?>
