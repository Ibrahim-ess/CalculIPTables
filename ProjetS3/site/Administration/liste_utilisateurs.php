<?php
    session_start();
    $title="Liste Utilisateurs";//Titre de la page
    require('../ressources/header.php');//Inclusion du header et du menu
    require('../ressources/connectBDD.php');
?>
<!-- CORPS -->
<div class="container">
    <div class="row" >
        <div class="col-md-12 col-xs-12">
            <h1 class="text-center" >Table utilisateurs</h1>
		<table class="table table-striped table-bordered">
		   <thead><tr>
                         <th class="text-center">Utilisateur</th>
			 <th class="text-center">Exercice</th>
			 <th class="text-center">Réussi</th>
			 <th class="text-center">Echec</th>
			 <th class="text-center">Der. modif</th>
		   </thead>
                   <tbody id="tbody"> 
<?php
	try{
		$req = $bdd->prepare('SELECT utilisateurs.userID,nom_exercice,reussi,echec,ts FROM utilisateurs,exercice_fait,exercices WHERE utilisateurs.userID = exercice_fait.userID AND exercice_fait.id_exercice=exercices.id_exercice ORDER BY utilisateurs.userID,ts');
		$req->execute();

		while ($row = $req->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
			print '<TR>';
			foreach ($row as $value) {
				print "<TD>$value</TD>";
			}
			print '</TR>';
		}
		
	} catch (PDOException $e) {
		print $e->getMessage();
	}
?>
		</tbody></table>
	</div>
    </div>
</div><!-- fin du corps -->
<?php
    include("../ressources/footer.php");//Inclusion du footer et des différents scripts nécéssaires au fonctionnement du site.
?>
