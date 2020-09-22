 <?php
	function correcReseauAdr($oct,$IP, $CIDR){
        $bit= $CIDR % 8; // nbr de bits à 1 dans l'octet à découper
        $correctionReseauAdr= array();
        $i=0;
        $ch= 0;
        for($i=1;$i<= $oct;$i++){
            $ch='o'.$i;
            $correctionReseauAdr[$ch]= $IP[$ch];
        }
       
        echo '<br/>';
        $ch= 'o'.$i; //c'est l'octet à découper
        $octetDecoupe= $IP[$ch];//on prend l'octet qui est à découper
        $decomposition=array();//on retranscrit l'octet à découper en binaire
        for($j=128;$j>=1;$j=$j/2){
            if(($octetDecoupe - $j >=0) and $octetDecoupe >0){//si le 1er bit de l'octet est à 1 alors la 1ere case du tableau
                //on soustrait chaque puissance de 2 pour tout n de[0,7] à l'octet et on regarde si c'est positif tant que c'est le cas on continue
                $octetDecoupe= $octetDecoupe - $j;
                $decomposition[]=1;
                }
                else{
                    $decomposition[]=0;
                }
        }
       
        $stock=0;
        $_128=128;
        for($j=0;$j< $bit;$j++){
            //pour tout les bits à 1 dans le masque
            if($decomposition[$j]==1){
                //si dans l'octet de l'adr reseau l'octet est à 1 aussi on lui associe la puissance de 2 correspondante
                $stock= $stock+ $_128;
            }
             $_128= $_128/2;
        }
       
        $correctionReseauAdr[$ch]= $stock;
        //maintenant on remplit les autres octects à 0
        if(4-$i >0){
            
            
            for($j= (4-$i); $j>0 ;$j--){
                
                $indice= $i +$j;
                $correctionReseauAdr['o'.$indice]=0;
            }
           
        }
        $c= implode('.',$correctionReseauAdr);
        //echo 'l\'adresse reseau est '. $c;
        return $c;
    }

    function correcMasque(&$masque_correction, &$octet, $CIDR){
        $bit= $CIDR % 8; // nbr de bits à 1 dans l'octet à découper
        $octet= ((int) ($CIDR/8)); //nbr d'octets qui passent tels quel à travers le masque
        $masque_correction= array();
        for($i=0;$i< $octet; $i++){
            $masque_correction[]=255;
        }
        $_128=128;
        $stock=0;
     
        for($j=0;$j<$bit;$j++){
            $stock= $stock+ $_128;//pour chaque bit qui doit être à 1, on additionne les valeurs decimales correspondantes
            $_128= $_128/2;
        }
        $masque_correction[]=$stock;
        if(($octet+1)<4){
            $diff= 4-($octet+1);
            for($i=0;$i<$diff;$i++){
                $masque_correction[]=0;
            }
        }
    }

    function correcMaskSelect($IP, $masque_correction){
        //il faut que $masque_correction= explode('.',$_POST[\'mCH\'])

        $chaine=1;
        $res= array();
        $bool= 1;
        
        $sum= 0;
        $i=1;
        $calcul=0;
        while($i<=4 and $bool == true){
            for($j=128;$j>=1;$j=$j/2){
                $chs= 'o'.$i.'_bit'.$j;
                $sum = $sum + $_GET[$chs];
            }
            
            $a= $i-1;
            
            if($sum != $masque_correction[$a] ){
                $bool= 0;
            }
            ;
            $i++;
        }
        if($bool== 0){
            echo'Faux!<br/>';
            for($i=1;$i<=4;$i++){
                $a=$i-1; 
                $res[$i]= array();
                $calcul=$masque_correction[$a];
                for($j=128;$j>=1;$j=$j/2){
                    if(($calcul-$j >=0) and ($calcul>0)){
                       
                        $calcul= $calcul - $j;
                        $res[$i][]= $j;
                    }
                    else{
                        $res[$i][]= 0;
                    }
                }
            }
            foreach($res as $valeur){
                echo'|';
                foreach($valeur as $val){
                    echo $val.'| ';
                }
                echo ' . ';
            }
           
        }
        else{
            echo 'Correct!';
        }
    }

    function correcCheckbox($masque_correction,$title,$bdd){
        $identique=1;
        $i=1;
        while($i<=4 and $identique ==1){
            $sum=0;
            for($j=128;$j>=1;$j=$j/2){
                $ch= 'o'.$i.'_bit'.$j.'_';
                if(isset($_POST[$ch]) and (trim($ch)!='')){
                    $sum+= $j;
                }
            }
            $indice=$i-1;
           
            if($sum == $masque_correction[$indice]){
                $identique=1;
            }
            else{
                $identique=0;
                $res=array();
                if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,-1,$bdd);
                echo"<div class='alert alert-danger alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Faux! La réponse était :<br/>";
                /*
                <div class="btn-group" data-toggle="buttons">
                  <label class="btn btn-primary active">
                    <input type="checkbox" autocomplete="off" checked> Checkbox 1 (pre-checked)
                  </label>
                  <label class="btn btn-primary">
                    <input type="checkbox" autocomplete="off"> Checkbox 2
                  </label>
                  <label class="btn btn-primary">
                    <input type="checkbox" autocomplete="off"> Checkbox 3
                  </label>
                </div>
                */
                
                for($i=1;$i<=4;$i++){
                    $a=$i-1; 
                    $res[$i]= array();
                    $calcul=$masque_correction[$a];
                    for($j=128;$j>=1;$j=$j/2){
                        if(($calcul-$j >=0) and ($calcul>0)){
                            $calcul= $calcul - $j;
                            $res[$i][]= $j;
                        }
                        else{
                            $res[$i][]= 0;
                        }
                    }
                }
                
                foreach($res as $num_octet => $valeur){
                   echo '<div ><span class="lead">Octet n°'.$num_octet.'</span><br />';
                    foreach($valeur as $val){
                        if($val>0)
                            echo '<span class="btn btn-primary active">1</span>';
                        else
                            echo '<span class="btn btn-primary">0</span>';
                        
                    }
                    $prem_boucle=true;
                    foreach($valeur as $val){
                        if($prem_boucle){
                            echo "</br>".$val;
                            $prem_boucle=false;   
                        }
                        else
                            echo " + ".$val;
                        
                    }
                    echo '</div><br/>';
                }
                echo '<br/><div class="lead">Le masque est donc :<br /><b>';
                $prem_boucle=true;
                foreach($res as $valeur){
                    if(!$prem_boucle)
                        echo'.';
                    $prem_boucle=false;
                    $valeur_octet=0;
                    foreach($valeur as $val){
                        $valeur_octet+= $val;
                    }
                    echo $valeur_octet;
                }
                echo '</b></div>';
            }
            $i++;
        }
        if($i ==5){
            $res=array();
            if(isset($_SESSION['connect'])) exerciceFait($_SESSION['connect'],$title,1,$bdd);
            echo "<div class='alert alert-success alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>Correct! La réponse était bien :<br/>";
            for($i=1;$i<=4;$i++){
                $a=$i-1; 
                $res[$i]= array();
                $calcul=$masque_correction[$a];
                for($j=128;$j>=1;$j=$j/2){
                    if(($calcul-$j >=0) and ($calcul>0)){
                        $calcul= $calcul - $j;
                        $res[$i][]= $j;
                    }
                    else{
                        $res[$i][]= 0;
                    }
                }
            }
            foreach($res as $num_octet => $valeur){
               echo '<div ><span class="lead">Octet n°'.$num_octet.'</span><br />';
                foreach($valeur as $val){
                    if($val>0)
                        echo '<span class="btn btn-primary active">1</span>';
                    else
                        echo '<span class="btn btn-primary">0</span>';
                    
                }
                $prem_boucle=true;
                foreach($valeur as $val){
                    if($prem_boucle){
                        echo "</br>".$val;
                        $prem_boucle=false;   
                    }
                    else
                        echo " + ".$val;
                    
                }
                echo '</div><br/>';
            }
            echo '<br/><div class="lead">Le masque est bien :<br /><b>';
            $prem_boucle=true;
            foreach($res as $valeur){
                if(!$prem_boucle)
                    echo'.';
                $prem_boucle=false;
                $valeur_octet=0;
                foreach($valeur as $val){
                    $valeur_octet+= $val;
                }
                echo $valeur_octet;
            }
            echo '</b></div>';
        }
        echo "</strong></div>";
    }
?>