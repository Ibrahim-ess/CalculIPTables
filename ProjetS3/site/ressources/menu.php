<nav class="navbar navbar-default navbar-static-top ">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php" ></a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="navbar">
			<ul class="nav navbar-nav">
				<li><a href="/projet/ProjetS3/site/index.php">Accueil</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Cours<span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="/projet/ProjetS3/site/Cours.php">Cours</a></li>
						<li><a href="/projet/ProjetS3/site/Memos/">Memos</a></li> 
					</ul>
				</li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Exercices<span class="caret"></span></a>
					<ul class="dropdown-menu">
					        <li><a href="/projet/ProjetS3/site/Exercices/AnalyseTrame.php">Analyse de trame</a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/Conversion.php">Conversions <i><sub><small>Binaire, Hexadécimal et Décimal</small></sub></i></a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/TrouverClasse.php">Classe IP <i><sub><small>Trouver la classe correspondante</small></sub></i></a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/TrouverClasseInverse.php">Classe IP <i><sub><small>Trouver l'IP correspondante</small></sub></i></a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/Masque.php">Masque (niveau S3)</a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/NotationCIDRS2.php">Notation CIDR (niveau S2)</a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/NotationCIDR.php">Notation CIDR (niveau S3)</a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/PrefixeMaxFacile.php">Préfixe max (S3)<i>
<sub><small>Presque Facile</small></sub></i></a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/PrefixeMaxDifficile.php">Préfixe max (S3) <i><sub><small>plus Difficile</small></sub></i></a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/SousReseaux.php">Calcul de sous-réseaux (S3)</a></li>
					        <li><a href="/projet/ProjetS3/site/Exercices/StructureTrame.php">Structure d'une trame</a></li>
					       <li><a href="/projet/ProjetS3/site/Exercices/TableRoutage.php">Table de Routage (S2,S3)</a></li>
					       <li><a href="/projet/ProjetS3/site/Exercices/Iptables.php">Iptables</a></li>
					</ul>
				</li>
				<li><a href="/projet/ProjetS3/site/news.php">Nouveautés</a></li>
				<li><a href="/projet/ProjetS3/site/quiSommesNous.php">Qui sommes-nous ?</a></li>
			</ul>
			<ul class="nav navbar-nav navbar-right">
			        <?php
                                        if(isset($_SESSION['connect']) && isLogin()){
                                                echo '<li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.getUser().'<span class="caret"></span></a>';
                                                echo '
                                                        <ul class="dropdown-menu">
                                                                <li><a href="/projet/ProjetS3/site/Utilisateur/profil.php">Profil</a></li>
                                                                <li><a href="?logout=">Se deconnecter</a></li>
                                                        </ul></li>
                                                ';
                                        }else{
                                                echo '<li><a href="?login=">Se connecter</a></li>';
                                        }
			        ?>
			</ul>
			
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
<ol class="breadcrumb">
        <?php
                $tab=array();
                if(preg_match('#((^/[a-zA-Z])[/a-zA-Z]*/)#iU',$_SERVER['PHP_SELF'],$chemin)){
                        $tab=explode("/",$chemin[0]);
                        unset($tab[0]);
                        unset($tab[count($tab)]);
                }
                if(!preg_match("/(\/(index.php)?)$/", $_SERVER['PHP_SELF'])){
                        echo '<li><a href="/projet/ProjetS3/site/index.php">Accueil</a></li>';
                        foreach($tab as $cle => $val){
                                echo '<li><a href="';
                                for($i=0;$i <$cle;$i++){
                                        echo '/'.$tab[$cle];
                                }
                                echo '/">'.$val.'</a></li>';
                        }
                }else if(!preg_match("/^(\/(index.php)?)$/", $_SERVER['PHP_SELF'])){
                        echo '<li><a href="/projet/ProjetS3/site/index.php">Accueil</a></li>';
                        $iMax=count($tab);
                        $i=0;
                        foreach($tab as $cle => $val){
                                if($i<$iMax-1){
                                        echo '<li><a href="';
                                        for($i=0;$i <$cle;$i++){
                                                echo '/'.$tab[$cle];
                                        }
                                        echo '/">'.$val.'</a></li>';
                                }
                        }
                }
                echo '<li class="active">'.$title.'</li>';
        ?>
</ol>
<?php
        if (isset($_REQUEST['logout']) && $_REQUEST['logout']=='success') {
                echo '<div class="container"><div class="text-center alert alert-success alert-dismissible" role="alert">
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <strong>Déconnexion réussie!</strong>
                </div></div>';
        }
?>
