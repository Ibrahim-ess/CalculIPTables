<?php
    function afficheResultat($valeur,$reponse,$sens,$tab,$user,$title,$bdd){
        if(preg_match('#^\d+$#',$valeur)){
            if(array_key_exists($sens,$tab)){
                $val_depart;
                $val_conv;
                if($tab[$sens]['function_1']==null){
                    $val_depart=$valeur;
                    $val_conv=$tab[$sens]['function_2']($valeur);
                }else if($tab[$sens]['function_2']==null){
                    $val_depart=$tab[$sens]['function_1']($valeur);
                    $val_conv=$valeur;
                }else{
                    $val_depart=$tab[$sens]['function_1']($valeur);;
                    $val_conv=$tab[$sens]['function_2']($valeur);
                }
                
                if(strtolower($reponse)==$val_conv){
                    echo'<div class="alert alert-success alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
                    echo 'Réponse correcte !<br>';
					if(isset($user))
                    exerciceFait($user,$title,1,$bdd);
                    
                }else{
                    echo'<div class="alert alert-danger alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
                    echo 'Erreur !<br>';
					if(isset($user))
                    exerciceFait($user,$title,-1,$bdd);
                }
                echo '<var>('.$val_depart.')<sub>'.$tab[$sens]['sub_1'].'</sub></var> = <var>('.$val_conv.')<sub>'.$tab[$sens]['sub_2'].'</sub> ';
                echo '</strong></div>';
            }
        }
    }

?>
