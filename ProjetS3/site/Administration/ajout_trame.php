<?php
    session_start();// démarrage de la session
    $errors = array();//création d'un tableau pour les éventuelles erreurs
    $type= array();
    $ajoutTab= array();
    $ajoutTabMin= array();
    $ajoutTabMax= array();
    $tempMin='';
    $tempMax='';
    require('../ressources/connectBDD.php');//Inlcusion du code nécéssaire à la connection à la base de données
    $erreur=0;
    $_SESSION['nbChamp']=$_POST['nbChamp'];
    if(isset($_POST['name']) && !trim($_POST['name'])==''){//Si le champ name (le type de trame) existe et n'est pas vide, on le stock dans $type
        $type[] =  $_POST['name'];
        if((preg_match('#^([ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ]*[[:alnum:]]*[[:space:]]*){1,}$#', $type[0]))==1){//Si la preg match renvoi true alors on affecte 1 a $type
        	$type[]=1;
    	}
    	else{//Sinon on incrémente $erreur, et on ajoute au tableau $errors l'erreur correspondante. On affecte 0 à $type
    	    $erreur++;
            $errors[]='Le nom saisi est incorrect !';
            $type[]=0;
    	}
    }
    else{//Si le champ est vide et/ou n'existe pas, alors on incrémente $erreur et on ajoute au tableau $errors l'erreur correspondante
        $erreur++;
        $errors[]='Il y n\'a pas de nom saisi !';
    }
    for($i=0;$i<$_POST['nbChamp'];$i++){//boucle pour parcourir les différentes lignes et les différents champs
        $ajoutTab[]=htmlspecialchars($_POST['name_'.$i]);//on stock le champ name_$i dans $ajoutTab
        if(isset($_POST['name_'.$i]) && !trim($_POST['name_'.$i])==''){//Si le champ name_$i (le nom du champ) existe et n'est pas vide, on le stock dans $ajout
            if($i>0){
            	$ajout .= ',';
            }
            $ajout .= htmlspecialchars($_POST['name_'.$i]);
            
            $ajoutTab[]=1;
        }
        else{//Sinon on incrémente $erreur, et on ajoute au tableau $errors l'erreur correspondante. On affecte 0 à $ajoutTab
            $erreur++;
            $errors[]='Il y n\'a pas de nom saisi pour le champ '.$i.' !';
            $ajoutTab[]=0;
        }
        if(isset($_POST['min_'.$i]) && trim($_POST['min_'.$i])!=''){//Si le champ min_$i (la taille minimum du champ) existe et n'est pas vide, on le stock dans $ajoutTabMin
            $ajoutTabMin[]=htmlspecialchars($_POST['min_'.$i]);//on stock le champ min_$i dans $ajoutTabMin
            if((preg_match('#^[0-9]*$#', $ajoutTabMin[$i*2]))==1){//Si la preg match renvoi true alors on affecte min_$i a $tempMin
                $tempMin=htmlspecialchars($_POST['min_'.$i]);
            }
            else{//Sinon on incrémente $erreur, et on ajoute au tableau $errors l'erreur correspondante. On affecte 0 à $ajoutTabMin
                $erreur++;
                $ajoutTabMin[]=0;
                $errors[]='Il y a une erreur dans la taille fixe/min du champ '.$i.' !';
            }
        }
        else{//Si le champ est vide et/ou n'existe pas, alors on incrémente $erreur et on ajoute au tableau $errors l'erreur correspondante
            $erreur++;
            $errors[]='Il y n\'a pas de taille fixe/min saisi pour le champ '.$i.' !';
        }
        if(isset($_POST['max_'.$i])&& trim($_POST['max_'.$i])!=''){//Si le champ max_$i (la taille maximum du champ) existe et n'est pas vide, on le stock dans $ajoutTabMax
            $ajoutTabMax[]=htmlspecialchars($_POST['max_'.$i]);//on stock le champ max_$i dans $ajoutTabMax
            if((preg_match('#^[0-9]*$#', $ajoutTabMax[$i*2]))==1){//Si la preg match renvoi true alors on affecte max_$i a $tempMax
                $tempMax=htmlspecialchars($_POST['max_'.$i]);
            }
            elseif($ajoutTabMax[$i*2]=='*'){//Si le champ max_$i contient *, alors on ajoute '' a $ajoutMax
                if($i>0){
        	    	$ajoutMax .= ',';
        	    }
        	    $ajoutMax .= '';
        	    $ajoutTabMax[]=1;
            }
            else{//Sinon on incrémente $erreur, et on ajoute au tableau $errors l'erreur correspondante. On affecte 0 à $ajoutTabMax
                $erreur++;
                $errors[]='Il y a une erreur dans la taille max du champ '.$i.' !';
                $ajoutTabMax[]=0;
            }
        }
        else{//Si le champ est vide et/ou n'existe pas, alors on incrémente $erreur et on ajoute au tableau $errors l'erreur correspondante
            $erreur++;
            $errors[]='Il y n\'a pas de taille max saisi pour le champ '.$i.' !';
        }
        if($tempMax!=''&&$tempMin!=''){//Si $tempMax et $tempMin ne sont pas vide on compare leurs valeurs
            if($tempMax>$tempMin){//Si $tempMax est plus grand que $tempMin alors on ajoute min_$i et max_$i à $ajoutMin et $ajoutMax respectivement
                if($i>0){
                	$ajoutMin .= ',';
                }
                $ajoutMin .= htmlspecialchars($_POST['min_'.$i]);
                $ajoutTabMin[]=1;
                if($i>0){
                    $ajoutMax .= ',';
                }
                $ajoutMax .= htmlspecialchars($_POST['max_'.$i]);
                $ajoutTabMax[]=1;
                $tempMin='';
                $tempMax='';
            }
            else{//Sinon on incrémente $erreur, on ajoute 0 à $ajoutTabMin et $ajoutTabMax, et on ajoute au tableau $errors l'erreur correspondante
                $erreur++;
                $ajoutTabMin[]=0;
                $ajoutTabMax[]=0;
                $errors[]='La taille maximale doit être supérieur à la taille minimale !';
            }
        }
        elseif($tempMax!=''&&$tempMin==''){//Si $tempMin est vide et $tempMax n'est pas vide alors on ajoute max_$i a $ajoutMax
            if($i>0){
                $ajoutMax .= ',';
            }
            $ajoutMax .= htmlspecialchars($_POST['max_'.$i]);
            $ajoutTabMax[]=1;
            $tempMax='';
        }
        elseif($tempMax==''&&$tempMin!=''){//Si $tempMax est vide et $tempMin est non vide
            if($tempMin>0){//on verifie que $tempMin soit supérieur à 0, si oui on ajoute min_$i à $ajoutMin
                if($i>0){
                	$ajoutMin .= ',';
                }
                $ajoutMin .= htmlspecialchars($_POST['min_'.$i]);
                $ajoutTabMin[]=1;
                $tempMin='';
            }
            else{//Sinon on incrémente $erreur, on ajoute 0 à $ajoutTabMin, et on ajoute au tableau $errors l'erreur correspondante
                $errors[]='Une taille fixe ne peut être égale à 0 !';
                $ajoutTabMin[]=0;
                $tempMin='';
                $erreur++;
            }
        }
    }
    if($erreur==0){//S'il n'y a pas d'erreurs alors on ajoute à la base de données les informations saisies précédemment
        $ajour_req = 'INSERT into trame values(:nom,:ajout,:ajoutMin,:ajoutMax)';
        try{
            $req = $bdd->prepare($ajour_req);
            $req->bindValue(":nom",$type[0]);
            $req->bindValue(":ajout",$ajout);
            $req->bindValue(":ajoutMin",$ajoutMin);
            $req->bindValue(":ajoutMax",$ajoutMax);
        	$req->execute();
        }
        catch(PDOException $e)
        {
            $errors[]= $e->getMessage();
            //die('<p> Erreur['.$e->getCode.'] : '.$e->getMessage().'</p>');
        }
    }
    if(!empty($errors)){//Si erreur(s), on retourne la tableau
        $_SESSION['errors'] = $errors;
    }
    else{//Sinon on affecte 1 à la variable $_SESSION['success']
        $_SESSION['success'] = 1;
    }
    $_SESSION['type']=$type;
    $_SESSION['ajoutTab']=$ajoutTab;
    $_SESSION['ajoutTabMin']=$ajoutTabMin;
    $_SESSION['ajoutTabMax']=$ajoutTabMax;
    header('Location: admin_trame.php');//On retourne sur la page d'administration
?>
