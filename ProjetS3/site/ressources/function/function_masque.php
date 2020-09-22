<?php
    function masque($title,$bdd){
?>
        <h1>Masque</h1>
        <div class="panel panel-default">
            <div class="panel-heading">
                    <h3 class="panel-title">Exercice</h3>
            </div>
            <div class="panel-body">
                <div class="alert bg-info">
                    <p>
                        Ici, nous allons vous aider à visualiser ce qu'est réellement un masque. 
                        En effet, quand on vous dit masque, vous regardez la classe de l'adresse IP pour voir combien de 255 il faut mettre. 
                        Exemple: 255.255.0.0 est un masque typique que vous rencontrez en cours.
    	            </p>
    	            <p>
    	                Néanmoins, avec la notation CIDR, vous allez voir des masques comme 255.172.0.0. 
    	                Pour comprendre cela, nous allons décomposer chacun de ces octets en 8 bits.
                    </p>
                    <p>
                	    Chaque ligne représente un octet. 
                	    Dans chaque ligne, il y a 8 cases. La case la plus à gauche est le bit de poids fort et celui le plus à droite le bit de poids faible. 
                	    En fonction de l'adresse et de la notation CIDR, cochez tous les bits qui sont à 1. Pour vous donner une idée du masque en décimal, la valeur du bit que vous cocherez apparaîtra juste en dessous.
            	    </p>
                </div>
                
                <div class="lead bg-info well">
                    <?php
                        $IP=array('o1'=>rand(1,223),'o2'=>rand(0,255),'o3'=>rand(0,255),'o4'=>rand(1,254));     
                        $CIDR=0;
                        if($IP['o1'] <= 127){
                            $CIDR= rand(8,15); // si c'est une classe A alors les 8 premiers bits importent dejà c'est donc sur les autres bits qu'il faut jouer
                        }elseif($IP['o1']<= 191){
                            $CIDR= rand(16,23);
                        }else{
                            $CIDR= rand(24,31);
                        }
                        $octet=0;
                        $masque_correction=0;
                        correcMasque($masque_correction, $octet, $CIDR);
                        // echo $ch= $IP; 
                        echo 'Adresse IP : '. $IP['o1'].'.'.$IP['o2'].'.'.$IP['o3'].'.'.$IP['o4'].' /'.$CIDR;
                        $ch= implode('.',$IP);
                        $ch= $ch.'/'.$CIDR;
                        $mCH= implode('.',$masque_correction);
                    ?>
                </div>
                <?php
                    if(!empty($_POST['submit']) && isset($_POST['IP']) && isset($_POST['masqueCorre'])){
                        
                        $masque_correction= explode('.',$_POST['masqueCorre']);
                        echo correcCheckbox($masque_correction,$title,$bdd);
			echo '<p align=center><a href=Masque.php?>Recommencer</a></p>';
                    }else {
                ?>
                <form class="" action="" method="post">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-offset-1 col-xs-12">
                                <p for="nbBits" class="control-label">Trouver la partie réseau de l'adresse IP et passez à 1 les bits correspondants.
                                <br />
                                <br/>
                                <i>Cocher toutes les cases (qui représentent ici les bits) qui doivent être mis à 1 pour fabriquer le masque adapté à cette adresse IP</i>.</p>
                            </div>
                        </div>
                       <div class="row">
                            <div class="col-sm-offset-1 col-xs-12">
                            <?php
                                for($i=1;$i<=4;$i++)
                                {
                                    echo '<div class="form-group"><div class="btn-group " data-toggle="buttons" id="octet_'.$i.'"><span class="lead">Octet n°'.$i.'</span><br />';
                                    
                                    for($j=128;$j>=1;$j=$j/2)
                                    {
                                        echo '<label class="btn btn-primary input-checkbox-bits">';
                                        $sel= 'o'.$i.'_bit'.$j.' ';
                                        echo '<input class="test" type="checkbox" name="'.$sel.'"/><p name="'.$sel.'_text" style="margin:0px;">0</p></label>';
                                    }
                                    echo '<div class="bg-info" id="rep_octet_'.$i.'"></div></div></div>';
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="masqueCorre" value="<?php echo $mCH; ?>"/>
                    <input type="hidden" name="IP" value="<?php echo $ch; ?>"/>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-4 col-md-offset-1">  
                                <input type="submit" name="submit" class="btn btn-success col-xs-12" name=""/>
                            </div>
                        </div>
                        <div class="row">
                                <div class="col-md-4 col-md-offset-1">
                                <input type="submit" name="retry" value="Nouvelle IP" class="btn btn-warning col-xs-12" />
                            </div>
                            <div class="row  form-group"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php
    }}
    
    function script_masque(){//Ce code javascript a pour but d'afficher la valeur décimal associé au bit sur lequel l'utilisateur a cliqué 
        ?>
        <script>
            jQuery(document).ready(function(){
                $(".input-checkbox-bits").click(function(){
                    var valeur= $(this).children(".test").attr("name");
                    var coupure= valeur.substr(6,3);
                    var id_parent=$(this).parent().attr('id');
                    var chaine= $("#rep_"+id_parent).html();
                    var tab= chaine.split(' ');
                    var estPresent=0;
                    for(var i=0;i<tab.length;i++){
                        tab[i]= tab[i] |0;
                    }
                    for( i=0;i<tab.length;i++){
                        if(tab[i]== coupure){
                            estPresent=1;
                            tab[i]='';
                        }
                    }
                    if(!estPresent){
                        document.getElementsByName(valeur+"_text").item(0).textContent=1;
                    }
                    else{
                        document.getElementsByName(valeur+"_text").item(0).textContent=0;
                    }
                    chaine='';
                    for(i=0;i<tab.length;i++){
                        for(var j=0;j<tab.length;j++){
                            if(tab[i]< tab[j]){
                                var stock= tab[i];
                                tab[i]=tab[j];
                                tab[j]= stock;
                            }
                        }
                    }
                    for( i=(tab.length-1);i>=0;i--){
                        if(tab[i]!=0){
                            if(chaine.length>=1){
                                chaine= chaine +' + '+ tab[i];
                            }
                            else{
                                chaine= chaine + tab[i];
                            }
                            
                        }
                    }
                    $("#rep_"+id_parent).html(chaine);
                    if(estPresent == 0){
                        chaine='';
                        tab.push(coupure);
                        for(i=0;i<tab.length;i++){
                            for(j=0;j<tab.length;j++){
                                if(tab[i]< tab[j]){
                                    stock= tab[i];
                                    tab[i]=tab[j];
                                    tab[j]= stock;
                                }
                            }
                        }
                        for( i=(tab.length-1);i>=0;i--){
                            if(tab[i]!=0){
                                if(chaine.length>=1){
                                  chaine= chaine +' + '+ tab[i];
                                }
                                else{
                                     chaine= chaine + tab[i];
                                }
                               
                            }
                        }
                        $("#rep_"+id_parent).html(chaine);
                       
                    }
                });
            });
        </script>
        <?php
    }
?>
