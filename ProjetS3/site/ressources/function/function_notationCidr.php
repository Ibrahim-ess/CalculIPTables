<?php
    function notationCidr($title,$bdd,$facile=false){
	if ($facile) 
        	echo '<h1>Notation CIDR (niveau S2)</h1>' ;
 	else 
		echo '<h1>Notation CIDR (niveau S3)</h1>' ;
?>

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Exercice</h3>
            </div>
            <div class="panel-body">
                <div class="lead bg-info well">
                    <?php
                        $CIDR=0;
                        if(!empty($_POST["submit"]) && isset($_POST["IP"]) && preg_match('#^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/([0-9]*)$#', $_POST["IP"], $ip)){
                            $IP=array('o1'=>$ip[1],'o2'=>$ip[2],'o3'=>$ip[3],'o4'=>$ip[4]);
                            $CIDR=$ip[5];
                        }
                        else{
                            $IP=array('o1'=>rand(1,223),'o2'=>rand(0,255),'o3'=>rand(0,255),'o4'=>rand(1,254));
			    if ($facile) 
				$CIDR= rand(1,3)*8;
			    else
			   	$CIDR= rand(3,31);
                        }
                        $octet=0;
                        $masque_correction=0;
                        correcMasque($masque_correction, $octet, $CIDR);
                        // echo $ch= $IP; 
                        echo 'Adresse IP : <b>'. $IP['o1'].'.'.$IP['o2'].'.'.$IP['o3'].'.'.$IP['o4'].' /'.$CIDR.'</b><br/>';
                        $ch= implode('.',$IP);
                        $ch= $ch.'/'.$CIDR;
                    ?>
                </div>
                <div class="col-md-12">
                    <?php
                        $recipient= correcReseauAdr($octet, $IP, $CIDR);
                        $mCH= implode('.',$masque_correction);
                        $reussi=0;
                        $exoReussi=false;
                    ?>
                    <form class="" action="" method="post">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12 col-md-offset-3 col-md-6">
                                    <label for="nbBits" class="control-label">Sur combien de bits la partie réseau est-elle codée ?</label>
                                    <?php
                                        if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])){
                                            if(!empty($_POST['nbBits'])){
                                                if($_POST['nbBits'] == $_POST['cidr']){
                                                    echo "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>"."Correct ! ".$_POST['cidr']." est bien la réponse!"."</strong></div>";
                                                    $reussi+=1;
                                                }
                                                else{
                                                    echo "<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Faux ! La partie réseau est codée sur <strong>".$_POST['cidr']."bits.</strong></div>";
                                                }
                                            }
                                            else{
                                                echo "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Il faut donner une réponse à la question <b>Sur combien de bits la partie réseau est-elle codée ?</b></div>";
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-offset-3 col-md-6">
                                    <div class="input-group col-md-12 col-xs-12 <?php if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])): if(!empty($_POST['nbBits'])): if($_POST['nbBits'] == $_POST['cidr']): echo 'has-success'; else: echo 'has-error';endif; else: echo 'has-warning'; endif;endif;?>">
                                        <input type="text" class="form-control " id="nbBits" name="nbBits" placeholder="0" value="<?php if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])): echo $_POST['nbBits']; endif;?>">
                                        <span class="glyphicon glyphicon-<?php if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])): if(!empty($_POST['nbBits'])): if($_POST['nbBits'] == $_POST['cidr']): echo 'ok'; else: echo 'remove';endif; else: echo 'warning-sign'; endif;endif;?> form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12 col-md-offset-3 col-md-6">
                                    <label for="masque" class="control-label">Quelle est alors la valeur du masque en décimal ?</label>
                                    <?php
                                        if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])){
                                            if(!empty($_POST['masque'])){
                                                if($_POST['masque'] == $_POST['masqueCorre']){
                                                    echo "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>"."Correct ! ".$_POST['masqueCorre']." est bien le masque! "."</strong></div>";
                                                    $reussi+=1;
                                                }
                                                else{
                                                    echo "<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Faux ! La valeur du masque est <strong>".$_POST['masqueCorre']."</strong></div>";
                                                }
                                            }
                                            else{
                                                echo "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Il faut donner une réponse à la question <b>Quel est alors la valeur du masque ?</b></div>";
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-offset-3 col-md-6">
                                    <div class="input-group col-md-12 col-xs-12 <?php if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])):if(!empty($_POST['masque'])): if($_POST['masque'] == $_POST['masqueCorre']): echo 'has-success'; else: echo 'has-error';endif; else: echo 'has-warning'; endif;endif;?>">
                                        <input type="text" class="form-control " id="masque" name="masque" placeholder="0.0.0.0" value="<?php if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])): echo $_POST['masque']; endif;?>">
                                        <span class="glyphicon glyphicon-<?php if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])):if(!empty($_POST['masque'])): if($_POST['masque'] == $_POST['masqueCorre']): echo 'ok'; else: echo 'remove';endif; else: echo 'warning-sign'; endif;endif;?> form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-xs-12 col-md-offset-3 col-md-6">
                                    <label for="adrReseau" class="">Quelle est l'adresse réseau de <?php echo $IP['o1'].'.'.$IP['o2'].'.'.$IP['o3'].'.'.$IP['o4'].'/'.$CIDR;?> ?</label>
                                    <?php
                                        if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])){
                                            if(!empty($_POST['adrReseau'])){
                                                if($_POST['adrReseau'] == $_POST['reseauCorre']){
                                                    echo "<div class='alert alert-success alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Correct ! L'adresse réseau est bien <strong>".$_POST['reseauCorre']."</strong></div>";
                                                    $reussi+=1;
                                                }
                                                else{
                                                    echo "<div class='alert alert-danger alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Faux ! L'adresse réseau est <strong>".$_POST['reseauCorre']."</strong></div>";
                                                }
                                            }else{
                                                echo "<div class='alert alert-warning alert-dismissible' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>Il faut donner une réponse à la question <b>En appliquant ce masque, quelle est l'adresse réseau de ".$_POST['IP']." ?</b></div>";
                                            }
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-md-offset-3 col-md-6">
                                    <div class="input-group col-md-12 col-xs-12 <?php if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])):if(!empty($_POST['adrReseau'])): if($_POST['adrReseau'] == $_POST['reseauCorre']): echo 'has-success'; else: echo 'has-error';endif; else: echo 'has-warning'; endif;endif;?>">
                                    <input type="text" class="form-control" id="adrReseau" name="adrReseau" placeholder="0.0.0.0" value="<?php if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])): echo $_POST['adrReseau']; endif;?>">
                                        <span class="glyphicon glyphicon-<?php if(!empty($_POST['submit']) && isset($_POST['reseauCorre']) && isset($_POST['masqueCorre']) && isset($_POST['cidr'])):if(!empty($_POST['adrReseau'])): if($_POST['adrReseau'] == $_POST['reseauCorre']): echo 'ok'; else: echo 'remove';endif; else: echo 'warning-sign'; endif;endif;?> form-control-feedback" aria-hidden="true"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                            if(!empty($_POST['submit'])){
                                if($reussi==3)
                                {
                                    $exoReussi=true;
									if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,1,$bdd);
                                }else{
                                    if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
                                }
                            }
                        ?>
                        <input type="hidden" name="cidr" value="<?php echo $CIDR; ?>"/>
                        <input type="hidden" name="reseauCorre" value="<?php echo $recipient; ?>"/>
                        <input type="hidden" name="masqueCorre" value="<?php echo $mCH; ?>"/>
                        <input type="hidden" name="IP" value="<?php echo $ch; ?>"/>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-offset-4 col-xs-12 col-md-4">
                                    <?php if(empty($_POST['submit'])): ?>
                                    <input type="submit" name="submit" class="btn btn-success col-xs-12" name=""/>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                    <div class="col-md-offset-4 col-xs-12 col-md-4">
                                    <input type="submit" name="retry" value="Nouvelle IP" class="btn btn-warning col-xs-12" />
                                </div>
                                <div class="row  form-group"></div>
                            </div>
                        </div>
                        
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
?>
