<?php
    function analyseTrame($title,$bdd){
        ?>
        <h1>Analyse de trame <div class="sl">(en cours de débogage)</div></h1>
            <div class="panel panel-default" id="exercice">
                <div class="panel-heading">
                    <h3 class="panel-title">Exercice :</h3>
                </div>
                <div class="panel-body">
                    <div class="lead well">
                        Vous devez analyser la trame 
ci-dessous et ne remplir que les champs qui vous semblent nécessaires.</BR>
</BR>

Attention, pour l'instant, pour ICMP et DNS, seuls les entêtes
de ces derniers sont considérés.</BR>

Légende : b: 1 bit, X: 1 hexa, D: 1 nombre décimal, 
attention tous les chiffres sont significatifs !</BR>

<div class="sl">Au moins 1 bug connu : cas particulier de DNS encapsulé dans TCP incorrect.</div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-md-12">
                            <div id="commentaire"></div>
                        </div>
                    </div>
                    <?php
                        $tabVerif='toCollapseEth';
                        $contructeur_mac = array('00000C', '0000A2','0000AA', '00AA00', '08002B','080046');//tableau de différent constructeur mac
                        $etype = array('0800','0800','0800','0806'); //IPv4 & ARP, tableau de choix du type de trame entre ARP et IPv4 (75% d'ip et 25% d'arp, car IPv4 contient différents protocoles)
                        $rand = rand(0,count($etype)-1);//choix de l'etype aléatoirement
                        $opcode_arp = array('0001', '0002'); //ARP-req & ARP-reply
                        $rand_arp = rand(0,count($opcode_arp)-1);//choix du protocole arp aléatoirement
                        $rand_mac = rand(0,count($contructeur_mac)-1);//choix du premier constructeur mac
                        $mac_a = $contructeur_mac[$rand_mac].'012345';//création artificielle de la premiere adresse mac
                        $rand_mac = rand(0,count($contructeur_mac)-1);//choix du deuxième constructeur mac
                        $mac_b = $contructeur_mac[$rand_mac].'678910';//création artificielle de la deuxième adresse mac
                        $arp_req = 'FFFFFFFFFFFF';//adresse de diffusion pour l'arp request
                        $rand_ip = rand(1,254);
                        $ip_a="";
                        $ip_b="";
                        for($i=0;$i<4;$i++){ //Génération des ip aléatoirement
                            $rand_addr=0;
                            if($i==0){
                                $rand_addr = rand(1,240);
                            }
                            else{
                                $rand_addr  = rand(1,254);
                            }
                            if($rand_addr<16){
                                $ip_a .= '0'.dechex($rand_addr);
                            }
                            else{
                                $ip_a.= dechex($rand_addr);
                            }
                        }
                        for($i=0;$i<4;$i++){//Génération des ip aléatoirement
                            $rand_addr=0;
                            if($i==0){
                                $rand_addr = rand(1,240);
                            }
                            else{
                                $rand_addr  = rand(1,254);
                            }
                            if($rand_addr<16){
                                $ip_b .= '0'.dechex($rand_addr);
                            }
                            else{
                                $ip_b.= dechex($rand_addr);
                            }
                        }
						$rand_protocol_ip="";
                        if($rand!=3){ //IPv4
                            $tabVerif.='\',\'toCollapseIp';
                            $trame= $mac_b.$mac_a.$etype[$rand];//DA, SA, etype
                            $trame.= '4500';//N° ver. IP, long. entete, DiffServ
                            setlocale(LC_ALL, 'fr_FR.UTF-8');
                            $identification = strftime('%m%d');//identification
                            $dfmfoffset='4000';// df, dm, offset
                            $rand_ttl= rand(1,255);
                            if($rand_ttl<16){//ttl
                                $ttl='0'.dechex($rand_ttl);
                            }
                            else{
                                $ttl=dechex($rand_ttl);
                            }
                            $protocol = array('01','06','11');//tableau des différents protocoles IPv4: ICMP, TCP, UDP
                            $rand_protocol_ip = rand(0,count($protocol)-1);//choix du type aléatoirement
                            if($rand_protocol_ip==0){ //ICMP
                                $tabVerif.='\',\'toCollapseIcmp';
                                $type_icmp = array('0', '8', '13','14', '15','16');// echo reply, echo request, timestamp, timestamp reply, information request, information reply
                                $rand_icmp = rand(0,count($type_icmp)-1);//choix du type aléatoirement
                                $trame.='00'.dechex(38).$identification.$dfmfoffset.$ttl.$protocol[$rand_protocol_ip].'1234'.$ip_b.$ip_a;//longueur totale, identification, df, dm, offset, ttl, protocole, header checksum, ip a, ip b
                                if($rand_icmp==5){
                                    $trame.=dechex($type_icmp[$rand_icmp]).'001234';//type, code erreur ou 0, checksum
                                }
                                else{
                                    $trame.='0'.dechex($type_icmp[$rand_icmp]).'001234';//type, code erreur ou 0, checksum
                                }
                            }
                            elseif($rand_protocol_ip==1){ //TCP
                                $tabVerif.='\',\'toCollapseTcp';
                                $service_tcp = array('0007', '000D', '0015', '0016', '0019','0025', '0035', '0050', '01BB');//echo, daytime, FTP, SSH, SMTP, Time, DNS, HTTP, HTTPS
                                $rand_port = rand(1,65535);//choix du port destination aléatoirement
                                $rand_port_tcp = rand(0,count($service_tcp)-1);//choix du port source aléatoirement
                                $rand_sd = rand(0,1);//Détermine si le port destination est $rand_port ou $rand_port_tcp
                                if($rand_port_tcp==6){//Si c'est DNS
                                    $tabVerif.='\',\'toCollapseDns';
                                    $trame.='00'.dechex(66).$identification.$dfmfoffset.$ttl.$protocol[$rand_protocol_ip].'0123'.$ip_b.$ip_a;//longueur totale, identification, df, dm, offset, ttl, protocole, header checksum, ip a, ip b
                                    if($rand_sd==0){
                                        $trame.= $service_tcp[$rand_port_tcp];//Port source
                                        if($rand_port<=4095&&$rand_port>255){
                                            $trame.= '0';
                                        }
                                        elseif($rand_port<=255&&$rand_port>15){
                                            $trame.= '00';
                                        }
                                        elseif($rand_port<=15){
                                            $trame.= '000';
                                        }
                                        $trame.=dechex($rand_port);//port destination
                                    }
                                    else{
                                        if($rand_port<=4095&&$rand_port>255){
                                            $trame.= '0';
                                        }
                                        elseif($rand_port<=255&&$rand_port>15){
                                            $trame.= '00';
                                        }
                                        elseif($rand_port<=15){
                                            $trame.= '000';
                                        }
                                        $trame.= dechex($rand_port).$service_tcp[$rand_port_tcp];//port source, port destination
                                    }
                                    $trame.= '0000000100000002500';//Numéro de séquence, Numéro d'acquittement, Long. entête, 000
                                    $tab_flag_tcp = array('1','2','4');
                                    $rand_flag_tcp = rand(0,count($tab_flag_tcp)-1);
                                    $trame.= $tab_flag_tcp[$rand_flag_tcp];//Drapeaux
                                    $rand_taillefen = rand(1,65535);
                                    if($rand_taillefen<=4095&&$rand_taillefen>255){
                                        $trame.= '0';
                                    }
                                    elseif($rand_taillefen<=255&&$rand_taillefen>15){
                                        $trame.= '00';
                                    }
                                    elseif($rand_taillefen<=15){
                                        $trame.= '000';
                                    }
                                    $trame.=dechex($rand_taillefen).'12340000';//taille fenêtre, Checksum, Pointeur urgent
                                    setlocale(LC_ALL, 'fr_FR.UTF-8');
                                    $transid = strftime('%m%d');//Trans ID
                                    $rand_qr = rand(0,1);
                                    $rand_aa = rand(0,1);
                                    $rand_rd = rand(0,1);
                                    $rand_ra = rand(0,1);
                                    $trame.=$transid;//trans ID
                                    if($rand_rd==1){
                                        $flags.=$rand_qr.'0000'.$rand_aa.'0'.$rand_rd.$rand_ra.'0000000';//flags en binaire
                                    }
                                    else{
                                        $flags.=$rand_qr.'0000'.$rand_aa.'0'.$rand_rd.'00000000';//flags en binaire
                                    }
                                    for($i=0;$i<strlen($flags)/4;$i++){
                                        $bin=substr($flags,$i*4,4);
                                        $trame.=dechex(bindec($bin));//flags
                                    }
                                    if($rand_qr==1){
                                        $trame.='00010001';//Nb req(S1), Nb réponses(S2)
                                    }
                                    else{
                                        $trame.='00010000';//Nb req(S1), Nb réponses(S2)
                                    }
                                    if($rand_aa==1){
                                        $trame.='0001';//Nb autorité(S3)
                                    }
                                    else{
                                        $trame.='0000';//Nb autorité(S3)
                                    }
                                    $trame.='0000';//Nb supp(S4)
                                }
                                else{//Si n'est pas DNS
                                    $trame.='00'.dechex(54).$identification.$dfmfoffset.$ttl.$protocol[$rand_protocol_ip].'0123'.$ip_b.$ip_a;//longueur totale, identification, df, dm, offset, ttl, protocole, header checksum, ip a, ip b
                                    if($rand_sd==0){
                                        $trame.= $service_tcp[$rand_port_tcp];//Port source
                                        if($rand_port<=4095&&$rand_port>255){
                                            $trame.= '0';
                                        }
                                        elseif($rand_port<=255&&$rand_port>15){
                                            $trame.= '00';
                                        }
                                        elseif($rand_port<=15){
                                            $trame.= '000';
                                        }
                                        $trame.= dechex($rand_port);//Port destination
                                    }
                                    else{
                                        if($rand_port<=4095&&$rand_port>255){
                                            $trame.= '0';
                                        }
                                        elseif($rand_port<=255&&$rand_port>15){
                                            $trame.= '00';
                                        }
                                        elseif($rand_port<=15){
                                            $trame.= '000';
                                        }
                                        $trame.= dechex($rand_port).$service_tcp[$rand_port_tcp];//Port source, Port destination
                                    }
                                    $trame.= '0000000100000002500';//Numéro de séquence, Numéro d'acquittement, Long. entête, 000
                                    $tab_flag_tcp = array('1','2','4');
                                    $rand_flag_tcp = rand(0,count($tab_flag_tcp)-1);
                                    $trame.= $tab_flag_tcp[$rand_flag_tcp];//Drapeaux
                                    $rand_taillefen = rand(1,65535);
                                    if($rand_taillefen<=4095&&$rand_taillefen>255){
                                        $trame.= '0';
                                    }
                                    elseif($rand_taillefen<=255&&$rand_taillefen>15){
                                        $trame.= '00';
                                    }
                                    elseif($rand_taillefen<=15){
                                        $trame.= '000';
                                    }
                                    $trame.=dechex($rand_taillefen).'12340000';//taille fenêtre, Checksum, Pointeur urgent
                                }
                            }
                            elseif($rand_protocol_ip==2){ //UDP
                                $tabVerif.='\',\'toCollapseUdp';
                                $service_udp = array('0007', '000D', '0025', '0035');//echo, daytime, Time, DNS
                                $rand_port = rand(1,65535);
                                $rand_port_udp = rand(0,count($service_udp)-1);
                                $rand_sd = rand(0,1);//Détermine si le port destination est $rand_port ou $rand_port_udp
                                if($rand_port_udp!=3){//Si ce n'est pas DNS
                                    $trame.='00'.dechex(42).$identification.$dfmfoffset.$ttl.$protocol[$rand_protocol_ip].'0123'.$ip_b.$ip_a;//longueur totale, identification, df, dm, offset, ttl, protocole, header checksum, ip a, ip b
                                    if($rand_sd==0){
                                        $trame.= $service_udp[$rand_port_udp];//Port source
                                        if($rand_port<=4095&&$rand_port>255){
                                            $trame.= '0';
                                        }
                                        elseif($rand_port<=255&&$rand_port>15){
                                            $trame.= '00';
                                        }
                                        elseif($rand_port<=15){
                                            $trame.= '000';
                                        }
                                        $trame.= dechex($rand_port);//Port destination
                                    }
                                    else{
                                        if($rand_port<=4095&&$rand_port>255){
                                            $trame.= '0';
                                        }
                                        elseif($rand_port<=255&&$rand_port>15){
                                            $trame.= '00';
                                        }
                                        elseif($rand_port<=15){
                                            $trame.= '000';
                                        }
                                        $trame.= dechex($rand_port).$service_udp[$rand_port_udp];//Port source, Port destination
                                    }
                                    $trame.= '000'.dechex(8).'1234';//Long. tot. en octets, Checksum
                                }
                                else{//Si c'est DNS
                                    $tabVerif.='\',\'toCollapseDns';
                                    setlocale(LC_ALL, 'fr_FR.UTF-8');
                                    $transid = strftime('%m%d');//Trans ID
                                    $trame.='00'.dechex(50).$identification.$dfmfoffset.$ttl.$protocol[$rand_protocol_ip].'0123'.$ip_b.$ip_a;//longueur totale, identification, df, dm, offset, ttl, protocole, header checksum, ip a, ip b
                                    if($rand_sd==0){
                                        $trame.= $service_udp[$rand_port_udp];//Port source
                                        if($rand_port<=4095&&$rand_port>255){
                                            $trame.= '0';
                                        }
                                        elseif($rand_port<=255&&$rand_port>15){
                                            $trame.= '00';
                                        }
                                        elseif($rand_port<=15){
                                            $trame.= '000';
                                        }
                                        $trame.= dechex($rand_port);//Port destination
                                    }
                                    else{
                                        if($rand_port<=4095&&$rand_port>255){
                                            $trame.= '0';
                                        }
                                        elseif($rand_port<=255&&$rand_port>15){
                                            $trame.= '00';
                                        }
                                        elseif($rand_port<=15){
                                            $trame.= '000';
                                        }
                                        $trame.= dechex($rand_port).$service_udp[$rand_port_udp];//Port source, Port destination
                                    }
                                    $trame.='00'.dechex(20).'1234'.$transid;//Long. tot. en octets, Checksum, Trans ID
                                    $rand_qr = rand(0,1);
                                    $rand_aa = rand(0,1);
                                    $rand_rd = rand(0,1);
                                    $rand_ra = rand(0,1);
                                    $flags='';
                                    if($rand_rd==1){
                                        $flags.=$rand_qr.'0000'.$rand_aa.'0'.$rand_rd.$rand_ra.'0000000';//flags en binaire
                                    }
                                    else{
                                        $flags.=$rand_qr.'0000'.$rand_aa.'0'.$rand_rd.'00000000';//flags en binaire
                                    }
                                    for($i=0;$i<strlen($flags)/4;$i++){
                                        $bin=substr($flags,$i*4,4);
                                        $trame.=dechex(bindec($bin));//flags
                                    }
                                    if($rand_qr==1){
                                        $trame.='00010001';//Nb req(S1), Nb réponses(S2)
                                    }
                                    else{
                                        $trame.='00010000';//Nb req(S1), Nb réponses(S2)
                                    }
                                    if($rand_aa==1){
                                        $trame.='0001';//Nb autorité(S3)
                                    }
                                    else{
                                        $trame.='0000';//Nb autorité(S3)
                                    }
                                    $trame.='0000';//Nb supp(S4)
                                }
                            }
                        }
                        if($rand==3){ //ARP
                            $tabVerif.='\',\'toCollapseArp';
                            if($rand_arp==0){ //ARP-req
                                $trame = $arp_req.$mac_a.$etype[$rand];//DA, Sa, etype
                                $trame.= '000108000604'.$opcode_arp[$rand_arp];//HAS, PAS, HLEN, PLEN, opcode
                                $trame.= $mac_a.$ip_a.$arp_req.$ip_b;//SHA, SPA, THA, TPA
                            }
                            else{ //ARP-reply
                                $trame= $mac_b.$mac_a.$etype[$rand];//DA, Sa, etype
                                $trame.= '000108000604'.$opcode_arp[$rand_arp];//HAS, PAS, HLEN, PLEN, opcode
                                $trame.= $mac_a.$ip_a.$mac_b.$ip_b;//SHA, SPA, THA, TPA
                            }
                        }
                        $trame = strtoupper($trame);//passage en majuscule
                        for($i=0;$i<strlen($trame);$i++){
                            $tab_aff[] = substr($trame, $i*2, 2);
                        }
                        echo '<div class="row"><div class="col-md-12 col-xs-12 col-sm-12"><table class="table table-striped table-bordered">';//Affichage de la trame en tableau
                        $compteur=0;
                        $i=0;
                        echo '<tr><td>';
                        while($compteur<16&&$i<count($tab_aff)){
			    if ($compteur==8) {
				echo ' </td><td>';
			    }
                            if($compteur==15&&$tab_aff[$i]!=null){
                                echo $tab_aff[$i].'</td></tr><tr><td>';
                                $compteur=0;
                            }elseif($tab_aff[$i]!=null){
                                if($tab_aff[$i+1]!=null){
                                    echo $tab_aff[$i].'</td><td>';
                                }
                                else{
                                    echo $tab_aff[$i].'</td>';
                                }
                                $compteur++;
                            }
                            $i++;
                        }
                        echo '</td></tr>';
                        echo '</table></div></div>';
                    ?>
                    <!-- Affichage des différents champs -->
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" href="#toCollapseEth">
                            <h3 class="panel-title">Trame Ethernet : <i>Cliquez pour afficher/masquer</i></h3>
                        </div>
                        <div class="panel-body collapse" id="toCollapseEth">
                            <table class="col-xs-12 col-md-12">
                                <tr>
                                    <td>
                                        <div class="col-xs-4 col-md-4">
                                            <div class="col-xs-12 col-md-12 input-group" id="da">
                                                <div class="input-group-addon">DA : </div>
                                                <input type="text" class="form-control" placeholder="XX:XX:XX:XX:XX:XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-md-4">
                                            <div class="col-xs-12 col-md-12 input-group" id="sa">
                                                <div class="input-group-addon">SA : </div>
                                                <input type="text" class="form-control" placeholder="XX:XX:XX:XX:XX:XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-md-4">
                                            <div class="col-xs-12 col-md-12 input-group" id="etype">
                                                <div class="input-group-addon">DL/ETYPE : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" href="#toCollapseArp">
                            <h3 class="panel-title">Paquet ARP : <i>Cliquez pour afficher/masquer</i></h3>
                        </div>
                        <div class="panel-body collapse" id="toCollapseArp">
                            <table class="col-xs-12 col-md-12 table">
                                <tr>
                                    <td>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="has">
                                                <div class="input-group-addon">Hardware Address Space : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="pas">
                                                <div class="input-group-addon">Protocol Address Space : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group" id="hlen">
                                                <div class="input-group-addon">HLEN : </div>
                                                <input type="text" class="form-control" placeholder="XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group" id="plen">
                                                <div class="input-group-addon">PLEN : </div>
                                                <input type="text" class="form-control" placeholder="XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="opcode">
                                                <div class="input-group-addon">Opcode : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="sha">
                                                <div class="input-group-addon">Sender Hardware Address : </div>
                                                <input type="text" class="form-control" placeholder="XX:XX:XX:XX:XX:XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="spa">
                                                <div class="input-group-addon">Sender Protocol Address : </div>
                                                <input type="text" class="form-control" placeholder="D.D.D.D">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="tha">
                                                <div class="input-group-addon">Target Hardware Address : </div>
                                                <input type="text" class="form-control" placeholder="XX:XX:XX:XX:XX:XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="tpa">
                                                <div class="input-group-addon">Target Protocol Address : </div>
                                                <input type="text" class="form-control" placeholder="D.D.D.D">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" href="#toCollapseIp">
                            <h3 class="panel-title">Paquet IPv4 : <i>Cliquez pour afficher/masquer</i></h3>
                        </div>
                        <div class="panel-body collapse" id="toCollapseIp">
                            <table class="col-xs-12 col-md-12 table">
                                <tr>
                                    <td>
                                       <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group" id="numIP">
                                                <div class="input-group-addon">N° ver. IP : </div>
                                                <input type="text" class="form-control" placeholder="X">
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group"id="longEnteteIP">
                                                <div class="input-group-addon">Long. entête : </div>
                                                <input type="text" class="form-control" placeholder="X">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="diffserv">
                                                <div class="input-group-addon">DiffServ : </div>
                                                <input type="text" class="form-control" placeholder="XX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="longTotIP">
                                                <div class="input-group-addon">Long. tot. en octets : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="identification">
                                                <div class="input-group-addon">Identification : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="0">
                                                <div class="input-group-addon">0 : </div>
                                                <input type="text" class="form-control" placeholder="x">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="df">
                                                <div class="input-group-addon">DF : </div>
                                                <input type="text" class="form-control" placeholder="x">
                                            </div>
                                        </div>
                                        <div class="col-xs-2 col-md-2">
                                            <div class="col-xs-12 col-md-12 input-group" id="mf">
                                                <div class="input-group-addon">MF : </div>
                                                <input type="text" class="form-control" placeholder="x">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="offset">
                                                <div class="input-group-addon">Offset : </div>
                                                <input type="text" class="form-control" placeholder="bXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group" id="ttl">
                                                <div class="input-group-addon">TTL : </div>
                                                <input type="text" class="form-control" placeholder="XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group" id="protocole">
                                                <div class="input-group-addon">Protocole : </div>
                                                <input type="text" class="form-control" placeholder="XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="hc">
                                                <div class="input-group-addon">Header Checksum : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="ipE">
                                                <div class="input-group-addon">Adresse IP émetteur : </div>
                                                <input type="text" class="form-control" placeholder="D.D.D.D">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="ipD">
                                                <div class="input-group-addon">Adresse IP destinataire : </div>
                                                <input type="text" class="form-control" placeholder="D.D.D.D">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" href="#toCollapseUdp">
                            <h3 class="panel-title">Datagramme UDP : <i>Cliquez pour afficher/masquer</i></h3>
                        </div>
                        <div class="panel-body collapse" id="toCollapseUdp">
                            <table class="col-xs-12 col-md-12 table">
                                <tr>
                                    <td>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="portSUDP">
                                                <div class="input-group-addon">Port source : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="portDUDP">
                                                <div class="input-group-addon">Port destination : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="longTotUDP">
                                                <div class="input-group-addon">Long. tot. en octets : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="checksumUDP">
                                                <div class="input-group-addon">Checksum : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" href="#toCollapseIcmp">
                            <h3 class="panel-title">Entête Paquet ICMP : <i>Cliquez pour afficher/masquer</i></h3>
                        </div>
                        <div class="panel-body collapse" id="toCollapseIcmp">
                            <table class="col-xs-12 col-md-12 table">
                                <tr>
                                    <td>
                                        <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group" id="type">
                                                <div class="input-group-addon">Type : </div>
                                                <input type="text" class="form-control" placeholder="XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group" id="codeErreur">
                                                <div class="input-group-addon">0 ou code erreur : </div>
                                                <input type="text" class="form-control" placeholder="XX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="checksumICMP">
                                                <div class="input-group-addon">Checksum : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" href="#toCollapseTcp">
                            <h3 class="panel-title">Segment TCP : <i>Cliquez pour afficher/masquer</i></h3>
                        </div>
                        <div class="panel-body collapse" id="toCollapseTcp">
                            <table class="col-xs-12 col-md-12 table">
                                <tr>
                                    <td>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="portSTCP">
                                                <div class="input-group-addon">Port source : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="portDTCP">
                                                <div class="input-group-addon">Port destination : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="numSeq">
                                                <div class="input-group-addon">Numéro de séquence : </div>
                                                <input type="text" class="form-control" placeholder="XXXXXXXX">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="numAcq">
                                                <div class="input-group-addon">Numéro d'acquittement : </div>
                                                <input type="text" class="form-control" placeholder="XXXXXXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group" id="longEntete">
                                                <div class="input-group-addon">Long. entête : </div>
                                                <input type="text" class="form-control" placeholder="X">
                                            </div>
                                        </div>
                                        <div class="col-xs-3 col-md-3">
                                            <div class="col-xs-12 col-md-12 input-group" id="000">
                                                <div class="input-group-addon">000 : </div>
                                                <input type="text" class="form-control" placeholder="bbb">
                                            </div>
                                        </div>
                                        <div class="col-xs-6 col-md-6">
                                            <div class="col-xs-12 col-md-12 input-group" id="drapeaux">
                                                <div class="input-group-addon">Drapeaux : </div>
                                                <input type="text" class="form-control" placeholder="bXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="col-xs-4 col-md-4">
                                            <div class="col-xs-12 col-md-12 input-group" id="tailleFen">
                                                <div class="input-group-addon">TailleFenêtre : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-md-4">
                                            <div class="col-xs-12 col-md-12 input-group" id="checksumTCP">
                                                <div class="input-group-addon">Checksum : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                        <div class="col-xs-4 col-md-4">
                                            <div class="col-xs-12 col-md-12 input-group" id="pointeur">
                                                <div class="input-group-addon">Pointeur urgent : </div>
                                                <input type="text" class="form-control" placeholder="XXXX">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="panel panel-default">
                                            <div class="panel-heading" data-toggle="collapse" href="#toCollapseDTcp">
                                                <h3 class="panel-title">Drapeaux TCP : <i>Cliquez pour afficher/masquer</i></h3>
                                            </div>
                                            <div class="panel-body collapse" id="toCollapseDTcp">
                                                <table class="col-xs-12 col-md-12 table">
                                                    <tr>
                                                        <td>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="ns">
                                                                    <div class="input-group-addon">NS : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="cwr">
                                                                    <div class="input-group-addon">CWR : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="ece">
                                                                    <div class="input-group-addon">ECE : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="urg">
                                                                    <div class="input-group-addon">URG : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="ack">
                                                                    <div class="input-group-addon">ACK : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="psh">
                                                                    <div class="input-group-addon">PSH : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="rst">
                                                                    <div class="input-group-addon">RST : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="syn">
                                                                    <div class="input-group-addon">SYN : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="fin">
                                                                    <div class="input-group-addon">FIN : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading" data-toggle="collapse" href="#toCollapseDns">
                                <h3 class="panel-title">Entête Message DNS : <i>Cliquez pour afficher/masquer</i></h3>
                            </div>
                            <div class="panel-body collapse" id="toCollapseDns">
                                <table class="col-xs-12 col-md-12 table">
                                    <tr>
                                        <td>
                                            <div class="col-xs-6 col-md-6">
                                                <div class="col-xs-12 col-md-12 input-group" id="transID">
                                                    <div class="input-group-addon">Trans ID : </div>
                                                    <input type="text" class="form-control" placeholder="XXXX">
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6">
                                                <div class="col-xs-12 col-md-12 input-group" id="flags">
                                                    <div class="input-group-addon">Drapeaux : </div>
                                                    <input type="text" class="form-control" placeholder="XXXX">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-xs-6 col-md-6">
                                                <div class="col-xs-12 col-md-12 input-group" id="nbReq">
                                                    <div class="input-group-addon">Nb req(S1) : </div>
                                                    <input type="text" class="form-control" placeholder="XXXX">
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6">
                                                <div class="col-xs-12 col-md-12 input-group" id="nbRep">
                                                    <div class="input-group-addon">Nb réponses(S2) : </div>
                                                    <input type="text" class="form-control" placeholder="XXXX">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="col-xs-6 col-md-6">
                                                <div class="col-xs-12 col-md-12 input-group" id="nbAut">
                                                    <div class="input-group-addon">Nb autorité(S3) : </div>
                                                    <input type="text" class="form-control" placeholder="XXXX">
                                                </div>
                                            </div>
                                            <div class="col-xs-6 col-md-6">
                                                <div class="col-xs-12 col-md-12 input-group" id="nbSupp">
                                                    <div class="input-group-addon">Nb supp(S4) : </div>
                                                    <input type="text" class="form-control" placeholder="XXXX">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <div class="panel panel-default">
                                            <div class="panel-heading" data-toggle="collapse" href="#toCollapseFDns">
                                                <h3 class="panel-title">Drapeaux DNS : <i>Cliquez pour afficher/masquer</i></h3>
                                            </div>
                                            <div class="panel-body collapse" id="toCollapseFDns">
                                                <table class="col-xs-12 col-md-12 table">
                                                    <tr>
                                                        <td>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="qr">
                                                                    <div class="input-group-addon">QR : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="opcodeDNS">
                                                                    <div class="input-group-addon">Opcode : </div>
                                                                    <input type="text" class="form-control" placeholder="bbbb">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="aa">
                                                                    <div class="input-group-addon">AA : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="tc">
                                                                    <div class="input-group-addon">TC : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="rd">
                                                                    <div class="input-group-addon">RD : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="ra">
                                                                    <div class="input-group-addon">RA : </div>
                                                                    <input type="text" class="form-control" placeholder="b">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="000DNS">
                                                                    <div class="input-group-addon">000 : </div>
                                                                    <input type="text" class="form-control" placeholder="bbb">
                                                                </div>
                                                            </div>
                                                            <div class="col-xs-2 col-md-2">
                                                                <div class="col-xs-12 col-md-12 input-group" id="rcode">
                                                                    <div class="input-group-addon">RCODE : </div>
                                                                    <input type="text" class="form-control" placeholder="bbbb">
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="button" class="btn btn-success col-md-4 col-md-offset-4 col-xs-12" id="valider" value="Valider"/><!-- Bouton permettant la validation du formulaire -->
                            <a id="button" href='' hidden><input type='button' class='btn btn-warning  col-md-4 col-md-offset-4 col-xs-12' id='recommencer' value='Recommencer'/></a> <!-- Bouton permettant de recommencer l'exercice -->
                        </div>
                    </div>
                </div>
            </div>
        <?php
        $valReturn=array($trame,$tabVerif,$rand_protocol_ip);
        return  $valReturn;
    }
    
    function script_analyseTrame($trame,$tabVerif,$rand_protocol_ip){
        ?>
        <script>
            jQuery( document ).ready(function() {
            $("#valider").click(function(event) {
                event.preventDefault();
                $(".glyphicon").remove();
                var eth = new Array("da","sa","etype");//tableau des champs du protocole ethernet
                var arp = new Array("has","pas","hlen","plen","opcode","sha","spa","tha","tpa");//tableau des champs du protocole arp
                var ipv4 = new Array("numIP","longEnteteIP","diffserv","longTotIP","identification","0","df","mf","offset","ttl","protocole","hc","ipE","ipD");//tableau des champs du protocole ipv4
                var udp = new Array("portSUDP","portDUDP","longTotUDP","checksumUDP");//tableau des champs du protocole udp
                var icmp = new Array("type","codeErreur","checksumICMP");//tableau des champs du protocole ethernet
                var tcp = new Array("portSTCP","portDTCP","numSeq","numAcq","longEntete","000","drapeaux","tailleFen","checksumTCP","pointeur","ns","cwr","ece","urg","ack","psh","rst","syn","fin");//tableau des champs du protocole tcp
                var drapeauxTCP = new Array("ns","cwr","ece","urg","ack","psh","rst","syn","fin");//tableau des champs des drapeaux tcp
                var flagsDNS = new Array("qr","opcodeDNS","aa","tc","rd","ra","000DNS","rcode");//tableau des champs du protocole des drapeaux DNS
                var dns = new Array("transID","flags","nbReq","nbRep","nbAut","nbSupp","qr","opcodeDNS","aa","tc","rd","ra","000DNS","rcode");//tableau des champs du protocole dns
                var type = new Array("toCollapseEth",eth,"toCollapseArp",arp,"toCollapseIp",ipv4,"toCollapseUdp",udp,"toCollapseIcmp",icmp,"toCollapseTcp",tcp,"toCollapseDTcp",drapeauxTCP,"toCollapseDns",dns,"toCollapseFDns",flagsDNS);//tableau de tableau regroupant les tableaux des différents protocoles énoncés precedemment
                var tab = '<?php echo $trame; ?>';//tableau contenant la trame
                var tabVerif = new Array('<?php echo $tabVerif; ?>');//tableau contenant la liste des protocoles présents dans la trame sous la forme: "protocole 1", "protocole 2"
                var trame = new Array('da',0,12,':','sa',12,12,':','etype',24,4,'','has',28,4,'','pas',32,4,'','hlen',36,2,'','plen',38,2,'','opcode',40,4,'','sha',44,12,':','spa',56,8,'.','tha',64,12,':','tpa',76,8,'.','numIP',28,1,'','longEnteteIP',29,1,'','diffserv',30,2,'','longTotIP',32,4,'','identification',36,4,'','0',40,1,'','df',40,1,'','mf',40,1,'','offset',40,4,'','ttl',44,2,'','protocole',46,2,'','hc',48,4,'','ipE',52,8,'.','ipD',60,8,'.','portSUDP',68,4,'','portDUDP',72,4,'','longTotUDP',76,4,'','checksumUDP',80,4,'','type',68,2,'','codeErreur',70,2,'','checksumICMP',72,4,'','portSTCP',68,4,'','portDTCP',72,4,'','numSeq',76,8,'','numAcq',84,8,'','longEntete',92,1,'','000',93,1,'','drapeaux',93,3,'','ns',93,1,'','cwr',94,1,'','ece',94,1,'','urg',94,1,'','ack',94,1,'','psh',95,1,'','rst',95,1,'','syn',95,1,'','fin',95,1,'','tailleFen',96,4,'','checksumTCP',100,4,'','pointeur',104,4,''<?php if($rand_protocol_ip==2): echo ',"transID",84,4,"","flags",88,4,"","nbReq",92,4,"","nbRep",96,4,"","nbAut",100,4,"","nbSupp",104,4,"","qr",88,1,"","opcodeDNS",88,2,"","aa",89,1,"","tc",89,1,"","rd",89,1,"","ra",90,1,"","000DNS",90,1,"","rcode",91,1,""'; elseif($rand_protocol_ip==1): echo ',"transID",108,4,"","flags",112,4,"","nbReq",116,4,"","nbRep",120,4,"","nbAut",124,4,"","nbSupp",128,4,"","qr",112,1,"","opcodeDNS",112,2,"","aa",113,1,"","tc",113,1,"","rd",113,1,"","ra",114,1,"","000DNS",114,1,"","rcode",115,1,""'; endif; ?>);//tableau permettant l'accès aux différents champs par leurs noms. Structure: 'nom du champ',débutValeurChamp,longueurChamp,'séparateur'
                for(var i=0;i<trame.length/4;i++){
                    var champ = $("#"+trame[i*4]+" input").val();//on récupere la réponse de l'utilisateur
                    var split = new Array();
                    if(champ!=''){
                        if(trame[i*4+3]!=''){
                            var split = champ.split(trame[i*4+3]);
                        }
                        else{
                            split=champ;
                        }
                        var compare = "";
                        for(var j=0;j<split.length;j++){
                            if(trame[i*4]=='spa'){
                                compare+=parseInt(split[j],10);
                            }
                            else{
                                compare+=split[j];
                            }
                        }
                        var compareTo = "";
                        if(trame[i*4]=='spa' || trame[i*4]=='tpa' ||trame[i*4]=='ipE' ||trame[i*4]=='ipD' ){//Si le champ actuel est l'un dans la liste, on convertit la réponse en hexadécimal (car les adresses sont données sous la forme X.X.X.X en décimal)
                            var nombre = "";
                            for(var k=trame[i*4+1];k<trame[i*4+1]+trame[i*4+2];k+=2){
                                if((tab[k]!=null && tab[k+1]!=null) || (tab[k]!="" && tab[k+1]!="")){
                                    nombre = tab[k]+tab[k+1];
                                    compareTo+= parseInt(nombre,16);//conversion
                                }
                            }
                        }
                        else if(trame[i*4]=='000'|| trame[i*4]=='0'||trame[i*4]=='df' ||trame[i*4]=='mf' ||trame[i*4]=='offset' ||trame[i*4]=='drapeaux'||trame[i*4]=='ns' || trame[i*4]=='cwr' || trame[i*4]=='ece' || trame[i*4]=='urg'|| trame[i*4]=='ack'|| trame[i*4]=='psh'|| trame[i*4]=='rst'|| trame[i*4]=='syn'|| trame[i*4]=='fin'|| trame[i*4]=='qr'|| trame[i*4]=='opcodeDNS'|| trame[i*4]=='000DNS'||trame[i*4]=='aa'|| trame[i*4]=='tc'|| trame[i*4]=='rd'|| trame[i*4]=='ra'|| trame[i*4]=='rcode'){//Si le champ actuel est l'un dans la liste, on convertit la réponse en binaire car les valeurs sont données en décimal
                            var bin = new Array('000',0,3,'0',0,1,'df',1,1,'mf',2,1,'offset',3,1,'drapeaux',3,1,'ns',3,1,'cwr',0,1,'ece',1,1,'urg',2,1,'ack',3,1,'psh',0,1,'rst',1,1,'syn',2,1,'fin',3,1,'qr',0,1,'aa',1,1,'tc',2,1,'rd',3,1,'ra',0,1,'opcodeDNS',1,4,'rcode',0,4,'000DNS',1,3);
                            var nombre = "";
                            var sommeTmp="";
                            for(var k=trame[i*4+1];k<trame[i*4+1]+trame[i*4+2];k++){
                                if(tab[k]!=null && tab[k]!=""){
                                    nombre = tab[k];
                                    for(var l=0;l<bin.length/3;l++){
                                        if(trame[i*4]==bin[3*l]){
                                            var tmp=parseInt(nombre).toString(2);//conversion
                                            while(tmp.length<4){
                                                tmp = '0'+tmp;
                                            }
                                            sommeTmp+=tmp;
                                            for(var m=0;m<bin[3*l+2];m++){
                                                if(bin[3*l]!='offset'&&bin[3*l]!='drapeaux'){
                                                    if(sommeTmp.length>=bin[3*l+1]+bin[3*l+2]){
                                                        compareTo+= sommeTmp[bin[3*l+1]+m];
                                                    }
                                                }
                                                else if(bin[3*l]=='offset'||bin[3*l]=='drapeaux'){
                                                    if(sommeTmp.length<=bin[3*l+1]+bin[3*l+2]){
                                                        compareTo+=sommeTmp[bin[3*l+1]+m];
                                                    }
                                                    else{
                                                        compareTo+=parseInt(sommeTmp.substr(sommeTmp.length-4,sommeTmp.length),2);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        else{
                            for(var k=trame[i*4+1];k<trame[i*4+1]+trame[i*4+2];k++){
                                if(tab[k]!=null || tab[k]!=""){
                                    compareTo+= tab[k];
                                }
                            }
                        }
                        //alert(compare+':'+compareTo);
                        if($("#"+trame[i*4]).hasClass("has-warning")){
                                $("#"+trame[i*4]).removeClass("has-warning");
                        }
                        if(compare==compareTo){//comparaison entre la valeur saisie et la correction
                            if($("#"+trame[i*4]).hasClass("has-error")){
                                $("#"+trame[i*4]).removeClass("has-error");
                                $("#"+trame[i*4]).addClass("has-success");
                            }
                            else if(!$("#"+trame[i*4]).hasClass("has-success")){
                                $("#"+trame[i*4]).addClass("has-success");
                            }
                            $("#"+trame[i*4]).append('<span class="glyphicon glyphicon-ok form-control-feedback " aria-hidden="true"></span>');
                        }
                        else{
                            if($("#"+trame[i*4]).hasClass("has-success")){
                                $("#"+trame[i*4]).removeClass("has-success");
                                $("#"+trame[i*4]).addClass("has-error");
                            }
                            else if(!$("#"+trame[i*4]).hasClass("has-error")){
                                $("#"+trame[i*4]).addClass("has-error");
                            }
                            $("#"+trame[i*4]).append('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
                        }
                    }
                    else{
                        if($("#"+trame[i*4]).hasClass("has-error")){
                            $("#"+trame[i*4]).removeClass("has-error");
                        }
                        if($("#"+trame[i*4]).hasClass("has-success")){
                            $("#"+trame[i*4]).removeClass("has-success");
                        }
                    }
                }
                var compteurVerif=0;
                var compteurErreur=0;
                var compteurVide=0;
                for(var j=0;j<type.length/2;j++){
                    var compteur=0;
                    var erreur=0;
                    var vide=0;
                    for(var k=0;k<type[j*2+1].length;k++){//On vérifie les champs valides, invalides, ou vides
                        if($("#"+type[j*2+1][k]).hasClass("has-success")){
                            compteur++;
                            compteurVerif++;
                        }
                        else if($("#"+type[j*2+1][k]).hasClass("has-error")){
                            erreur++;
                            compteurErreur++;
                        }
                        else{
                            vide++;
                            compteurVide++;
                        }
                    }
                    for(var k=0;k<type[j*2+1].length;k++){
                        if(compteur>0||erreur>0){
                            if(!$("#"+type[j*2+1][k]).hasClass("has-success")&&!$("#"+type[j*2+1][k]).hasClass("has-error")){
                                $("#"+type[j*2+1][k]).addClass("has-warning");
                                $("#"+type[j*2+1][k]).append('<span class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>');
                            }
                        }
                    }
                    $("#"+type[j*2]).parent().removeClass("panel-success");
                    $("#"+type[j*2]).parent().removeClass("panel-danger");
                    $("#"+type[j*2]).parent().removeClass("panel-warning");
                    if(erreur>0){
                        $("#"+type[j*2]).parent().addClass("panel-danger");
                    }
                    else if(compteur==type[j*2+1].length){
                        $("#"+type[j*2]).parent().addClass("panel-success");
                    }
                    else if((compteur>0 && compteur<type[j*2+1].length)){
                        $("#"+type[j*2]).parent().addClass("panel-warning");
                    }
                }
                var verif=0;
                for(var l=0;l<tabVerif.length;l++){
                    if($("#"+tabVerif[l]).parent().hasClass("panel-success")){
                        verif++;
                    }
                }
                $('#commentaire').children().remove();
                var prefix;
                var message;
                if(compteurErreur>0){//S'il y a des erreurs on notifie l'utilisateur par un alert
                    prefix="<div class='alert alert-danger alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>";
                }
                else if(verif==tabVerif.length){//S'il n'y a pas d'erreur on notifie l'utilisateur par un alert
                    prefix="<div class='alert alert-success alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>";
                }
                else{//Sinon on notifie l'utilisateur d'un autre message d'erreur
                    prefix="<div class='alert alert-warning alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>";
                }
                if(compteurErreur==0){
                    if(verif==tabVerif.length){//Si le nombre de champs valide est égal au nombre de champ du tableau de la correction
                        $('#commentaire').parent().parent().removeClass("panel-danger");
                        $('#commentaire').parent().parent().removeClass("panel-warning");
                        message="Bravo ! L'exercice a bien été réussi !";
                        $('#commentaire').append(prefix+message+"</strong></div>");
                        $('#commentaire').parent().parent().addClass("panel-success");
                        document.getElementById('button').hidden = false;//On affiche le bouton recommencer
                        verif++;
                        //Ajout d'un exercice réussi dans la base de données
                        $.post("../ressources/function/fonction_exercice_fait_ajax.php",
                        {
                            UserID: <?php if(!empty($_SESSION['connect'])) echo $_SESSION['connect'];else echo -1; ?>,
                            nom: "Analyse de trame",
                            isSuccess: "1"
                        },
                        function(data, status){
                           
                        });
                    }
                    else if(verif<tabVerif.length&&compteurVide>0){
                        $('#commentaire').parent().parent().removeClass("panel-danger");
                        $('#commentaire').parent().parent().removeClass("panel-success");
                        $('#commentaire').parent().parent().removeClass("panel-warning");
                        message="Il faut remplir les champs vides !";
                        $('#commentaire').append(prefix+message+"</strong></div>");
                    }
                }
                else{
                    $('#commentaire').parent().parent().removeClass("panel-success");
                    $('#commentaire').parent().parent().removeClass("panel-warning");
                    message="Il y a une ou plusieurs erreur(s) !";
                    $('#commentaire').append(prefix+message+"</strong></div>");
                    $('#commentaire').parent().parent().addClass("panel-danger");
                    //Ajout d'un exercice non réussi dans la base de données
                    $.post("../ressources/function/fonction_exercice_fait_ajax.php",
                        {
                            UserID: <?php if(!empty($_SESSION['connect'])) echo $_SESSION['connect'];else echo -1; ?>,
                            nom: "Analyse de trame",
                            isSuccess: "-1"
                        },
                        function(data, status){
                    });
                }
            });
            });
        </script>
        <?php
    }
?>
