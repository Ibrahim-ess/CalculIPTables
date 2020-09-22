<?php
    session_start();
    $title="Ajout de structure de trame";//Titre de la page
    require('../ressources/header.php');//Inclusion du header et du menu
    require('../ressources/connectBDD.php');//Inlcusion du code nécéssaire à la connection à la base de données
    if(!array_key_exists('nbChamp',$_SESSION)){//Verification du nombre de champs
        $_SESSION['nbChamp']=1;//Si $_SESSION['nbChamp'] n'existe pas, on l'initialise à 1
    }
?>
<!-- CORPS -->
<div class="container"><!-- début du corps -->
    <div class="row" >
        <div class="col-md-12 col-xs-12">
            <h1 class="text-center" >Administration de la table trame</h1>
            <?php if(array_key_exists('errors',$_SESSION)): ?><!-- S'il y a une ou plusieurs erreur(s), on affiche les erreurs sous formes d'alert-->
			<div class='alert alert-danger alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>
				<?php
				    foreach($_SESSION['errors'] as $valeur){//Affichage des différentes erreurs
				        echo $valeur.'<br>';
				    }
				?>
			</strong></div>
			<?php endif; ?>
			<?php if(array_key_exists('success',$_SESSION)): ?><!-- Sinon on notifie l'utilisateur que les mises à jour ont bien été effectuées -->
			<div class='alert alert-success alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>
				La base a bien été mise à jour !
			</strong></div>
			<?php endif; ?>
            <div class="panel panel-default <?php if(array_key_exists('success',$_SESSION)&&$_SESSION['success']==1): echo 'panel-success'; elseif(!array_key_exists('type',$_SESSION)&&!array_key_exists('ajoutTab',$_SESSION)&&!array_key_exists('ajoutTabMin',$_SESSION)&&!array_key_exists('ajoutTabMax',$_SESSION)): ;else: echo'panel-danger'; endif; ?>"><!-- On attribue une class en fonction de la présence d'erreur(s) ou non -->
                <div class="panel-heading">
                    <h3 class="panel-title">Ajouter un tableau dans la base de données:</h3>
                </div>
                <div class="panel-body">
                    <form id="contact" method="post" action="ajout_trame.php" class="col-xs-12 col-md-12"><!-- début du formulaire d'ajout de trame dans la base de données -->
                        <div class="col-xs-12 col-md-12">
                            <div class="form-group">
                                <div class="input-group col-xs-12 col-md-12 <?php if(array_key_exists('type',$_SESSION)&&$_SESSION['type'][1]==1): echo 'has-success'; elseif(array_key_exists('type',$_SESSION)&&$_SESSION['type'][1]==0): echo'has-error'; endif; ?>"><!-- On attribue une class en fonction de la présence d'erreur(s) ou non -->
                                    <div class="input-group-addon">Type : </div>
                                    <input type="text" class="form-control" id="inputname" name="name" placeholder="Ex : trame Ethernet" value="<?php if(array_key_exists('type',$_SESSION)):echo $_SESSION['type'][0]; endif; ?>"><!-- Si le champ existe, alors on l'affiche, sinon on affiche un champ vide. -->
                                </div>
                            </div>
                            <div style="margin-bottom: 15px;">
                                <table class="table">
                                    <tbody id="tbody">
                                        <tr id="champ_0" class="form-inline form-group">
                                            <td class="col-xs-4 col-md-4">
                                                <div class="col-xs-12 col-md-12 input-group <?php if(array_key_exists('ajoutTab',$_SESSION)&&$_SESSION['ajoutTab'][1]==1): echo 'has-success'; elseif(array_key_exists('ajoutTab',$_SESSION)&&$_SESSION['ajoutTab'][1]==0): echo'has-error'; endif; ?>"><!-- On attribue une class en fonction de la présence d'erreur(s) ou non -->
                                                    <div class="input-group-addon">Nom :</div>
                                                    <input type="text" class="form-control" id="inputname_0" name="name_0" placeholder="Ex : DA" value=<?php if(array_key_exists('ajoutTab',$_SESSION)): echo $_SESSION['ajoutTab'][0]; endif; ?>><!-- Si le champ existe, alors on l'affiche, sinon on affiche un champ vide. -->
                                                </div>
                                            </td>
                                            <td class="col-xs-4 col-md-4">
                                                <div class="col-xs-12 col-md-12 input-group <?php if(array_key_exists('ajoutTabMin',$_SESSION)&&$_SESSION['ajoutTabMin'][1]==1): echo 'has-success'; elseif(array_key_exists('ajoutTabMin',$_SESSION)&&$_SESSION['ajoutTabMin'][1]==0): echo'has-error'; endif; ?>"><!-- On attribue une class en fonction de la présence d'erreur(s) ou non -->
                                                    <div class="input-group-addon">Taille fixe/min :</div>
                                                    <input type="text" class="form-control" id="inputmin_0" name="min_0" placeholder="Ex : 48" value=<?php if(array_key_exists('ajoutTab',$_SESSION)): echo $_SESSION['ajoutTabMin'][0]; endif; ?>><!-- Si le champ existe, alors on l'affiche, sinon on affiche un champ vide. -->
                                                    <div class="input-group-addon">bit(s)</div>
                                                </div>
                                            </td>
                                            <td class="col-xs-4 col-md-4">
                                                <div class="col-xs-12 col-md-12 input-group <?php if(array_key_exists('ajoutTabMax',$_SESSION)&&$_SESSION['ajoutTabMax'][1]==1): echo 'has-success'; elseif(array_key_exists('ajoutTabMax',$_SESSION)&&$_SESSION['ajoutTabMax'][1]==0): echo'has-error'; endif; ?>"><!-- On attribue une class en fonction de la présence d'erreur(s) ou non -->
                                                    <div class="input-group-addon">Taille max :</div>
                                                    <input type="text" class="form-control" id="inputmax_0" name="max_0" placeholder="* si pas de taille max" value=<?php if(array_key_exists('ajoutTab',$_SESSION)): echo $_SESSION['ajoutTabMax'][0]; endif;?>><!-- Si le champ existe, alors on l'affiche, sinon on affiche un champ vide. -->
                                                    <div class="input-group-addon">bit(s)</div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php 
                                        if($_SESSION['nbChamp']>1){
                                            for($i=1;$i<$_SESSION['nbChamp'];$i++){//Affichage des autres champs que celui de bases dans le cas ou il y en aurait plus d'un.
                                                $affi.= '<tr id="champ_'.$i.'" class="form-inline form-group"><td class="col-xs-4 col-md-4"><div class="col-xs-12 col-md-12 input-group ';
                                                if(array_key_exists("ajoutTab",$_SESSION)&&$_SESSION["ajoutTab"][$i*2+1]==1){//On attribue une class en fonction de la présence d'erreur(s) ou non
                                                    $affi.= 'has-success';
                                                }
                                                elseif(array_key_exists("ajoutTab",$_SESSION)&&$_SESSION["ajoutTab"][$i*2+1]==0){//On attribue une class en fonction de la présence d'erreur(s) ou non
                                                    $affi.='has-error';
                                                } 
                                                $affi.='"><div class="input-group-addon">Nom :</div>';
                                                if(array_key_exists("ajoutTab",$_SESSION)){
                                                    $affi.= '<input type="text" class="form-control" id="inputname_'.$i.'" name="name_'.$i.'" value='.$_SESSION["ajoutTab"][$i*2].'>';
                                                }
                                                else{
                                                    $affi.='<input type="text" class="form-control" id="inputname_'.$i.'" name="name_'.$i.'">';
                                                }
                                                $affi.='</div></td><td class="col-xs-4 col-md-4"><div class="col-xs-12 col-md-12 input-group ';
                                                if(array_key_exists("ajoutTabMin",$_SESSION)&&$_SESSION["ajoutTabMin"][$i*2+1]==1){//On attribue une class en fonction de la présence d'erreur(s) ou non
                                                    $affi.= 'has-success';
                                                }
                                                elseif(array_key_exists("ajoutTabMin",$_SESSION)&&$_SESSION["ajoutTabMin"][$i*2+1]==0){//On attribue une class en fonction de la présence d'erreur(s) ou non
                                                    $affi.= 'has-error';
                                                }
                                                $affi.='"><div class="input-group-addon">Taille fixe/min :</div>';
                                                if(array_key_exists("ajoutTab",$_SESSION)){
                                                    $affi.= '<input type="text" class="form-control" id="inputmin_'.$i.'" name="min_'.$i.'" value='.$_SESSION["ajoutTabMin"][$i*2].'>';
                                                }
                                                else{
                                                    $affi.= '<input type="text" class="form-control" id="inputmin_'.$i.'" name="min_'.$i.'">';
                                                }
                                                $affi.='<div class="input-group-addon">bit(s)</div></div></td><td class="col-xs-4 col-md-4"><div class="col-xs-12 col-md-12 input-group ';
                                                if(array_key_exists("ajoutTabMax",$_SESSION)&&$_SESSION["ajoutTabMax"][$i*2+1]==1){//On attribue une class en fonction de la présence d'erreur(s) ou non
                                                    $affi.= 'has-success';
                                                }
                                                elseif(array_key_exists("ajoutTabMax",$_SESSION)&&$_SESSION["ajoutTabMax"][$i*2+1]==0){//On attribue une class en fonction de la présence d'erreur(s) ou non
                                                    $affi.="has-error";
                                                }
                                                $affi.='"><div class="input-group-addon">Taille max :</div>';
                                                if(array_key_exists("ajoutTab",$_SESSION)){
                                                    $affi.= '<input type="text" class="form-control" id="inputmax_'.$i.'" name="max_'.$i.'" value='.$_SESSION["ajoutTabMax"][$i*2].'>';
                                                }
                                                else{
                                                    $affi.= '<input type="text" class="form-control" id="inputmax_'.$i.'" name="max_'.$i.'">';
                                                }
                                                $affi.='<div class="input-group-addon">bit(s)</div></div></td></tr>';
                                            }
                                            echo $affi;
                                        }?>
                                    </tbody>
                                </table>
                            </div>
                            <div id="nbChamp">
                                <input type="hidden"  name="nbChamp" value="1"/><!-- Champ caché permettant de connaître le nombre de champs lors de la validation du formulaire-->
                            </div>
                            <div class="form-group">
                                <input type="button" class="btn btn-info" id="valider_champ" value="Ajouter un champ"/><!-- Bouton permettant l'ajout de champs -->
                                <input type="button" class="btn btn-danger" id="suppr_champ" value="Supprimer un champ" /><!-- Bouton permettant la suppression de champs -->
                                <input type="submit" class="btn btn-success" id="valider" value="Valider"/><!-- Bouton permettant la validation du formulaire -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Dans la base:</h3>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">
                                Type
                            </th>
                            <th class="text-center">
                                Nom des champs
                            </th>
                            <th class="text-center">
                                Taille fixe/taille min
                            </th>
                            <th class="text-center">
                                Taille max
                            </th>
                            <th class="text-center">
                                Supprimer
                            </th>
                        </tr>
                    </thead>
                    <tbody id="in_base">
                    <?php
                        try{
                            $req = $bdd->prepare('SELECT * from trame');//Requête préparée: afficher toutes les données présentes dans la table trame
                        	$req->execute();//exécution de la requête
                			while($rep = $req->fetch(PDO::FETCH_ASSOC)){
                			    echo '<tr><td class="text-center"><h4><strong>'.$rep['Nom'].'</strong></h4></td><td>'.$rep['Tab'].'</td><td>'.$rep['tailleMinFixe'].'</td><td>'.$rep['tailleMax'].'</td><td><form method="post" action="delete_trame.php"><input type="hidden" name="nom" value="'.$rep['Nom'].'" /><input type="submit" class="btn btn-danger" name="'.$rep['Nom'].'" value="Supprimer"/></form></td></tr>';
                			}
                        }
                        catch(PDOException $e)
                        {
                            //On termine le script
                            die('<p> Erreur['.$e->getCode.'] : '.$e->getMessage().'</p>');
                        }  
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div><!-- fin du corps -->
<!-- CORPS -->
<?php
    include("../ressources/footer.php");//Inclusion du footer et des différents scripts nécéssaires au fonctionnement du site.
	unset($_SESSION['success']);
	unset($_SESSION['errors']);
	unset($_SESSION['type']);
	unset($_SESSION['ajoutTab']);
	unset($_SESSION['ajoutTabMin']);
	unset($_SESSION['ajoutTabMax']);
?>
<script>
    jQuery( document ).ready(function(){
        var i=<?php echo $_SESSION['nbChamp']; ?>;
        $("#valider_champ").click(function( event ) {// lors du clique sur le bouton ajouter un champ, on ajoute a tbody une ligne avec les différents champs, et on incrémente 1 au nombre de champs
            event.preventDefault();
            $("#tbody").append("<tr id='champ_"+i+"' class='form-inline form-group'><td class='col-xs-4 col-md-4'><div class='col-xs-12 col-md-12 input-group'><div class='input-group-addon'>Nom :</div><input type='text' class='form-control' id='inputname_"+i+"' name='name_"+i+"'></div></td><td class='col-xs-4 col-md-4'><div class='col-xs-12 col-md-12 input-group'><div class='input-group-addon'>Taille fixe/min :</div><input type='text' class='form-control' id='inputmin_"+i+"' name='min_"+i+"'><div class='input-group-addon'>bit(s)</div></div></td><td class='col-xs-4 col-md-4'><div class='col-xs-12 col-md-12 input-group'><div class='input-group-addon'>Taille max :</div><input type='text' class='form-control' id='inputmax_"+i+"' name='max_"+i+"'><div class='input-group-addon'>bit(s)</div></div></td></tr>");
            i++;
            $("#nbChamp").children().remove();
            $("#nbChamp").append("<input name='nbChamp' type='hidden' value="+i+" />");
        });
        $("#suppr_champ").click(function( event ) {// lors du clique sur le bouton supprimer un champ, on supprime a tbody une ligne avec les différents champs, et on décrémente 1 au nombre de champs
            event.preventDefault();
            if(i-1>0){
                var name= 'champ_'+(i-1);
                $('#'+name).remove();
                i--;
                $("#nbChamp").children().remove();
                $("#nbChamp").append("<input name='nbChamp' type='hidden' value="+i+" />");
            }
        });
    });
</script>
<?php 
	unset($_SESSION['nbChamp']);//Suppression du nombre de champs après l'affichage du résultat de la validation du formulaire
?>
