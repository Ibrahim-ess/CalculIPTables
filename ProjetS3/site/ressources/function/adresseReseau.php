<?php
    function getAdresseReseau(){
        $o = array();
        $o[0] = rand(0, 240);
        for($i=1;$i<4;$i++)
            $o[$i] = rand(0, 255);
        return $o;
    }
    
    function getPlusLong($adresse1, $adresse2, $ressemblance){
        $corrige['taille'] = 0;//initialisation de la taille à 0
        for($i=0;$i<4;$i++)//Cette initialisation allège le code par la suite.
            $corrige[$i] = 0;
        for($i=0;$i<(int)($ressemblance/8);$i++){//Pour tout les premiers octets identiques pourles deux adresses
            $corrige['taille']+=8;//la taille du prefixe augmente d'un octet
            $corrige[$i]=$adresse1[$i];//et l'ip du prefixe à un octet de réponse en plus.
        }
        for($i=(int)($ressemblance/8);$i<4;$i++){//Pour les octets restants.
            if($adresse1[$i] != $adresse2[$i]){//S'il les deux adresses ne sont pas identique à l'octet i.
                $d1 = $adresse1[$i];//Je récupère les octets
                $d2 = $adresse2[$i];
                for($poids=128;$poids>1;){//On commence par le bit de poids fort pour la résolution en binaire.
                    if($d1>=$poids && $d2>=$poids){//Si le bit de poids fort est le même pour les deux.
                        $d1-=$poids;//Je le retire pour le prochain test
                        $d2-=$poids;
                        $corrige[$i]+=$poids;//La valeur de l'octet du corrigé est augmenté du poids du bit en cours.
                        $corrige['taille']+=1;//La taille augmente de un bit.
                    }
                    else if($d1<$poids && $d2<$poids){
                        $corrige['taille']+=1;//Si le bit est à 0 pour les deux, la taille augmente de 1.
                    }
                    else{
                        return $corrige;//Une différence entre les deux bits et le code se termine.
                    }
                    $poids/=2;//Pour une raison obscure cela ne fonctionnait pas dans le for.
                }
            }
            else{//Si les deux adresses sont identique à l'octet i.
                $corrige[$i]=$adresse1[$i];//Un octet en plus dans le prefixe.
                $corrige['taille']+=8;//Un octet en plus dans sa taille.
            }
        }
        return $corrige;
    }
    
    //              /!\OUTDATED, MIRROIR SANS STEP BY STEP/!\
    //A CONSERVER (par soucis de compréhension du code et de changements possibles)
    function getPlusLongDur($adresse1, $adresse2, $adresse3){
        $corrige['taille'] = 8;//initialisation de la taille à 8
        $corrige[0] = $adresse1[0];
        for($i=1;$i<4;$i++)//Cette initialisation allège le code par la suite.
            $corrige[$i] = 0;
            
        for($i=1;$i<4;$i++){//Le premier octet est toujours identique.
            if($adresse1[$i] == $adresse2[$i] && $adresse1[$i] == $adresse3[$i]){
                $corrige[$i] = $adresse1[$i];
                $corrige['taille'] += 8;
            }
            else{
                $octet = 128;
                for($j=0;$j<8;$j++){//8 bits dans chaque octet
                    if($adresse1[$i]>=$octet && $adresse2[$i]>=$octet && $adresse3[$i]>=$octet){//Si le bit est à 1 pour tous
                        //On ne compte plus cet octet
                        $adresse1[$i]-=$octet;
                        $adresse2[$i]-=$octet;
                        $adresse3[$i]-=$octet;
                        //On ajuste le corrigé
                        $corrige[$i]+=$octet;
                        $corrige['taille']++;
                    }
                    else if($adresse1[$i]<$octet && $adresse2[$i]<$octet && $adresse3[$i]<$octet){//Si le bit est à 0 pour tous
                        //Seul la taille du corrigé est à changer
                        $corrige['taille']++;
                    }
                    else{//Sinon, il y a une différence le prefixe est fini
                        return $corrige;
                    }
                    $octet/=2;
                }
            }
        }
        return $corrige;
    }
    
    function getPlusLongDurSBS($adresse1, $adresse2, $adresse3){
        $corrige['taille'] = 8;//initialisation de la taille à 8
        $corrige[0] = $adresse1[0];
        $corrige['SBS'] = '<p>On cherche d\'abord quels sont les octets communs aux trois adresses:<br/>-' . $adresse1[0] . ', et on augmente la taille de 8 bits (un octet) : ' . $corrige['taille'];
        for($i=1;$i<4;$i++)//Cette initialisation allège le code par la suite.
            $corrige[$i] = 0;
        
        for($i=1;$i<4;$i++){//Le premier octet est toujours identique.
            if(($adresse1[$i] == $adresse2[$i]) && ($adresse1[$i] == $adresse3[$i])){
                $corrige[$i] = $adresse1[$i];
                $corrige['taille'] += 8;
                $corrige['SBS'] = $corrige['SBS'] . '<br/>-' . $adresse1[$i] . ', et on augmente la taille de 8 bits (un octet) : ' . $corrige['taille'];
                if($i == 3){
                    $corrige['SBS'] = $corrige['SBS'] . '<br/>Tout les octets sont en communs, le travail est donc fini.';
                }
            }
            else{
                $corrige['SBS'] = $corrige['SBS'] . '<br/>On compare ensuite le premier octet qui diffère :';
                $octet = 128;
                for($j=0;$j<8;$j++){//8 bits dans chaque octet
                    //$corrige['SBS'] = $corrige['SBS'] . '<br/>Bit n' . $j+1 . '<br/>--------<br/>Valeur : ' . $octet;
                    if($adresse1[$i]>=$octet && $adresse2[$i]>=$octet && $adresse3[$i]>=$octet){//Si le bit est à 1 pour tous
                        //On ne compte plus cet octet
                        $adresse1[$i]-=$octet;
                        $adresse2[$i]-=$octet;
                        $adresse3[$i]-=$octet;
                        //On ajuste le corrigé
                        $corrige[$i]+=$octet;
                        $corrige['taille']++;
                        $corrige['SBS'] = $corrige['SBS'] . '<br/>Tout les octets n°' . ($i+1) . ' sont supérieurs à ' . $octet . '.'
                                                        . '<br/>On incrémente donc la taille : ' . $corrige['taille'] . ', et la valeur de l\'octet corrigé augmente de la valeur du bit : ' . $corrige[$i]
                                                        . '<br/>On décrémente fictionellement les octets n°' . ($i+1) . ' de chaque adresse : '
                                                        . '<ul><li>Adresse 1 : ' . ($adresse1[$i]+$octet) . ' - ' . $octet . ' = ' . $adresse1[$i] . '</li>'
                                                        . '<li>Adresse 2 : ' . ($adresse2[$i]+$octet) . ' - ' . $octet . ' = ' . $adresse2[$i] . '</li>'
                                                        . '<li>Adresse 3 : ' . ($adresse3[$i]+$octet) . ' - ' . $octet . ' = ' . $adresse3[$i] . '</li></ul>';
                    }
                    else if($adresse1[$i]<$octet && $adresse2[$i]<$octet && $adresse3[$i]<$octet){//Si le bit est à 0 pour tous
                        //Seul la taille du corrigé est à changer
                        $corrige['taille']++;
                        $corrige['SBS'] = $corrige['SBS'] . '<br/>Tout les octets n°' . ($i+1) . ' sont strictements inférieurs à ' . $octet . '.';
                        $corrige['SBS'] = $corrige['SBS'] . '<br/>On incrémente donc la taille : ' . $corrige['taille'] . ', et la valeur de l\'octet corrigé reste inchangée : ' . $corrige[$i];
                    }
                    else{//Sinon, il y a une différence le prefixe est fini
                        $corrige['SBS'] = $corrige['SBS'] . '<br/>Finalement, l\'un des octets à le bit n°' . ($j+1) . ' à 1 et un autre à 0. '
                                                        . 'L\'octet actuel reste à ' . $corrige[$i] . ' et les octets restants sont mis à 0</p>';
                        return $corrige;
                    }
                    $octet/=2;
                }
            }
        }
        $corrige['SBS'] = $corrige['SBS'] . '</p>';
        return $corrige;
    }
    
    function tireReponse($prefixe){
        
    }
?>