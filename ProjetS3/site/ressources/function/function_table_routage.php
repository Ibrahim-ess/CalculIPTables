<?php
    function generer_ip($difficulte,$bdd){
        try{
            $req=$bdd->prepare("Select * from reseaux where difficulte=:difficulte");
            $req->bindValue(':difficulte',$difficulte);
            $req->execute();
            
            $gen=$bdd->prepare("Update reseaux set Adresse=:ip_reseau where difficulte=:difficulte and id_reseau=:id_reseau");
            $gen->bindValue(':difficulte',$difficulte);
            while($rep=$req->fetch(PDO::FETCH_ASSOC)){
                if(intval($rep['num_reseau'])!=0){
                    $ip_reseau;
                    $id_reseau=$rep['id_reseau'];
                    switch($rep['classe']){
                        case 'A':
                            $ip_reseau=rand(1,127).".0.0.0";
                            break;
                        case 'B':
                            $ip_reseau=rand(128,191).".".rand(0,255).".0.0";
                            break;
                        case 'C':
                            $ip_reseau=rand(192,223).".".rand(0,255).".".rand(0,255).".0";
                            break;
                        case 'D':
                            $ip_reseau=rand(224,239).".".rand(0,255).".".rand(0,255).".".rand(1,255);
                            break;
                    }
                    $gen->bindValue(':id_reseau',$id_reseau);
                    $gen->bindValue(':ip_reseau',$ip_reseau);
                    $gen->execute();
                }
                
            }
            
            $req_machine=$bdd->prepare("SELECT id_ip_machine,classe,adresse,ip_machine FROM ip_machines JOIN reseaux ON reseaux.id_reseau = ip_machines.id_reseaux AND reseaux.difficulte = ip_machines.difficulte WHERE ip_machines.difficulte =:difficulte");
            $req_machine->bindValue(':difficulte',$difficulte);
            $req_machine->execute();
            $gen_machine=$bdd->prepare("Update ip_machines set ip_machine=:ip_machine where id_ip_machine=:id_ip_machine ");
            while($rep=$req_machine->fetch(PDO::FETCH_ASSOC)){
            
                $ip_machine;
                switch($rep['classe']){
                    case 'A':
                        if(preg_match('#^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$#', $rep['adresse'], $ip)){
                            $ip_machine=$ip[1].".".rand(0,255).".".rand(0,255).".".rand(1,254);
                        }
                        break;
                    case 'B':
                        if(preg_match('#^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$#', $rep['adresse'], $ip)){
                            $ip_machine=$ip[1].".".$ip[2].".".rand(0,255).".".rand(1,254);
                        }
                        break;
                    case 'C':
                        if(preg_match('#^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$#', $rep['adresse'], $ip)){
                            $ip_machine=$ip[1].".".$ip[2].".".$ip[3].".".rand(1,254);
                        }
                        break;
                    case 'D':
                        if(preg_match('#^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$#', $rep['adresse'], $ip)){
                            $ip_machine=$ip[0];
                        }
                        break;
                }
                $gen_machine->bindValue(':id_ip_machine',$rep['id_ip_machine']);
                $gen_machine->bindValue(':ip_machine',$ip_machine);
                $gen_machine->execute();
            }
        }catch(PDOException $e){
            echo 'ERREUR:'.$e->getCode().' : '.$e->getMessage();
        }
    }
    function isExistMachine($difficulte,$nom,$bdd){
        $req=$bdd->prepare('Select id_machine from machines where difficulte=:difficulte and nom=:nom');
        $req->bindParam(":difficulte",$difficulte);
        $req->bindParam(":nom",$nom);
        $req->execute();
        if($req->fetch(PDO::FETCH_ASSOC)){
            return true;
        }
        return false;
    }
    function recupereNomMachine($id_machine,$bdd){
        $req=$bdd->prepare('Select nom from machines where id_machine=:id_machine');
        $req->bindParam(":id_machine",$id_machine);
        $req->execute();
        if($rep=$req->fetch(PDO::FETCH_ASSOC)){
            return $rep['nom'];
        }
        return null;
    }
    function ajoutMachine($difficulte,$nom,$image,$x,$y,$width,$height,$bdd){
        try{
            $add=$bdd->prepare('Insert into machines (id_machine,difficulte,nom,image,x,y,width,height) VALUES(:id_machine,:difficulte,:nom,:image,:x,:y,:width,:height)');
            $add->bindParam(":id_machine",$difficulte."-".$nom);
            $add->bindParam(":difficulte",$difficulte);
            $add->bindParam(":nom",$nom);
            $add->bindParam(":image",$image);
            $add->bindParam(":x",$x);
            $add->bindParam(":y",$y);
            $add->bindParam(":width",$width);
            $add->bindParam(":height",$height);
            $add->execute();
        }catch(PDOException $e){
            echo 'ERREUR '.$e->getCode().' : '.$e->getMessage();
        }
    }
    function isExistBranchement($difficulte,$nom_machine_1,$nom_machine_2,$bdd){
        $req=$bdd->prepare('Select id_branchement from branchements where difficulte=:difficulte and id_machine_1=:id_machine_1 and id_machine_2=:id_machine_2');
        $add->bindParam(":difficulte",$difficulte);
        $add->bindParam(":id_machine_1",$difficulte."-".$nom_machine_1);
        $add->bindParam(":id_machine_2",$difficulte."-".$nom_machine_2);
        $req->execute();
        if($req->fetch(PDO::FETCH_ASSOC)){
            return true;
        }
        return false;
    }
    function ajoutBranchement($difficulte,$nom_machine_1,$nom_machine_2,$interface_machine_1,$interface_machine_2,$bdd){
        try{
            $add=$bdd->prepare('Insert into branchements (id_branchement,difficulte,id_machine_1,id_machine_2,machine_interface_1,machine_interface_2) VALUES(:id_branchement,:difficulte,:id_machine_1,:id_machine_2,:machine_interface_1,machine_interface_2)');
            $add->bindParam(":id_branchement",$difficulte."-".$nom_machine_1."-".$nom_machine_2);
            $add->bindParam(":difficulte",$difficulte);
            $add->bindParam(":id_machine_1",$difficulte."-".$nom_machine_1);
            $add->bindParam(":id_machine_2",$difficulte."-".$nom_machine_2);
            $add->bindParam(":machine_interface_1",$interface_machine_1);
            $add->bindParam(":machine_interface_2",$interface_machine_2);
            $add->execute();
        }catch(PDOException $e){
            echo 'ERREUR '.$e->getCode().' : '.$e->getMessage();
        }
    }
    function isExistReseau($difficulte,$num_reseau,$bdd){
        $req=$bdd->prepare('Select id_reseau from reseaux where difficulte=:difficulte and num_reseau=:num_reseau');
        $req->bindParam(":difficulte",$difficulte);
        $req->bindParam(":num_reseau",$num_reseau);
        $req->execute();
        if($req->fetch(PDO::FETCH_ASSOC)){
            return true;
        }
        return false;
    }
    function ajoutReseau($difficulte,$num_reseau,$classe,$cidr,$masque,$adresse,$bdd){
        try{
            $add=$bdd->prepare('Insert into reseaux (id_reseau,difficulte,num_reseau,classe,cidr,masque,adresse) VALUES(:id_reseau,:difficulte,:num_reseau,:classe,:cidr,:masque,:adresse,)');
            $add->bindParam(":id_reseau",$difficulte."-".$num_reseau);
            $add->bindParam(":difficulte",$difficulte);
            $add->bindParam(":num_reseau",$num_reseau);
            $add->bindParam(":classe",$classe);
            $add->bindParam(":cidr",$cidr);
            $add->bindParam(":masque",$masque);
            $add->bindParam(":adresse",$adresse);
            $add->execute();
        }catch(PDOException $e){
            echo 'ERREUR '.$e->getCode().' : '.$e->getMessage();
        }
    }
    
    function isExistIpMachine($difficulte,$id_reseau,$interface,$bdd){
        $req=$bdd->prepare('Select id_ip_machine from ip_machines where difficulte=:difficulte and id_machine=:id_machine and interface=:interface');
        $req->bindParam(":difficulte",$difficulte);
        $req->bindParam(":id_machine",$id_reseau);
        $req->bindParam(":interface",$interface);
        $req->execute();
        if($req->fetch(PDO::FETCH_ASSOC)){
            return true;
        }
        return false;
    }
    function ajoutIpMachine($difficulte,$id_machine,$interface,$id_reseau,$bdd){
        try{
            $add=$bdd->prepare('Insert into ip_machines (id_ip_machine,difficulte,id_machine,interface,id_reseau,ip_machine) VALUES(:id_ip_machine,:difficulte,:id_machine,:interface,:id_reseau,"")');
            $add->bindParam(":id_ip_machine",$difficulte."-".$id_machine."-".$interface);
            $add->bindParam(":difficulte",$difficulte);
            $add->bindParam(":id_machine",$id_machine);
            $add->bindParam(":interface",$interface);
            $add->bindParam(":id_reseau",$id_reseau);
            $add->execute();
        }catch(PDOException $e){
            echo 'ERREUR '.$e->getCode().' : '.$e->getMessage();
        }
    }
    


    function afficheTableauIp($difficulte,$bdd){
        $IP_machine=array();
        try{
            $req = $bdd->prepare('SELECT DISTINCT nom as machine,interface,ip_machine,Image FROM ip_machines inner join machines on ip_machines.id_machine=machines.id_machine WHERE ip_machines.difficulte=:diff ORDER BY machine,interface ASC');
    	    $req->bindValue(":diff",$difficulte);
        	$req->execute();
        	while($rep = $req->fetch(PDO::FETCH_ASSOC)){
        	    $IP_machine[count($IP_machine)]=$rep;
        	}
        	$cleTot=0;
            foreach($IP_machine as $cle => $val){
                if($cle==0){
                    echo '<table class="table table-bordered table-striped table-hover" >
                        <thead>
                            <tr>
                               <th>Interface</th>
                               <th>IP de '.$IP_machine[$cle]['machine'].'</th>
                            </tr>
                        </thead>
                    ';
                }
                if($IP_machine[$cleTot]['machine']==$IP_machine[$cle]['machine']){
                    echo '
                        <tr>
                           <td>'.$IP_machine[$cle]['interface'].'</td>
                           <td>'.$IP_machine[$cle]['ip_machine'].'</td>
                        </tr>
                    ';
                }else{
                    $cleTot=$cle;
                   
                    echo '</table>';
                    echo '<table class="table table-bordered table-striped table-hover" >
                        <thead>
                            <tr>
                               <th>Interface</th>
                               <th>IP';
			if ( $IP_machine[$cle]['Image'] == 'router' ) {
				echo 's';
			}
			echo ' de '.$IP_machine[$cle]['machine'].'</th>
                            </tr>
                        </thead>
                        <tr>
                           <td>'.$IP_machine[$cle]['interface'].'</td>
                           <td>'.$IP_machine[$cle]['ip_machine'].'</td>
                        </tr>
                    ';
                }
            }
            echo '</table>';
        }catch(PDOException $e){
            echo 'ERREUR '.$e->getCode().' : '.$e->getMessage();
        }
    }
    function afficheNomMachineTable($difficulte,$bdd){
        try{
            $req = $bdd->prepare('SELECT machine FROM routes where Difficulte=:diff');
        	$req->bindValue(":diff",$difficulte);
        	$req->execute();
        	$rep = $req->fetch(PDO::FETCH_ASSOC);
            echo $rep['machine'];
        }catch(PDOException $e){
            echo 'ERREUR '.$e->getCode().' : '.$e->getMessage();
        }
    }
    
    function recupereMachinesFormatJSON($difficulte,$bdd){
        $machines=array();
        try{
            $req_machines = $bdd->prepare('SELECT * FROM machines where Difficulte=:diff');
            $req_machines->bindValue(":diff",$difficulte);
        	$req_machines->execute();
        	while($rep = $req_machines->fetch(PDO::FETCH_ASSOC)){
        	    $machines[$rep['Nom']] = '{"nom":"'.$rep['Nom'].'","img":"'.$rep['Image'].'","x":'.$rep['X'].',"y":'.$rep['Y'].',"width":'.$rep['Width'].',"height":'.$rep['Height'].'}';
        	}
        	return $machines;
            
        }catch(PDOException $e){
            echo 'ERREUR '.$e->getCode().' : '.$e->getMessage();
        }
        return null;
    }
    function recupereBranchementsFormatJSON($difficulte,$bdd){
        $branchements=array();
        try{
            $req_search_machine = $bdd->prepare('SELECT nom as nom_machine FROM machines where id_machine=:id_machine');
        	$req = $bdd->prepare('SELECT * FROM branchements where difficulte=:diff');
        	$req->bindParam(":diff",$difficulte);
        	$req->execute();
        	while($rep = $req->fetch(PDO::FETCH_ASSOC)){
        	    if(($nom_machine_1 = recupereNomMachine($rep['id_machine_1'],$bdd))!=null && ($nom_machine_2 = recupereNomMachine($rep['id_machine_2'],$bdd))){
        	        $branchements[$nom_machine_1.'_'.$nom_machine_2] = '{"m1":"'.$nom_machine_1.'","m2":"'.$nom_machine_2.'","p1":'.$rep['Machine1_port'].',"p2":'.$rep['Machine2_port'].'}';
        	    }
        	}
        	return $branchements;
        }catch(PDOException $e){
            echo 'ERREUR '.$e->getCode().' : '.$e->getMessage();
        }
    	return null;
    }
    function recupereTableRouteCorrectionFormatJSON($difficulte,$bdd){
        $Table_route_cor=array();
        try{
            $recup_gateway = $bdd->prepare('SELECT ip_machine FROM ip_machines where id_ip_machine=:id_ip_machine ');
        	$req = $bdd->prepare('SELECT * FROM table_route where difficulte=:difficulte');
        	$req -> bindValue(":difficulte",$difficulte);
        	$req->execute();
        	while($rep = $req->fetch(PDO::FETCH_ASSOC)){
        	    if($rep['gateway']!=null || $rep['gateway']!=''){
        	        $recup_gateway->bindValue(':id_ip_machine',$rep['gateway']);
        	        $recup_gateway->execute();
        	        if($passe=$recup_gateway->fetch(PDO::FETCH_ASSOC)){
        	            $rep['gateway']=$passe['ip_machine'];
        	        }
        	    }
        	    $Table_route_cor[$rep['destination']] = '{"destination":"'.$rep['destination'].'","gateway":"'.$rep['gateway'].'","mask":"'.$rep['mask'].'","flags":"'.$rep['flags'].'","interface":"'.$rep['interface'].'"}';
        	}
        	return $Table_route_cor;
        }catch(PDOException $e){
            echo 'ERREUR '.$e->getCode().' : '.$e->getMessage();
        }
    	return null;
    }
    function convMaskDecimal($mask) {
	if (substr($mask,0,1)!='/') return($mask);
	$nbBits=substr($mask,1);
	$nbPleins= floor($nbBits/8);
	$reste = $nbBits % 8;
	$maskdec="";
	for ($i=0;$i<$nbPleins;$i++)
		$maskdec=$maskdec + '255.';
	
	return($maskdec);
    }
?>
