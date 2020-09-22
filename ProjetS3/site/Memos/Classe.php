<?php
$title="Classe de l'IP : Trouver la classe correspondante";
require("../ressources/header.php");?>

<!-- CORPS -->
<div class="container">
    <h1>Classe de l'IP <small>Comment reconnaître la classe d'une adresse IP</small></h1>
    <div class="panel panel-default">
        <div class="panel-heading">
                <h3 class="panel-title">Classe IP</h3>
        </div>
        <div class="panel-body">
            <p>Pour connaître la classe d'une adresse IP, il suffit de regarder le premier octet de l'adresse</p>
            <p>192.168.1.1 dans ce cas là c'est 192 le premier octet</p>
            <ul>
                <li>Entre 0 et 127 inclus : Classe A</li>
                <li>Entre 128 et 191 inclus : Classe B</li>
                <li>Entre 192 et 223 inclus : Classe C</li>
                <li>Entre 224 et 239 inclus : Classe D</li>
                <li>Entre 240 et 255 inclus : Classe E</li>
            </ul>
            <p>Sinon l'autre méthode consiste à regarder les bits du premier octet</p>
            <ul>
                <li>Si 0xxx xxxx : Classe A</li>
                <li>Si 10xx xxxx : Classe B</li>
                <li>Si 110x xxxx : Classe C</li>
                <li>Si 1110 xxxx : Classe D</li>
                <li>Si 1111 xxxx : Classe E</li>
            </ul>
        </div>
    </div>
</div>
<?php require("../ressources/footer.php");?>
