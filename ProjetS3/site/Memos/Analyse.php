<?php
$title="Analyse de trame";
require("../ressources/header.php");?>

<!-- CORPS -->
<div class="container">
    <h1>Analyse de trame</h1>
    <div class="panel panel-default">
        <div class="panel-heading">
                <h3 class="panel-title">Mémo sur l'analyse de trame</h3>
        </div>
        <div class="panel-body">
            <p>Pour connaître la structure d'une trame veuillez lire vos cours ou l'annexe que l'on vous donne en contrôle.</p>
            <p>Conversion pour la taille des champs:</p>
            <p><b>Rappel : </b><br>Les données de la trame sont en hexadécimal, c'est à vous d'effectuer des conversions en binaire ou décimal si nécessaire.</p>
            <p><b>Exemple : </b><br>Pour les paquets IPv4, les champs des adresses IP de l'émetteur et du destinataire sont à écrire en décimal au format X.X.X.X (par exemple: 228.117.15.2).</p>
            <p>Si vous ne savez pas si le champ à remplir est en hexadécimal, binaire ou décimal, nous vous invitons à regarder sur votre support de cours et la feuille que l'on vous distribue en annexe pendant les contrôles de réseau.</p>
        </div>
    </div>
</div>
<?php require("../ressources/footer.php");?>
