<?php
    $title="Conversions : Binaire - Hexadécimal - Décimal";
    include("../ressources/header.php");
    //include_once('../ressources/connectBDD.php');
    include_once('../ressources/function/fonction_utilisateur.php');
    include("../ressources/function/function_conversion.php");
    /*$connectuser=-1;
    if(isset($_SESSION['connect']))
	$connectuser=$_SESSION['connect'];*/
?>
<div class="container">
    <h1>Conversions <small>binaire, hexadécimal et décimal</small></h1>
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title">Exercice</h3>
        </div>
        <div class="panel-body">
                <?php
		$conversions = array(
'hexa'=> array('descr'=>'hexadécimal','base'=>16,'prefixe'=>'0x'),
'deci'=> array('descr'=>'décimal','base'=>10,'prefixe'=>''),
'binaire'=> array('descr'=>'binaire','base'=>2,'prefixe'=>'0b'));
                $tab=array(
                    'hexa_binaire'=>array('function_1'=>"dechex",'sub_1'=>16,'function_2'=>"decbin",'sub_2'=>2),
                    'binaire_hexa'=>array('function_1'=>"decbin",'sub_1'=>2,'function_2'=>"dechex",'sub_2'=>16),
                    'deci_binaire'=>array('function_1'=>null,'sub_1'=>10,'function_2'=>"decbin",'sub_2'=>2),
                    'binaire_deci'=>array('function_1'=>"decbin",'sub_1'=>2,'function_2'=>null,'sub_2'=>10),
                    'hexa_deci'=>array('function_1'=>"dechex",'sub_1'=>16,'function_2'=>null,'sub_2'=>10),
                    'deci_hexa'=>array('function_1'=>null,'sub_1'=>10,'function_2'=>"dechex",'sub_2'=>16)
                    );
                if(isset($_POST['submit'])){
                        if(isset($_POST['reponse']) and trim($_POST['reponse'])!='' and isset($_POST['valeur']) and trim($_POST['valeur'])!='' and isset($_POST['choix_conv_1']) and trim($_POST['choix_conv_1'])!='' and isset($_POST['choix_conv_2']) and trim($_POST['choix_conv_2'])!=''){
                            $rep=false;
                            afficheResultat($_POST['valeur'],$_POST['reponse'],$_POST['choix_conv_1']."_".$_POST['choix_conv_2'],$tab,$connectuser,$title,$bdd);
                        } else {
                            echo'<div class="alert alert-warning alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
                            echo 'Il faut remplir les champs vides !';
                            echo '</strong></div>';
                        }
                } // submit ou pas
                if(isset($_POST['choix'])){
                    if(isset($_POST['choix_conver_1']) and trim($_POST['choix_conver_1'])!='' && isset($_POST['choix_conver_2']) and trim($_POST['choix_conver_2'])!=''){
                        if($_POST['choix_conver_1']==$_POST['choix_conver_2']){
                            echo '<div class="alert alert-danger alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
                            echo "Cette conversion n'est pas autorisée !";
                            echo '</strong></div>';
                        }
                    }
                }
// valeurs par défaut
		$choix_conver_1='hexa';$choix_conver_2='deci';
// on essaie de récupérer l'info précédente sur les choix
		if (isset($_POST['choix_conver_1']) 
		  && array_key_exists($_POST['choix_conver_1'],$conversions)) {
			$choix_conver_1=$_POST['choix_conver_1'];
		} else if (isset($_POST['choix_conv_1']) 
		  && array_key_exists($_POST['choix_conv_1'],$conversions)) {
			$choix_conver_1=$_POST['choix_conv_1'];
		}
		if (isset($_POST['choix_conver_2']) 
        	  && array_key_exists($_POST['choix_conver_2'],$conversions)) {
			$choix_conver_2=$_POST['choix_conver_2'];
		} else if (isset($_POST['choix_conv_2'])
		  && array_key_exists($_POST['choix_conv_2'],$conversions)) {
                        $choix_conver_2=$_POST['choix_conv_2'];
                }

                ?>

            <form action="" method="post">
                <div class="form-group">
                    <label for="choix_conver">Choisir la conversion</label>
                    <div class="input-group">
                        <select name="choix_conver_1" class="form-control">
<?php foreach ($conversions as $conv=>$val) : ?>
<?php if ($conv == $choix_conver_1) :?>
			<option value="<?=$conv?>" selected><?=$val['descr']?></option>
<?php else : ?>
                        <option value="<?=$conv?>"><?=$val['descr']?></option>
<?php endif ?>
<?php endforeach ?>
                        </select>
                        <span class="input-group-addon"> en </span>
                        <select name="choix_conver_2" class="form-control">
<?php foreach ($conversions as $conv=>$val) : ?>
<?php if ($conv == $choix_conver_2) :?>
			<option value="<?=$conv?>" selected><?=$val['descr']?></option>
<?php else : ?>
                        <option value="<?=$conv?>"><?=$val['descr']?></option>
<?php endif ?>
<?php endforeach ?>

                        </select>
                        <div class="input-group-btn">
                            <button type="submit" name="choix" class="btn btn-info">Envoyer</button>
                        </div>
                    </div>
                </div>
            </form>
            <?php
                if(isset($_POST['choix'])){
                    if(isset($_POST['choix_conver_1']) and trim($_POST['choix_conver_1'])!='' && isset($_POST['choix_conver_2']) and trim($_POST['choix_conver_2'])!=''){
                        $valeur=rand(17,253); 
			$affiche_1=$conversions[$choix_conver_1]['prefixe'].base_convert($valeur,10,$conversions[$choix_conver_1]['base']);
			$affiche_2=$conversions[$choix_conver_2]['prefixe'].base_convert($valeur,10,$conversions[$choix_conver_2]['base']);

	    ?>
	<form action="" method="post">
           <div class="form-group"><div class="row"><div class="col-sm-8 col-sm-offset-2">
		<div class="input-group input-group-sm">
		<span class="input-group-addon"> <?=$conversions[$choix_conver_1]['descr']?></span>
		<span class="input-group-addon sr-only"><label for="conv">
			<?=$affiche_1?></label></span>
		<input type="text" id="conv" class="form-control " 
placeholder="<?=$affiche_1?>" aria-label="<?=$affiche_1?>" disabled>
		<span class="input-group-addon">=</span>
                                    <span class="input-group-addon sr-only"><label for="conv">en <?=$conversions[$choix_conver_2]['descr']?></label></span>
		<input type="text" name="reponse" class="form-control" 
placeholder="en <?=$conversions[$choix_conver_2]['descr']?>" 
aria-label="en <?=$conversions[$choix_conver_2]['descr']?>">
		<input type="text" name="valeur" value="<?=$valeur?>" hidden />
                <input type="text" name="choix_conv_1" value="<?=$choix_conver_1?>" hidden />
                <input type="text" name="choix_conv_2" value="<?=$choix_conver_2?>" hidden />
		<div class="input-group-btn"><button type="submit" name="submit" value="submit" class="btn btn-success">Valider</button></div>
		</div>
	   </div></div></div>
	</form>
<?php
			}
		}?>
</div></div></div>
<?php
    include("../ressources/footer.php");
?>
