<?php

    //Fonction vérifiant si l'utilisateur existe dans la base de données
    function isExistUser($userID,$bdd){
        try{
            $requete=$bdd->prepare("select distinct * from utilisateurs where UserID=:userID");
            $requete->bindValue("userID",$userID);
            $requete->execute();
            if($requete->fetch(PDO::FETCH_ASSOC)){
                return true;
            }
        }catch(PDOException $e){
            echo "";
            return null;
            
        }
        return -1;
    }
    
    //Fonction permettant a l'utilisateur de se connecter à la base de données
    function connectionUser($userID,$bdd){
        try{
            //On vérifie s'il existe,si ce n'est pas le cas, on ajoute un utilisateur à la base
            if ( ($isExist=isExistUser($userID,$bdd))!=null){
                if($isExist==-1){
                    $requete=$bdd->prepare("INSERT INTO utilisateurs values(:userID)");
                    $requete->bindValue("userID",$userID);
                    $requete->execute();
                }
                
            }
        }catch(PDOException $e){
            echo "";
            
        }
    }
    //Cela récupère les statistiques de l'utilisateur sur tous les exercices
    function getStatsAllExercices($userID,$bdd){
        try{
            if ( ($isExist=isExistUser($userID,$bdd))!=null){
                if($isExist==true){
                    $requete=$bdd->prepare("SELECT exercices.*, reussi, echec FROM exercice_fait join exercices on exercice_fait.id_exercice= exercices.id_exercice WHERE UserID=:userID ORDER BY nom_exercice");
                    $requete->bindValue("userID",$userID);
                    $requete->execute();
                }
                $tab_stats=array();
                //La requête va récupérer les informations sur les exercices faits
                while($rep=$requete->fetch(PDO::FETCH_ASSOC)){
                    $tab_stats[$rep['id_exercice']]=array('id'=>$rep['id_exercice'],'nom_exercice'=>$rep['nom_exercice'],'url'=>$rep['url'],'reussi'=>$rep['reussi'],'echec'=>$rep['echec']);
                }
                
                $tab_exo=getExercices($bdd); //On récupere tous les exercices
                //On vérifie que le tableau contenant les exercices fait contient tous les exercices;
                //S'il manque un exercice (donc un exercice non réalisé), on l'ajoute dans le tableau.
                foreach($tab_exo as $cle => $valeur){
                    if(!array_key_exists($cle, $tab_stats)){
                        $tab_stats[$cle]=array('id'=>$cle,'nom_exercice'=>$tab_exo[$cle]['nom_exercice'],'url'=>$tab_exo[$cle]['url'],'reussi'=>0,'echec'=>0);
                    }
                }
                return $tab_stats;
            }
        }catch(PDOException $e){
            echo "";
            
        }
        return null;
    }
    
    //Cela récupère les statistiques de l'utilisateur sur les exercices faits
    function getStatsExercicesDone($userID,$bdd){
        try{
            if($isExist=isExistUser($userID,$bdd)!=null){
                if($isExist==true){
                    $requete=$bdd->prepare("SELECT exercices.*, reussi, echec FROM exercice_fait join exercices on exercice_fait.id_exercice= exercices.id_exercice WHERE UserID=:userID ORDER BY nom_exercice");
                    $requete->bindValue("userID",$userID);
                    $requete->execute();
                }
                $tab_stats=array();
                while($rep=$requete->fetch(PDO::FETCH_ASSOC)){
                    $tab_stats[$rep['id_exercice']]=array('id'=>$rep['id_exercice'],'nom_exercice'=>$rep['nom_exercice'],'reussi'=>$rep['reussi'],'echec'=>$rep['echec']);
                }
                return $tab_stats;
            }
        }catch(PDOException $e){
            echo "";
            
        }
        return null;
    }
    
    function getExercices($bdd){
        try{
                $requete=$bdd->prepare("select * from exercices ORDER BY nom_exercice");
                $requete->bindValue("userID",$userID);
                $requete->execute();
                $tab_exercices=array();
                while($rep=$requete->fetch(PDO::FETCH_ASSOC)){
                    $tab_exercices[$rep['id_exercice']]=array('id'=>$rep['id_exercice'],'nom_exercice'=>$rep['nom_exercice'],'url'=>$rep['url']);
                }
                return $tab_exercices;
        }catch(PDOException $e){
            echo "Erreur";
        }
        return null;
    }
    
    
    function afficheTabExercices($userID,$bdd){
        $tab=getStatsAllExercices($userID,$bdd);
        echo '
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nom de l\'exercice</th>
                    <th>Nombres de bonne(s) réponse(s)</th>
                    <th>Nombres d\'erreur(s)</th>
                </tr>
            </thead>
            <tbody>';

        foreach($tab as $cle =>$valeur){
            echo '<tr>
                <td><a href="/CalculIP/'.$tab[$cle]['url'].'">'.$tab[$cle]['nom_exercice'].'</a></td>
                <td>'.$tab[$cle]['reussi'].'</td>
                <td>'.$tab[$cle]['echec'].'</td>
                </tr>';
        }
        echo ' </tbody>
            </table>';
    }
    function afficheProgExercices($userID,$bdd){
        $tab=getStatsExercicesDone($userID,$bdd);
        $nb_exo=count(getExercices($bdd));
        $pc_reussi=0;
        $pc_echec=0;
        foreach($tab as $cle =>$valeur){
            if($tab[$cle]['reussi']>0){
                $pc_reussi++;
            }else if($tab[$cle]['echec']>0){
                $pc_echec++;
            }
        }
        echo '<div class="progress">';
            if($pc_reussi!=0 || $pc_echec!=0){
                if($pc_reussi!=0){
                    echo '
                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar" aria-valuenow="'.round(($pc_reussi/$nb_exo*100),2).'" aria-valuemin="0" aria-valuemax="100" style="width: '.round(($pc_reussi/$nb_exo*100),2).'%">
                        <b>'.round(($pc_reussi/$nb_exo*100),2).'%</b><span class="sr-only"> des exercices faits ont été réussis</span>
                    </div>';
                }
                if($pc_echec!=0){
                    echo'
                    <div class="progress-bar progress-bar-danger progress-bar-striped active" role="progressbar" aria-valuenow="'.round(($pc_echec/$nb_exo*100),2).'" aria-valuemin="0" aria-valuemax="100" style="width: '.round(($pc_echec/$nb_exo*100),2).'%">
                        <b>'.round(($pc_echec/$nb_exo*100),2).'%</b><span class="sr-only"> des exercices faits ne sont pas réussis</span>
                    </div>';
                }
            }else{
                echo '
                <div class="progress-bar progress-bar-warning " role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                    <b>0%</b><span class="sr-only"> des exercices ont été faits</span>
                </div>';
            }
            echo'
            </div>
            <ul class="list-unstyled well">
                <li><b>'.round(($pc_reussi/$nb_exo*100),2).'%</b> des exercices faits ont été réussis au moins une fois</li>
                <li><b>'.round(($pc_echec/$nb_exo*100),2).'%</b> des exercices faits n\'ont pas encore été réussis</li>
                <li><b>'.round((count($tab)/$nb_exo*100),2).'%</b> des exercices ont été faits au moins une fois</li>
            </ul>';
        
    }
    function afficheStatsExercicesFaits($userID,$bdd){
        $tab=getStatsExercicesDone($userID,$bdd);
        $pc_reussi=0;
        $pc_echec=0;
        foreach($tab as $cle =>$valeur){
            if($tab[$cle]['reussi']>0){
                $pc_reussi+=$tab[$cle]['reussi'];
            }
            if($tab[$cle]['echec']>0){
                $pc_echec+=$tab[$cle]['echec'];
            }
        }
        echo '<div class="progress">';
            if( ($pc_reussi+ $pc_echec)!=0){
                echo '
                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="'.round(($pc_reussi/($pc_echec+$pc_reussi)*100),2).'" aria-valuemin="0" aria-valuemax="100" style="width: '.round(($pc_reussi/($pc_echec+$pc_reussi)*100),2).'%">
                    <b>'.round(($pc_reussi/($pc_echec+$pc_reussi)*100),2).'%</b><span class="sr-only"> des exercices faits ont été réussis</span>
                </div>
                <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="'.round(($pc_echec/($pc_echec+$pc_reussi)*100),2).'" aria-valuemin="0" aria-valuemax="100" style="width: '.round(($pc_echec/($pc_echec+$pc_reussi)*100),2).'%">
                    <b>'.round(($pc_echec/($pc_echec+$pc_reussi)*100),2).'%</b><span class="sr-only"> des exercices faits ne sont pas réussis</span>
                </div>';
        }else{
            echo '
            <div class="progress-bar progress-bar-warning " role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <b>0%</b><span class="sr-only">Aucun exercice enregistré !</span>
            </div>';
        }
        echo'
        </div>
        <ul class="list-unstyled well">
            <li><b>';
	if (($pc_echec+$pc_reussi)==0) {
		echo 'Aucun exercice enregistré !</b></li>';
	}else {
		echo round(($pc_reussi/($pc_echec+$pc_reussi)*100),2).'%</b> de tous les exercices réalisés ont été réussis</li>
            <li><b>'.round(($pc_echec/($pc_echec+$pc_reussi)*100),2).'%</b> n\'ont pas été réussis</li>';
	}
        echo '</ul>';
    }
    function afficheTabEvaluations($userID,$bdd){
        $tab=getEvalDone($userID,$bdd);
        echo '
        <table class="table table-striped">
              <thead>
                <tr>
                  <th>Date évaluation</th>
                  <th>Pourcentage de réussite</th>
                </tr>
              </thead>
              <tbody>';

        foreach($tab as $cle =>$valeur){
            echo '<tr>
                  <td>'.$tab[$cle]['Date_evaluation'].'</td>
                  <td>'.$tab[$cle]['Pourcentage'].'%</td>
                </tr>';
        }
        echo ' </tbody>
            </table>';
    }
    
    /*Fonction permettant d'actualiser la base de données lorsqu'un exercice est réalisé
        $isSuccess=1 : exercice réussi
        $isSuccess=-1 : exercice non réussi
    */
    function exerciceFait($userID,$nom_exercice,$isSuccess,$bdd){
        try{
            if( ($isExist=isExistUser($userID,$bdd)) !=null){
                if($isExist==true){
                    //Il faut récupérer l'id de l'exercice en fonction du nom
			error_log('nom exercice:'.$nom_exercice);
                    $req_id_exo=$bdd->prepare("Select * from exercices Where nom_exercice=:nom_exercice");
                    $req_id_exo->bindValue("nom_exercice",$nom_exercice);
                    $req_id_exo->execute();
                    $id_exercice=-1;
                    if($rep_id_exo=$req_id_exo->fetch(PDO::FETCH_ASSOC)){
                        $id_exercice=$rep_id_exo['id_exercice'];

                        //On vérifie si l'exercice a déjà été réalisé une fois
                        $req_exist_ex=$bdd->prepare("Select * from exercice_fait where id_exercice=:id_exercice and UserID=:userID");
                        $req_exist_ex->bindValue("id_exercice",$id_exercice);
                        $req_exist_ex->bindValue("userID",$userID);
                        $req_exist_ex->execute();
                        //Exercice déjà réalisé, on met à jour la table
                        if($req_exist_ex->fetch(PDO::FETCH_ASSOC)){
                            if($isSuccess==1)
                                $requete=$bdd->prepare("UPDATE exercice_fait set reussi=reussi+1 where id_exercice=:id_exercice and UserID=:userID");
                            else if ($isSuccess==-1)
                                $requete=$bdd->prepare("UPDATE exercice_fait set echec=echec+1 where id_exercice=:id_exercice and UserID=:userID");
                        }else{
                            if($isSuccess==1)
                                $requete=$bdd->prepare("INSERT INTO exercice_fait values(:id_exercice,:userID,1,0)");
                            else if ($isSuccess==-1)
                                $requete=$bdd->prepare("INSERT INTO exercice_fait values(:id_exercice,:userID,0,1)");
                        }
                        $requete->bindValue("id_exercice",$id_exercice);
                        $requete->bindValue("userID",$userID);
                        $requete->execute();
                    } else {
			error_log('nom exercice non trouvé:'.$nom_exercice);
		    }
                } else {
			error_log('Erreur user non existant:'.$userID);
		}
            }
        }catch(PDOException $e){
            echo "";
            
        }
    }
?>
