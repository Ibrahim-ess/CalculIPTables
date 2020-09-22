<?php
    function test_classe($ip){ // Test une adresse IP (en entrée) pour retourner sa classe
        if ($ip[1]<=255 && $ip[1]>0 && $ip[2]<=255 && $ip[2]>=0 && $ip[3]<=255 && $ip[3]>=0 && $ip[4]<=255 && $ip[4]>=0 ){
            if ($ip[1]>0 && $ip[1]<=127){
                return 'A';
            }
            elseif ($ip[1]>=128 && $ip[1]<=191){
                return 'B';
            }
            elseif ($ip[1]>=192 && $ip[1]<=223){
                return 'C';
            }
            elseif ($ip[1]>=224 && $ip[1]<=239){
                return 'D';
            }
            elseif ($ip[1]>=240 && $ip[1]<=255){
                return 'E';
            }
        }
        else {
            return 'None'; // Si l'adresse IP n'appartient à aucune classe, on retourne none
        }
    }

    function trouverClasseInverse($title,$bdd){ // On a une classe d'adresse IP et on doit écrire une bonne adresse IP correspondante
        ?>
        <h1>Classe IP <small>Trouver une adresse IP correspondante</small></h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Exercice</h3>
            </div>
            <div class="panel-body">
                <div class="lead bg-info well">
                    <?php // Ces conditions permette de vérifier si l'exercice à déjà et fait ou non, pour eviter de renvoyer un champ déjà rempli
                         if(!empty($_POST['submit']) && isset($_POST['classe'])){
                            $lettre=htmlspecialchars($_POST['classe']);
                            echo 'Entrer une adresse IP de classe <strong>'.$lettre.'</strong>';
                        }
                        else{
                            $lettre = chr(rand(65,68));
                            echo 'Entrer une adresse IP de classe <strong>'.$lettre.'</strong>';
                        }
                    ?>
                </div>
                <?php
                    if (isset($reponse)) {
			$reponse;
                    	$message_reponse;
			}
                    if(empty($_POST['retry'])&& !empty($_POST['ip']) && !empty($_POST['submit'])){ // Sécurité
                        if(preg_match('#^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$#', $_POST['ip'], $ip)){ // Sécurité avec un pregmatch pour l'IP
                            if(test_classe($ip) =='None'){ // On vérifie si l'ip n'est pas conforme
                                echo '<div class="form-group has-error has-feedback">';
                                $reponse='remove';
                                $message_reponse= '<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Faux, '.$ip[0].' n\'est pas une adresse IP, car un des octets dépasse 255 ou est inférieur à 0</div>'; // Dans ce cas on rappelle l'erreur au client
                                if(isset($_SESSION['connect'])) if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
                                
                            }else if($_POST['classe']!=test_classe($ip)){ // On vérifie si l'ip ne correspond pas à la classe attendu
                                echo '<div class="form-group has-warning has-feedback">';
                                $reponse='warning-sign';
                                $message_reponse= '<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Faux, l\'adresse IP '.$ip[0].' est de la classe <strong>'.test_classe($ip).'</strong> et non pas de la classe <strong>'.$_POST['classe'].'</strong></div>';
                                if(isset($_SESSION['connect'])) if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
                                
                            }else{ // On vérifie si l'ip correspond à la classe attendu
                                $reponse='ok';
                                echo '<div class="form-group has-success has-feedback">';
                                $message_reponse='<div class="alert alert-success text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Félicitations, l\'adresse IP '.$ip[0].' est bien de la classe <strong>'.$_POST['classe'].'</strong></div>';
                                if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,1,$bdd);
                            }
                        }
                        else{ // Si le pregmatch n'est pas conforme (donc pas une adresse IP)
                            $reponse='remove';
                            echo '<div class="form-group has-error has-feedback">';
                            $message_reponse= '<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Faux, <strong>'.htmlspecialchars($_POST['ip']).'</strong> n\'est pas une adresse IP valide</div>';
                            if(isset($_SESSION['connect'])) if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
                        }
                    }
		if (isset($message_reponse)) {
                    echo $message_reponse;
		}
                ?>
                <form method="post" class="">
                    <div class="row">
                        <div class="form-group col-md-4 col-md-offset-4 col-xs-12">
                            <div class="input-group col-xs-12">
                                <label class="control-label sr-only" for="inputIP">Adresse IP</label>
                                <span class="input-group-addon">IP</span>
                                <input type="text" name="ip" class="form-control " id="inputIP" aria-describedby="inputIPStatus" placeholder="0.0.0.0" value="<?php if(empty($_POST['retry'])&& !empty($_POST['ip']) && !empty($_POST['submit'])): echo $_POST['ip']; endif;?>"> <!-- Zone de texte pour l'IP -->
                                <?php
				if (isset($reponse)) {
                                    echo '
                                        <span class="glyphicon glyphicon-'.$reponse.' form-control-feedback" aria-hidden="true"></span>
                                        <span id="inputWarning2Status" class="sr-only">('.$reponse.')</span>
                                    ';
				}
                                ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="classe" value="<?php echo $lettre ; ?>" />
                        <?php
                            if(empty($_POST['ip']) || !empty($_POST['retry'])){
                        ?>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <input type="submit" name="submit" value="Valider" class="btn btn-success col-xs-12" /> <!-- Affiche l'icon "sucess" en cas de bonne réponse -->
                        </div>
                    </div>
                        <?php
                            }
                        ?>
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <input type="submit" name="retry" value="Nouvelle Classe" class="btn btn-warning col-xs-12" /> <!-- Affiche l'icon "retry" en cas de mauvaise réponse -->
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
?>
