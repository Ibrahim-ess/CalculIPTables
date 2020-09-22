<?php 
    $difficulte=rand(1,6);
    $title="Table de routage";
    //require('../ressources/connectBDD.php'); 
    require('../ressources/header.php'); 
    require('../ressources/function/function_table_routage.php');
    
    if (isset($_SESSION['connect'])) 
	$connect=$_SESSION['connect'] ;
    else
	$connect='';
    $routeDe;
    $IP_machine=array();
    $Table_route_cor=array();
    generer_ip($difficulte,$bdd);
    //Les 2 prochaines variables auront une valeur au format JSON. Cela sera utilisé en javascript
    $machines=recupereMachinesFormatJSON($difficulte,$bdd); //On recupère dans la base de données les informations sur les machines.
    $branchements=recupereBranchementsFormatJSON($difficulte,$bdd); //On recupère dans la base de données les informations sur les branchements entre machines.
?>
<div class="container">
    <h1>Table de routage (cas <?=$difficulte?>)</h1>
    <div class="panel panel-default">
        <div class="panel-heading">
                <h3 class="panel-title">Exercice</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6 col-md-offset-1 col-xs-12 well table-responsive">
                    <canvas id="canvasNetwork" class="col-md-10 " width=500px height=500px>Votre navigateur ne supporte pas les canvas, désolé !</canvas>
                </div>   
            
                <div class="col-md-4 col-md-offset-1 col-xs-12">
                    <?php
                        afficheTableauIp($difficulte,$bdd);
                    ?>
                    
                </div>
            </div>
            <div class="panel panel-primary">
                <div class="panel-heading text-center">
                    <h4>Table de routage de <?php afficheNomMachineTable($difficulte,$bdd); ?></h4>
                </div>
                <div class="panel-body">
                    
                    <div class="row">
                        <div class=" col-xs-offset-0 col-xs-12">
                            <p class="lead text-center bg-info" 
style="padding:15px">On notera l'interface locale
<b>lo</b> : <var>127.0.0.1</var> 
et on respectera la notion de classe. Les masques doivent être notés en décimal.</p>
                        </div>
                    </div>
                        
                    <div id="correction-alert">
                
                    </div>
                    
                
                <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover " id="table_route">
                            <thead>
                                <tr>
                                    <th class="col-xs-3">
                                        Destination    
                                    </th>
                                    <th class="col-xs-3">
                                        Gateway
                                    </th>
                                    <th class="col-xs-3">
                                        Mask
                                    </th>
                                    <th class="col-xs-1">
                                        Flags
                                    </th>
                                    <th class="col-xs-2">
                                        Interface
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="text-center ">
                            </tbody>
                        </table>
                </div></div>
                <div class="panel-footer">
                    <div id="input-alert">
                        
                    </div>
                    <form class="form-inline row container-fluid">
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3">Destination</label>
                            <input type="text" name="dest" id="dest" class="form-control" placeholder="Destination"/>
                        </div> 
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3">Gateway</label>
                            <input type="text" name="gate" id="gate" class="form-control" placeholder="Gateway"/>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3">Mask</label>
                            <input type="text" name="mask" id="mask" class="form-control" placeholder="Mask"/>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3">Flags</label>
                            <input type="text" name="flags" id="flags" class="form-control" placeholder="Flags"/>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail3">Interface</label>
                            <input type="text" name="interf" id="interf" class="form-control " placeholder="Interface"/>
                        </div>
                        <div class="form-group">
                        <button id="valider_ligne" class="btn btn-info">Valider la ligne</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <button id="correction" class="btn btn-success col-xs-12">Valider la table</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-md-offset-4">
                        <button id="show-correction" class="btn btn-primary col-xs-12">Voir la correction</button>
                    </div>
                </div>
		<div class="row">
		    <div class="col-md-4 col-md-offset-4">
		<button id="retry" class="btn btn-warning col-xs-12">Autre exercice de routage</button>
		    </div>
		</div>
            </div>
            <div class="panel panel-danger hidden">
                <div class="panel-heading text-center">
                    <h4>Correction table de routage de <?php afficheNomMachineTable($difficulte,$bdd); ?></h4>
                </div>
                <div class="panel-body">
                    
                    <table id="table_corr" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>
                                    Destination
                                </th>
                                <th>
                                    Gateway
                                </th>
                                <th>
                                    Mask
                                </th>
                                <th>
                                    Flags
                                </th>
                                <th>
                                    Interface
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
</div>
<?php 
    require('../ressources/footer.php'); 
?>
<link rel="prefetch" href="/CalculIP/Exercices/images/net.png">
<link rel="prefetch" href="/CalculIP/Exercices/images/router.png">
<link rel="prefetch" href="/CalculIP/Exercices/images/computer.png">
<?php
    /* On récupère la table de routage
        Toutes les tables de routage ont une ligne pour l'interface locale que l'on ajoute directement
        Ensuite on récupère le reste de la correction dans la base de donnée
    */
    $Table_route_cor['127.0.0.0'] = '{"destination":"127.0.0.0","gateway":"","mask":"255.0.0.0","flags":"U","interface":"lo"}';
	$Table_route_cor+=recupereTableRouteCorrectionFormatJSON($difficulte,$bdd);
	
	
?>
<script type="text/javascript" src="js/creationReseaux.js"></script>
<script>
/*
    Cette fonction sera instanciée à chaque fois que l'utilisateur ajoute une ligne à sa table
*/
function LigneRoute(dest,gate,mask,flags,interf,id) {
      this.dest=dest;
      this.gate=gate;
      this.mask=mask;
      this.flags=flags;
      this.interf=interf;
      this.id=id;
}

   

jQuery( document ).ready(function() {
    /*construireMachine(100,0,100,100,'net',0,1,0,0,'');
    construireLigne(150,100,155,150);
    construireMachine(80,150,150,70,'router',1,2,0,0,'R 1');
    construireLigne(115,220,75,250);
    construireMachine(0,250,150,150,'computer',1,0,0,0,'M 1');
    construireLigne(195,220,235,250);
    construireMachine(160,250,150,150,'computer',1,0,0,0,'M 2');*/
    
    var reseaux= new Array;
    var branchements=new Array;
    var tableRouteReponse=new Array;
    
    var tableauMachines = <?php echo json_encode($machines); ?>; //on recupere les informations sur les machines et on les encode au format JSON
    /*
        On dessine dans le canvas toutes les machines du réseau
    */
    for(var i in tableauMachines){
        var tab=JSON.parse(tableauMachines[i]);
        reseaux[tab.nom]=new Machine(tab.x,tab.y,tab.width,tab.height,tab.img,tab.nom);
        drawMachine(reseaux[tab.nom],"canvasNetwork");
    }
    
    var tableauBranchements = <?php echo json_encode($branchements); ?>; //on recupere les informations sur les branchements des machines
    /*
        On relie les machines entre elles, selon les informations que l'on a dans le tableau
    */
    for(var i in tableauBranchements){
        var tab=JSON.parse(tableauBranchements[i]);
        branchements[i]=new relierDeuxPoints(reseaux[tab.m1],reseaux[tab.m2],tab.p1,tab.p2);
        drawLigne(branchements[i],"canvasNetwork");
    }
    
    /*
        On recupere la correction de la table de routage au format JSON et on stock les lignes dans un tableau.
        Ce tableau sera comparé à la fin avec celui de l'utilisateur.
    */
    var tableauRoutes = <?php echo json_encode($Table_route_cor); ?>;
    var id=0;
    for(var i in tableauRoutes){
        var tab=JSON.parse(tableauRoutes[i]);
        tableRouteReponse[id]=new LigneRoute(tab.destination,tab.gateway,tab.mask,tab.flags,tab.interface,id);
        id++;
    }
    var tableRoute=new Array;
    var ligne=0;
    /*
        Permet de supprimer les messages d'erreurs du formulaire pour ajouter une ligne
    */
    $('input[type=text]').click(function() {
        var id_click=this.id;
        $('#'+id_click).parent().removeClass('has-error');
        
    });
    /*
        Cette partie est appelée lorsque l'on valide une ligne.
        Cela vérifie qu'au moins un input est rempli. Cela vérifie aussi les valeurs entrées.
    */
    $("#valider_ligne").click(function( event ) {
        event.preventDefault();
        $(".alert").remove();
        $('input[type=text]').parent().removeClass('has-error');
        $('#correction-alert').parent().parent().removeClass("panel-success");
        var dest=$("#dest").val();
        var gate=$("#gate").val();
        var mask=$("#mask").val();
        var flags=$("#flags").val();
        var interf=$("#interf").val();
        
        var champs_val=true;
        /*
            on enlève les espaces pour chaque valeur
        */
        dest=dest.replace(/[\s]*/img,"");
        gate=gate.replace(/[\s]*/img,"");
        mask=mask.replace(/[\s]*/img,"");
        flags=flags.replace(/[\s]*/img,"");
        interf=interf.replace(/[\s]*/img,"");
        var messageInputErreurBefore='<div class="alert alert-danger alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
        var messageInputErreurAfter='</strong></div>';
        if(gate=='' && dest=='' && mask=='' && flags=='' && dest==''){
            champs_val=false;
            $('#input-alert').append(messageInputErreurBefore+'Remplissez au moins un champ ;)'+messageInputErreurAfter)
            $('input[type=text]').parent().addClass('has-error');
        }else{
            if(dest!='default' && dest!='localhost' && dest!='' && !dest.match(/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/)){
                champs_val=false;
                $('#input-alert').append(messageInputErreurBefore+'Adresse IP de "Destination" invalide'+messageInputErreurAfter)
                $('#dest').parent().addClass('has-error');
            }
            if(gate!='' && !gate.match(/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/)){
                champs_val=false;
                $('#input-alert').append(messageInputErreurBefore+'Adresse IP de "Gateway" invalide'+messageInputErreurAfter)
                $('#gate').parent().addClass('has-error');
            }
            if(mask!='' && !mask.match(/^\/([1-9]|2[0-9]|3[0-2])$/) && !mask.match(/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/)){
                champs_val=false;
                $('#input-alert').append(messageInputErreurBefore+'Masque invalide'+messageInputErreurAfter)
                $('#mask').parent().addClass('has-error');
            }
            if(flags!='' && !flags.match(/[A-Z]/)){
                champs_val=false;
                $('#input-alert').append(messageInputErreurBefore+'Drapeaux invalide'+messageInputErreurAfter)
                $('#flags').parent().addClass('has-error');
            }
            
            if(interf!='' && !interf.match(/^(eth([0-9]|([1-9][0-9]+)))|lo$/)){
                champs_val=false;
                $('#input-alert').append(messageInputErreurBefore+'Interface invalide'+messageInputErreurAfter)
                $('#interf').parent().addClass('has-error');
            }
        }
        /*
            S'il n'y a pas d'erreur dans les inputs, on ajoute une ligne à la table de routage
        */
        if(champs_val){
            tableRoute[tableRoute.length]=new LigneRoute(dest,gate,mask,flags,interf,ligne);
            
            $(':input').not(':button, :submit, :reset, :hidden').val('').prop('checked', false).prop('selected', false);
            
            $("#table_route>tbody:last").append("<tr><td>" + dest + "</td><td>" + gate + "</td><td>" + mask + "</td><td>" + flags + "</td><td>" + interf + "</td> <td><button class='del btn btn-danger' id='del"+ligne+"'>Supprimer</button></td></tr>");
        
            $('.del').click(function() {
                $('#correction-alert').parent().parent().removeClass("panel-success");
                var id = this.id;
                if($('#'+id).is(':submit')){
                    var lignedel=id.replace(/[a-z]/igm,"");
                    $('#'+id).parent().parent().remove();
                    tableRoute = $.grep(tableRoute, function(el,ind) {
                        return (el.id != lignedel);
                    });
                }
            });
            ligne++;
        }
    });
    /*
        Lorque l'on clique sur voir la correction, cela affichera la correction de la table de routage.
        
    */
    $("#retry").click(function(event) {
	window.location.reload(true);
});
    $("#show-correction").click(function( event ) {
        $("#table_corr tbody tr").remove();
        $("#table_corr").parent().parent().removeClass('hidden');
        for(var i in tableRouteReponse){
            $("#table_corr tbody").append("<tr><td>" + tableRouteReponse[i].dest + "</td><td>" + tableRouteReponse[i].gate + "</td><td>" + tableRouteReponse[i].mask + "</td><td>" + tableRouteReponse[i].flags + "</td><td>" + tableRouteReponse[i].interf + "</td></tr>");
        
            
        }
    });
    /*
        Si on clique sur valider la table, on va comparer le tableau de correction au tableau fait par l'utilisateur.
        Cela indiquera si son tableau est vide, 
        s'il manque une ligne, 
        si une fois le bon nombre de ligne il y a une erreur
        ou bien si son tableau est bon.
    */
    $("#correction").click(function( event ) {
        var messageCorValideBefore='<div class="alert alert-success alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
        var messageCorErreurBefore='<div class="alert alert-danger alert-dismissible text-center" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>';
        var messageCorAfter='</strong></div>';
        /*
            on réinitialise les panels, on supprime les alertes
        */
        var dans_tableau=true;
        $(".alert").remove();
        $('input[type=text]').parent().removeClass('has-error');
        $('#correction-alert').parent().parent().removeClass("panel-success");
        /*
            On compare les 2 tableaux
        */
        if(tableRoute.length>0){
            if(tableRoute.length>=tableRouteReponse.length){ //S'il manque une ligne a l'utilisateur, on ne vérifie pas plus et on l'avertit
                for(var i in tableRoute){ 
                    /*
                        On parcours le tableau et on recherche si chaque ligne de se tableau 
                        est bien présente dans le tableau de correction
                    */
                    var stop=true; //Si la ligne ne correspond pas a l'une des lignes du tableau de l'utilisateur, stop restera a true et arrêtera les recherches
                    for(var j in tableRouteReponse){
                        if(tableRoute[i].dest==tableRouteReponse[j].dest 
                        && tableRoute[i].gate==tableRouteReponse[j].gate
                        && tableRoute[i].mask==convMaskDecimal(tableRouteReponse[j].mask)
                        && tableRoute[i].flags==tableRouteReponse[j].flags
                        && tableRoute[i].interf==tableRouteReponse[j].interf){
                            stop=false;
                            break;
                        }else if(tableRoute[i].interf=='lo' && tableRouteReponse[j].interf=='lo'){
                            if((tableRoute[i].dest=='localhost' || tableRoute[i].dest=='127.0.0.0')
                        && tableRoute[i].gate==tableRouteReponse[j].gate
                        && (tableRoute[i].mask=='255.0.0.0' || tableRoute[i].mask=='/8')
                        && tableRoute[i].flags=='U'){
                                stop=false;
                                break;
                            }
                        }
                    }
                    if(stop){
                        dans_tableau=false;
                        break;
                    }
                }
                //Si les éléments sont tous présent et sont tous correct, on indique que c'est le bon résultat
                if(dans_tableau){
                    $('#correction-alert').append(messageCorValideBefore+"C'est le bon résultat"+messageCorAfter);
                    $('#correction-alert').parent().parent().addClass("panel-success");
                    $.post("../ressources/function/fonction_exercice_fait_ajax.php",
                        {
                            UserID: <?php echo '"'.$connect.'"';?>,
                            nom: "Table de routage",
                            isSuccess: "1"
                        },
                        function(data, status){ });
                        //function(data, status){ }).fail(alert('erreur1'));
                    
                }else{
                    $('#correction-alert').append(messageCorErreurBefore+"Il y a une ou plusieurs erreurs"+messageCorAfter);
                    $.post("../ressources/function/fonction_exercice_fait_ajax.php",
                        {
                            UserID: <?php echo  '"'.$connect.'"';?>,
                            nom: "Table de routage",
                            isSuccess: "-1"
                        },
                        function(data, status){ });
                        //function(data, status){ }).fail(alert('erreur2'));
                }
            }else{
                $('#correction-alert').append(messageCorErreurBefore+"Il manque peut être une ligne .."+messageCorAfter);
                $.post("../ressources/function/fonction_exercice_fait_ajax.php",
                    {
                        UserID: <?php echo '"'.$connect.'"'; ?>,
                        nom: "Table de routage",
                        isSuccess: "-1"
                    },
                    function(data, status){ });
                    //function(data, status){ }).fail(alert('erreur3'+data));
            }
        }else{
            $('#correction-alert').append(messageCorErreurBefore+"La table ne peut pas être vide .."+messageCorAfter);
        }
    });
});
</script>
