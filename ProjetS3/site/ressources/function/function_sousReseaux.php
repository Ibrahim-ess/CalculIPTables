<?php

require_once("fonctionsReseaux.php");

function icon_reponse($resp, $corr) {
  echo '<span class="glyphicon ';
  if ($resp=='has-error')
	echo 'glyphicon-remove';
  else 
	echo 'glyphicon-ok';
  echo ' form-control-feedback" aria-hidden="true">' ;
  echo '</span>';
  if ($resp=='has-error') {
	echo '<div class="alert alert-danger alert-dismissible" role="alert">';
	echo '<button class="close" type="button" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>';
	echo "Faux ! <strong>$corr</strong></div>";
  }
}

function verifs(&$res,&$message_reponse, &$form_class, &$erreurs, &$resCorr,$title,$bdd) {

//Données du pb
  $numReseauLong= $_POST['numReseauLong'];
  $masqCidrDep= $_POST['masqCidrDep'];
  $nbSousRes= $_POST['nbSousRes'];

// Réponses données par l'utilisateur
  $masqueDeci = $_POST['masqueDeci'];
  $masqueCIDR = $_POST['masqueCIDR'];
  

// calcul correction
  $numReseau = long2ip($numReseauLong);
  $nbBitsAig = nbBitsNecessaires($nbSousRes);
  $masqueCIDRCorr = $masqCidrDep+ $nbBitsAig;
  $resCorr['masqueCIDR']= $masqueCIDRCorr;
  $masqDec = masque($masqueCIDRCorr);
  $masqueDeciCorr = long2ip($masqDec);
  $resCorr['masqueDeci'] = $masqueDeciCorr;
  $deltaSousReseaux = 1<<(32-$masqueCIDRCorr);
  $resCorr[0][0]= $numReseauLong;
  $resCorr[0][1]= $numReseauLong+$deltaSousReseaux-1;
  for ($i=1; $i<=2;$i++) {
	$resCorr[$i][0] = $resCorr[$i -1][0] + $deltaSousReseaux;
	$resCorr[$i][1] = $resCorr[$i -1][1] + $deltaSousReseaux;
  }
  $resCorr[3][0] = $numReseauLong  + ($nbSousRes -1)*$deltaSousReseaux;
  $resCorr[3][1] = $resCorr[3][0] + $deltaSousReseaux -1;
  

  $erreurs =array(); $form_class= array();
  if ( $masqueDeciCorr != $masqueDeci ) {
	$erreurs[]='masqueDeci';
	$form_class['masqueDeci']='has-error';
  } else {
	$form_class['masqueDeci']='has-success';
  }
  if ( $masqueCIDRCorr != $masqueCIDR && $masqueCIDR != '/'.$masqueCIDRCorr) {
	$erreurs[]='masqueCIDR';
	$form_class['masqueCIDR']='has-error';
  } else {
	$form_class['masqueCIDR']='has-success';
  } 
  for ($i=0;$i<=3;$i++) {
      if ( $resCorr[$i][0] != ip2long($res[$i][0]) ) {
	$erreurs[] = 'resres'.$i;
	$form_class[$i][0] = 'has-error';
      } else {
	 $form_class[$i][0] = 'has-success';
      }
	
      if ( $resCorr[$i][1] != ip2long($res[$i][1])) {
	$erreurs[] = 'resdiff'.$i;
	$form_class[$i][1] = 'has-error';
      } else {
	$form_class[$i][1] = 'has-success';
      }
  }

  if (count($erreurs) ==0) {
  	$message_reponse='<div class="alert alert-success text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Félicitations, toutes les valeurs sont correctes !</div>';
	 if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,1,$bdd);
        return true;
  } else {
	$message_reponse='<div class="alert alert-danger text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Vous avez au moins une erreur.</div>';
	 if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
	return false;
  }
}

function sousReseaux($title,$bdd){
?>
<h1>Calcul de sous-réseaux (S3)</h1>
   <!--<div class="panel panel-default">
       <div class="panel-heading">
           <h3 class="panel-title">Exercice</h3>
       </div>
       <div class="panel-body">-->

<?php
    if(!isset($_POST['submit']) || $_POST['submit'] == 'retry' ){
	$adresse = aleaAdresseIP();
	$masqCidrDep = mt_rand(3,23);
	$nbSousRes = mt_rand(5,31-$masqCidrDep);
	//$adresse = ip2long('128.0.0.0');
	//$masqCidrDep = 8;
 	//$nbSousRes = 17;
	$numReseauLong = numeroReseau($adresse,$masqCidrDep);
	$message_reponse='';
	$showIcons=false;
	$res=array_fill(0,4,array_fill(0,2,''));
	$form_class=$res;
	$form_class['masqueDeci']='';
	$form_class['masqueCIDR']='';
    } else {
  	$numReseauLong= $_POST['numReseauLong'];
  	$masqCidrDep= $_POST['masqCidrDep'];
	$nbSousRes = $_POST['nbSousRes'];
	$res= $_POST['res'];
	$resu=verifs($res,$message_reponse,$form_class, $erreurs,$resCorr,$title,$bdd);
	$showIcons=true;
    }
    $numReseau = long2ip($numReseauLong);
    echo '<div class="lead bg-info well"><p>' ;
    echo "Soit le réseau <strong>$numReseau/$masqCidrDep</strong>
 à diviser en <strong>$nbSousRes sous-réseaux</strong> de même taille. 
Donnez le masque des sous-réseaux en notation décimale et CIDR
et pour les trois premiers réseaux et le dernier,
l'adresse du réseau ainsi que son adresse de diffusion.";
    echo '</p></div>';
    echo $message_reponse;
?>

<form class="form-horizontal" action="" method="post">
<?php
   if (!isset($_POST['submit']) || $_POST['submit'] == 'retry' ){
	$_POST['masqueDeci']='';
	$_POST['masqueCIDR']='';
?>
   <input type='hidden' name='numReseauLong' value='<?=$numReseauLong?>' />
   <input type='hidden' name='masqCidrDep' value='<?=$masqCidrDep?>' />
   <input type='hidden' name='nbSousRes' value='<?=$nbSousRes?>' />
<?php } ?>

<div class="form-group <?=$form_class['masqueDeci']?>">

	<label class="control-label col-md-3" for="masqueDeci">Masque décimal des sous-rés. :
	</label>
<div class="input-group">
<input type="text" name="masqueDeci" id="masqueDeci" 
class="form-control" placeholder="0.0.0.0" 
value="<?=$_POST['masqueDeci']?>"
aria-label="Masque decimal"/>
<?php if ($showIcons) icon_reponse($form_class['masqueDeci'], $resCorr['masqueDeci']);?>
</div>
</div>

<div class="form-group <?=$form_class['masqueCIDR']?>">

<label class="control-label col-md-3" for="masqueCIDR">Masque CIDR :</label>
<div class="input-group">
<input type="text" name="masqueCIDR" id="masqueCIDR" 
class="form-control" placeholder="0" 
value="<?=$_POST['masqueCIDR']?>"
aria-label="Masque CIDR"/>
<?php if ($showIcons) icon_reponse($form_class['masqueCIDR'], $resCorr['masqueCIDR']);?>
</div>
</div>

<div class="form-group">
<table class="table table-striped table-responsive">
<thead>
<tr><th class="col-md-1">N° ss-réseau</th><th class="col-md-5">Adr. sous-réseau</th><th class="col-md-5">Adresse Diffusion</th></tr>
</thead>
<tbody>
<?php
	for ($i=0;$i<=3;$i++) {
		$nameres="res[$i][0]";
		$namediff="res[$i][1]";
		if ($i==3) {
			echo '<tr><td>⠇</td><td>⠇</td><td>⠇</td></tr>';
			echo '<tr><td>'.($nbSousRes-1).'</td><td>' ;
			$nomRes='der. réseau';
			$nomDiff='diff. der. réseau';
		} else {
			echo '<tr><td>'.$i.'</td><td>';
			$nomRes="réseau $i";
			$nomDiff="diff. rés. $i";
		}
?>
<div class="input-group col-md-12 <?=$form_class[$i][0]?>">
<input type="text" name="<?=$nameres?>" id="<?=$nameres?>"
class="form-control text-center"
placeholder="0.0.0.0" 
value="<?=$res[$i][0]?>"
aria-label="Adresse <?=$nomRes?>"/>
<?php if ($showIcons) icon_reponse($form_class[$i][0], long2ip($resCorr[$i][0])) ?>
</div>
</td><td>
<div class="input-group col-md-12 <?=$form_class[$i][1]?>">
<input type="text" name="<?=$namediff?>" id="<?=$namediff?>"
class="form-control text-center"
placeholder="0.0.0.0" 
value="<?=$res[$i][1]?>"
aria-label="Adresse <?=$nomDiff?>"/>
<?php 
		if ($showIcons) 
			icon_reponse($form_class[$i][1], long2ip($resCorr[$i][1])) ;
		echo '</div></td></tr>';
	} 
?>

</tbody>
</table>
</div>

<div class="form-group">
     <div class="col-md-4 col-md-offset-2">
<?php if (!isset($_POST['submit'])){ 
           echo '<input type="submit" name="submit" value="Valider" class="btn btn-success col-xs-12" />';
}?>
     </div>
     <div class="col-md-4">
          <input type="submit" name="retry" value="Nouvelles valeurs" class="btn btn-warning col-xs-12" />
      </div>
</div>
</form>
</div>
</div>
<?php } //function sousReseaux
?>
