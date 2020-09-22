<?php
    $title="Exercices corrigés autour de TCP/IP - CalculIP";//Titre de la page
    require("ressources/header.php");//Inclusion du header et du menu
    require_once("ressources/connectBDD.php");
?>
<!-- CORPS -->
<div class="container"><!-- début du corps -->
    <h1>CalculIP : Accueil</h1>
<?php
	//$ID=rand(1,7);
try {
 	//$requete=$bdd->prepare("SELECT * FROM citations  WHERE id=$ID");
 	$requete=$bdd->prepare("SELECT * FROM citations order by RAND() LIMIT 1");
        $requete->execute();
}catch(PDOException $e){
	echo '';
}
        $rep=$requete->fetch(PDO::FETCH_ASSOC);
?>
    <blockquote>
	<p><?=$rep['texte']?>.</p>
	<footer><cite>
	<?=$rep['auteur']?> --- <?=$rep['commentaire']?>.
	</cite></footer>
    </blockquote>
    <div class="row">
    	<div class="col-sm-6 col-md-4">
		    <div class="panel panel-primary">
			<!-- Default panel contents -->
			<div class="panel-heading">Quelques exercices</div>
		<!-- List group -->
		<ul class="list-group">
		<a href="Exercices/Conversion.php" class="list-group-item">
		<dl class="dl-horizontal">
		<dt>Conversions</dt>
		<dd>Entraînez-vous à des changements de base&nbsp;: binaire, décimal, hexa!</dd></dl>
		</a>
		<a href="Exercices/TrouverClasse.php" class="list-group-item">
		<dl class="dl-horizontal">
		  <dt>Classes d'adresse IP</dt>
		  <dd>Entraînez-vous à trouver la bonne classe d'adresse IP&nbsp;!</dd>
		</dl>
		</a>
		<a href="Exercices/TableRoutage.php" class="list-group-item">
		<dl class="dl-horizontal">
		  <dt>Tables de routage</dt>
		  <dd>Entraînez-vous à faire des tables de routage&nbsp;!</dd>
		</dl>
		</a>
		<a href="Exercices" class="list-group-item">
			<dl class="dl-horizontal">
			<dt>Plus d'exercices</dt>
			<dd>Voir le menu !</dd>
			</dl>
		</a>
		</ul>
	</div>
</div>
		<div class="col-sm-6 col-md-4">
		    <div class="panel panel-primary">
			<!-- Default panel contents -->
				<div class="panel-heading">Quelques mémos pour les exos</div>
				<!-- List group -->
				<ul class="list-group">
					<a href="Memos/Classe.php" class="list-group-item">
						<dl class="dl-horizontal">
						  <dt>Les classes</dt>
						  <dd>Petit mémo sur les différentes classes.</dd>
						</dl>
					</a>
					<a href="Memos/Analyse.php" class="list-group-item">
						<dl class="dl-horizontal">
						  <dt>L'analyse de trame</dt>
						  <dd>Petit mémo sur l'analyse de trame.</dd>
						</dl>
					</a>
					<a href="Memos" class="list-group-item">
                                                <dl class="dl-horizontal">
                                                  <dt>Plus de mémos</dt>
                                                  <dd>Voir le menu !</dd>
						</dl>
					</a>

				</ul>
			</div>
		</div>
		<div class="col-sm-6 col-md-4">
		    <div class="panel panel-primary">
			<!-- Default panel contents -->
				<div class="panel-heading">Quelques cours</div>
				<!-- List group -->
				<ul class="list-group">
					<a href="https://www-info.iutv.univ-paris13.fr/~butelle/Polys/M2102_Reseaux" target="_blank" class="list-group-item">
						<dl class="dl-horizontal">
						  <dt>Cours S2</dt>
						  <dd>Accedez aux cours du S2. Accès restreint.</dd>
						</dl>
					</a>
					<a href="https://www-info.iutv.univ-paris13.fr/~butelle/Polys/M3102_Reseaux" target="_blank" class="list-group-item">
						<dl class="dl-horizontal">
						  <dt>Cours S3</dt>
						  <dd>Accedez aux cours du S3. Accès restreint.</dd>
						</dl>
					</a>
				</ul>
			</div>
		</div>
	</div>
</div>
<!-- CORPS -->
<?php
	require("ressources/footer.php");//Inclusion du footer
?>
