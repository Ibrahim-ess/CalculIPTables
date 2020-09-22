<?php
    function sousReseauxFacile($title,$bdd){
        ?>
        <h1>Préfixe maximal <small>Presque Facile</small></h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                    <h3 class="panel-title">Exercice</h3>
            </div>
            <div class="panel-body">
                <div class="lead bg-info well">
    				<?php
    			        $adresse = getAdresseReseau();
    			        $adresse2;
    			        $ressemblance = rand(8, 31); //determine combien de bits les 2 premieres adresses auront en commun au minimum, cela permet d'avoir une rotation du nombre et une ressemblance cohérente entre toutes les adresses.
    			        $i;//Besoin de garder trace de $i.
    			        for($i=0;$i<(int)($ressemblance/8) ;$i++) //octets qui seront en commun.
    			            $adresse2[$i] = $adresse[$i];
    			        $reste = $ressemblance%8; //Combien de bits en communs reste t-il à rajouter
    			        if($reste > 0)
    			        {
    			            $adresse[$i] = 0; //Si on doit changer l'octet i car le reste implique de nouvelles règles, on le remet à 0.
    			            for($mem=128;$reste>0;$reste--){//$mem pour 'memory' gardera trace du bit.
    			                $adresse[$i] += $mem;
    			                $mem/=2;
    			            }
    			            $adresse2[$i] = $adresse[$i] + rand(0,255-$adresse[$i]);
    			            $adresse[$i] += rand(0,255-$adresse[$i]);
    			        }//fin du if ici pour économiser un test si $reste == 0.
    			        else
    			            $adresse2[$i] = rand(0,255); // S'il n'y a pas de reste on peut faire de l'aléatoire directement.
    			       for($i++;$i<4;$i++)
    			            $adresse2[$i] = rand(1,254);
    			       echo '<p>Le but de cet exercice est de trouver le plus long prefixe commun entre les deux adresses ci-dessous.</p></div>';
if (!isset($_POST['submit']) || $_POST['submit']=='Retry'){
    			       echo '<div class="lead bg-info well"><p>Adresse 1 : ' . implode('.', $adresse) . '<br/>Adresse 2 : ' . implode('.', $adresse2) . '<p></div>';}
    			       $reponse;
    			       $adresse1;
    			       $corrige;
    			       $textCorrige;
    	                if(isset($_POST['submit'])){
            				if(isset($_POST['ip']) and isset($_POST['taille']) and trim($_POST['ip'])!='' and trim($_POST['taille'])!=''){
            		    		if(preg_match('#(^[0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}).([0-9]{1,3}$)#', $_POST['ip'], $tabIp)){//$tabIp contiendra dans les cases 1 à 4 les octets 1 à 4. 
            		    			if(preg_match('#^[0-9]*$#', $_POST['taille'])){
            		    				if($_POST['taille'] <= 32){
            		    				    $reponse = "<p>Adresse 1 : " . $_POST['adresse1'] . '<br/>Adresse 2 : ' . $_POST['adresse2'];
            		    					$reponse.= '<br/>Réponse : ' . $_POST['ip'] . '/' . $_POST['taille'] . '</p>';
            		    					$adresse1 = explode('.', $_POST['adresse1']);
            		    					$adresse2 = explode('.', $_POST['adresse2']);
            		    					$corrige = getPlusLong($adresse1, $adresse2, $_POST['ressemblance']);
            	    						$textCorrige = $corrige[0] . '.' . $corrige[1] . '.' . $corrige[2] . '.' . $corrige[3] . '/' . $corrige['taille'];
                                            if($_POST['ip'].'/'.$_POST['taille'] === $textCorrige){
            		    						echo '<div class="alert alert-success alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>'.$reponse.' <br>Bonne réponse !</strong></div>';
            		    						if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,1,$bdd);
            		    					}
            		    					else{
            		    						echo '<div class="alert alert-danger alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>'.$reponse.' <br>Mauvaise réponse !<br />La réponse était : ' . $textCorrige . '</strong></div>';
            		    						if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
            		    					}	
            	    					}
            	    					else{//Si l'utilisateur a bien saisit syntaxicalement mais n'a visiblement pas compris, on lui explique.
            	    						echo '<div class="alert alert-danger alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Il ne peux y avoir plus de 32 bits dans le masque !</strong></div>';
            	    						if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
            	    					}
            	    				}
            	    				else{//En cas de mauvaise écriture de la taille.
            	    					echo '<div class="alert alert-danger alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Le second champ correspond à la taille, il ne doit contenir que des chiffres !</strong></div>';
            	    					if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
            	    				}
            		    		}
            		    		else{//En cas de mauvaise écriture d'ip, volontaire ou non.
            		    			echo '<div class="alert alert-danger alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Le premier champ correspond à l\'ip et il n\'est pas syntaxicalement correcte !</strong></div>';	
            		    			if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
            		    		}
            		    	}
            		    	else{ //Si l'utilisateur ne s'est pas donné la peine de saisir, on lui renvoie de nouvelles adresses.
            		    		echo '<div class="alert alert-warning alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>Il faut remplir tous les champs !</strong></div>';
            		    	}
?>
<form method="POST">
<div class="row"><div class="col-md-offset-4 col-xs-12 col-md-4">
<input type="submit" name="retry" value="Nouvel exercice" class="btn btn-warning col-xs-12" />
</div>
<div class="row  form-group"></div>
</div>
</form>
<?php
    		            } else {
    				?>
    		    <form action = "" method="post">
    	            <div class="form-group">
    	            	<div class="input-group <?php if(isset($_POST['submit'])): if(isset($_POST['ip']) and isset($_POST['taille']) and trim($_POST['ip'])!='' and trim($_POST['taille'])!=''): if($_POST['ip'].'/'.$_POST['taille'] === $textCorrige): echo 'has-success'; else: echo 'has-error';endif; else: echo 'has-warning'; endif;endif;?>">
    	            		<input type="text" name="ip" id="ip" class="form-control" placeholder="0.0.0.0" aria-label="Adresse reseau"/>
    						<span class="input-group-addon">/</span>
    						<input type="text" name="taille" id="taille" class="form-control" placeholder="0" aria-label="taille"/>
    					</div>
    	            </div> 
    	            <input type='hidden' name='adresse1' value='<?php echo implode('.', $adresse);?>' />
    	            <input type='hidden' name='adresse2' value='<?php echo implode('.', $adresse2);?>' />
    	            <input type='hidden' name='ressemblance' value='<?php echo $ressemblance;?>' >
    	            <div class="form-group">
    	            	<div class="col-xs-12 col-md-4 col-md-offset-4">
    	            	    <?php if(!isset($_POST['submit'])): ?>
    	                    <input type="submit" name="submit" value="Valider" class="btn btn-success col-xs-12" />
    	                    <?php endif; ?>
    	                </div>
    	            </div>
    	            <div class="form-group">
    	            	<div class="col-xs-12 col-md-4 col-md-offset-4">
    	                    <input type="submit" name="retry" value="Nouvelles adresses" class="btn btn-warning col-xs-12" />
    	                </div>
    	            </div>
    	        </form>
    		</div>
    	</div>
    	<?php 
    }
}
?>
