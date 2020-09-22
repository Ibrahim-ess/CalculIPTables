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
    
    function trouverClasse($title,$bdd){
        ?>
        <h1>Classe de l'IP <small>Trouver la classe correspondante</small></h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                    <h3 class="panel-title">Exercice</h3>
            </div>
            <div class="panel-body">
                <div class="lead bg-info well">
                    <?php // Ces conditions permette de vérifier si l'exercice à déjà et fait ou non, pour eviter de renvoyer un champ déjà rempli
                        if(!empty($_POST['submit']) && !empty($_POST['ip']) && preg_match('#^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$#', $_POST['ip'], $ip)){ // Sécurité et pregmatch IP
                            echo '<p>De quelle classe est cette adresse IP : <strong>'.$ip[0].'</strong> ?</p>';
                            $o1=$ip[1];
                            $o2=$ip[2];
                            $o3=$ip[3];
                            $o4=$ip[4];
                        }
                        else{
                            // Rand des octets pour l'IP entre 0 et 260 (et pas 255, pour verifier les fautes d'inattention)
                            $o1=rand(1,260);
                            $o2=rand(0,260);
                            $o3=rand(0,260);
                            $o4=rand(0,260);
                            echo "<p>De quelle classe est cette adresse IP : <strong>$o1.$o2.$o3.$o4</strong> ?</p>";
                        }
                    ?>
                </div>
                <?php
                    if(!empty($_POST['submit']) && !empty($_POST['classe']) && !empty($_POST['ip']) && preg_match('#^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$#', $_POST['ip'], $ip))
                    {
                        if($_POST['classe']==($classe=test_classe($ip))){ // Si la classe coché correspond à l'IP on affiche un message de réussite
                            if($classe=='None') { // Le message de réussite est différent selon le cas ou l'IP est conforme ou pas
                                echo '<div class="alert alert-success text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Félicitations, tu as réussi, l\'adresse IP est bien <strong>impossible</strong><div>';
                                if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,1,$bdd);
                            }
                            else{
                                echo '<div class="alert alert-success text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Félicitations, tu as réussi, l\'adresse IP est bien de classe <strong>'.$classe.'</strong></div>';
                                if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,1,$bdd);
                            }
                        }
                        else{ // Si la classe coché ne correspond pas à l'IP affiché
                            if($classe=='None'){
                                echo '<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Tu as fait une erreur, l\'adresse IP est <strong>impossible</strong> car un des octets est supérieur à 255</div>';
                                if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
                            }
                            else{
                                echo '<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Tu as fait une erreur, l\'adresse IP est de classe <strong>'.$classe.'</strong></div>';
                                if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
                            }
                        }
                    }
                ?>
                <div>
                    <form class="" method="post">
                        <?php
                            if(empty($_POST['classe']) || !empty($_POST['retry'])){
                        ?>
                        <!-- Le formulaire bootstrap -->
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3 col-xs-12">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <label class="radio-inline">
                                            <input type="radio" name="classe" value="A" />A
                                        </label>
                                    </span>
                                    <span class="input-group-addon">
                                        <label class="radio-inline">
                                            <input type="radio" name="classe" value="B" />B
                                        </label>
                                    </span>
                                    <span class="input-group-addon">
                                        <label class="radio-inline">
                                            <input type="radio" name="classe" value="C" />C
                                        </label>
                                    </span>
                                    <span class="input-group-addon">
                                        <label class="radio-inline">
                                            <input type="radio" name="classe" value="D" />D
                                        </label>
                                    </span>
                                    <span class="input-group-addon">
                                        <label class="radio-inline">
                                            <input type="radio" name="classe" value="E" />E
                                        </label>
                                    </span>
                                    <span class="input-group-addon">
                                        <label class="radio-inline">
                                            <input type="radio" name="classe" value="None" />Impossible
                                        </label>
                                    </span>
                                    <input type="hidden" name="ip" value="<?php echo $o1.".".$o2.".".$o3.".".$o4 ; ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row  form-group"></div>
                        <div class="row">
                            <div class="col-md-6 col-md-offset-3">
                                <input type="submit" name="submit" value="Valider" class="btn btn-success col-xs-12" />
                            </div>
                        </div>
                        <?php
                            }
                        ?>
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                <input type="submit" name="retry" value="Nouvelle IP" class="btn btn-warning col-xs-12" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
?>
