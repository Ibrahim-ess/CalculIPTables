<?php
    function structureTrame($title,$bdd){
        ?>
        <div class="container">
            <div class="row" >
                <div class="col-md-12 col-xs-12">
            <?php
        $i=0;
        try{
            $req = $bdd->prepare('SELECT * FROM trame');//on récupere tout le contenu de la table trame
    			$req->execute();
    			while($rep = $req->fetch(PDO::FETCH_ASSOC)){
    			    $tabNom[$i] = $rep['Nom'];//on stock dans $tabNom les différentes informations
    			    $champ = explode(',',$rep['Tab']);
    			    $min = explode(',',$rep['tailleMinFixe']);
    			    $max = explode(',',$rep['tailleMax']);
    			    for($j=0;$j<count($champ);$j++){
    			        ${$tabNom[$i]}[] = $champ[$j];
    			        ${$tabNom[$i]}[]= $min[$j];
    			        ${$tabNom[$i]}[]= $max[$j];
    			    }
    			    $i++;
    			}
        }
        catch(PDOException $e)
        {
            //On termine le script
            die('<p> Erreur['.$e->getCode.'] : '.$e->getMessage().'</p>');
        }
        $nomTrameJS="";//On crée un tableau contenant les différents noms des différentes trames sous la forme: "Nom composé 1","Nom composé 2" qui va servir pour le javascript
        for($k=0;$k<$i;$k++){
            if($k>0){
                $nomTrameJS.= '\',\'';
            }
            $nomTrameJS.=$tabNom[$k];
        }
        $nomTabJS="";//on crée un tableau avec les différents noms des différentes trames sous la forme: nom1,nom2 qui va servir pour le javascript
        for($k=0;$k<$i;$k++){
            if($k>0){
                $nomTabJS.= ',';
            }
            $exp = explode(' ',$tabNom[$k]);
            $nomTabJS.= strtolower($exp[1]);
        }
        $tabNomJS;//on crée un tableau avec les différents noms des différentes trames sous la forme: nom1,nom2 qui va servir pour le javascript
        for($m=0;$m<$i;$m++){
            $exp = explode(' ',$tabNom[$m]);
            $tabNomJS[] = strtolower($exp[1]);
            ${$tabNomJS[$m]}= implode(',',${$tabNom[$m]});
        }
        $rand = rand(0,$i-1);
        ?>
        <h1>Structure de trame <small><?php echo $tabNom[$rand]; ?></small></h1><!-- Affichage du type de trame à reconstituer -->
        <div class="panel panel-default" id="exercice">
            <div class="panel-heading">
                <h3 class="panel-title">Exercice :</h3>
            </div>
            <div class="panel-body">
            <p><b>Vous devez reconstituer la structure d'un(e) <?php echo $tabNom[$rand]; ?> en renseignant le nom des champs, leur taille minimum et maximum, ou leur taille fixe.<br />PS: Pour la trame ethernet, le FCS est compris.</b></p>
            <div id="commentaire"></div>
                <h3><b>Type : </b><span class="text-info" id="trame"><?php echo $tabNom[$rand] ?></span></h3><!-- Affichage du type de trame à reconstituer -->
                <table id="tab_champ" class="table table-striped table-bordered">
                    <tbody id="tbody">
                        <tr>
                            <th class="col-xs-4 col-md-4">
                                <p>Nom du champ</p>
                            </th>
                            <th class="col-xs-4 col-md-4">
                                <p>Taille min ou fixe*</p>
                            </th>
                            <th class="col-xs-4 col-md-4">
                                <p>Taille max</p>
                            </th>
                        </tr>
                        <tr id="champ" >
                            <td id="champ_0" class="col-xs-4 col-md-4">
                                <select class="form-control" id="saisir_0"><!-- Affichage du select avec tous les différents champs des différents types de trame -->
                                    <?php
                                    $select='<option value='."saisir".'>Saisir...</option>';
                                    echo '<option value="saisir">Saisir...</option>';
                                    $allInTab=array();
                                    $occurence=0;
                                    for ($j=0;$j<$i;$j++){//Enlève les noms en double des différents champs
                                        for($k=0;$k<count(${$tabNom[$j]});$k+=3){
                                            for($o=0;$o<count($allInTab);$o++){
                                                if(${$tabNom[$j]}[$k]==$allInTab[$o]){
                                                    $occurence++;
                                                }
                                            }
                                            if($occurence<1){
                                               $allInTab[] = ${$tabNom[$j]}[$k];
                                            }
                                            else{
                                                $occurence=0;
                                            }
                                        }
                                    }
                                    sort($allInTab);//tri dans l'ordre alphabétique
                                    foreach($allInTab as $valeur){
                                        $select.= '<option value='.strtolower($valeur).'>'.$valeur.'</option>';
                                        echo '<option value='.strtolower($valeur).'>'.$valeur.'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                            <td class="col-xs-4 col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="min_0" name="min_0">
                                    <div class="input-group-addon">bit(s)</div>
                                </div>
                            </td>
                            <td class="col-xs-4 col-md-4">
                                <div class="input-group">
                                    <input type="text" class="form-control" id="max_0" name="max_0">
                                    <div class="input-group-addon">bit(s)</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p><b><i>* si le champ est de taille fixe, alors mettre * dans "Taille max".</i></b></p>
                <div class="form-group">
                    <input type="button" class="btn btn-info col-md-4 col-xs-12" id="valider_champ" value="Ajouter un champ"/><!-- Bouton permettant l'ajout de champs -->
                    <input type="button" class="btn btn-danger col-md-4 col-xs-12" id="suppr_champ" value="Supprimer un champ" /><!-- Bouton permettant la suppression de champs -->
                    <input type="button" class="btn btn-success col-md-4 col-xs-12" id="valider" value="Valider"/><!-- Bouton permettant la validation du formulaire -->
                    <a id="button" href='' hidden><input type='button' class='btn btn-warning col-md-4 col-md-offset-4 col-xs-12' id='recommencer' value='Recommencer'/></a> <!-- Bouton permettant de recommencer l'exercice -->
                </div>
            </div>
        </div>
        </div>
        </div>
    </div>
        <script>
            jQuery( document ).ready(function() {
            var i=1;
            $("#valider_champ").click(function( event ) {// lors du clique sur le bouton ajouter un champ, on ajoute une ligne avec les différents champs, et on incrémente 1 au nombre de champs
                event.preventDefault();
                $("#tbody").append("<tr id='champ'><td id='champ_"+i+"'><select class='form-control' id='saisir_"+i+"'>'<?php echo $select;?>'</select></td><td><div class='input-group'><input type='text' class='form-control' id='min_"+i+"' name='min_"+i+"'><div class='input-group-addon'>bit(s)</div></div></td><td><div class='input-group'> <input type='text' class='form-control' id='max_"+i+"' name='max_"+i+"'> <div class='input-group-addon'>bit(s)</div></div></td></tr>");
                i++;
            });
            $("#suppr_champ").click(function( event ) {// lors du clique sur le bouton supprimer un champ, on supprime une ligne avec les différents champs, et on décrémente 1 au nombre de champs
                event.preventDefault();
                if(i-1>0){
                    var name= 'champ_'+(i-1);
                    $('#'+name).parent().remove();
                    i--;
                }
            });
            $("#valider").click(function(event) {//lors du clique sur le bouton valider, on verifie le resultat de l'utilisateur
                event.preventDefault();
                var verif=0;
                var tab = new Array('<?php echo $nomTrameJS; ?>');
                var select =  $("#trame").text();
                var k=0;
                for(k=0;k<tab.length;k++){
                    if(select==tab[k]){
                    <?php
                    //création en php de tableaux javascript des differents types présents dans la trame
                        for($n=0;$n<$i;$n++){
                            echo 'var '.$tabNomJS[$n].' = new Array(';
                            for($j=0;$j<count(${$tabNom[$n]});$j++){
                                if($j>0){
                                    echo ',';
                                }
                                echo '"'.${$tabNom[$n]}[$j].'"';
                            }
                            echo ');';
                        }
                    ?>
                    var valeur = new Array(<?php echo $nomTabJS; ?>);//tableau de tableaux (contient les tableaux créés précedemment)
                    var j = 0;
                    var compteur = 0;
                    var vide = 0;
                    var erreur = 0;
                    var place = 0;
                    $(".glyphicon").remove();
                    for(j=0;j<i;j++){
                        var txt = $("#saisir_"+j+" option:selected").text();//on recupère le contenu du select
                        $('#saisir_'+j).parent().removeClass('has-success');
                        $('#saisir_'+j).parent().removeClass('has-error');
                        $('#saisir_'+j).parent().removeClass('has-warning');
                        if(txt==valeur[k][j*3]){//on compare la valeur avec la correction
                            $('#saisir_'+j).parent().addClass('has-success');
                            compteur++;
                        }
                        else if(txt=="Saisir..."){
                            $('#saisir_'+j).parent().addClass('has-warning');
                            vide ++;
                        }
                        else if($.inArray(txt, valeur[k])!=-1){
                            $('#saisir_'+j).parent().addClass('has-warning');
                            place++;
                        }
                        else{
                            $('#saisir_'+j).parent().addClass('has-error');
                            erreur++;
                        }
                        $('#min_'+j).parent().removeClass('has-success');
                        $('#min_'+j).parent().removeClass('has-error');
                        $('#min_'+j).parent().removeClass('has-warning');
                        txt = $("#min_"+j).val();//on affecte à txt la valeur du champ min puis on la compare avec la correction
                        if(txt==valeur[k][(j*3)+1]){
                            $('#min_'+j).parent().addClass('has-success');
                            compteur++;
                        }
                        else if(txt==""){
                            $('#min_'+j).parent().addClass('has-warning');
                            vide ++;
                        }
                        else if($.inArray(txt, valeur[k])!=-1){
                            $('#min_'+j).parent().addClass('has-warning');
                            place++;
                        }
                        else{
                            $('#min_'+j).parent().addClass('has-error');
                            erreur++;
                        }
                        $('#max_'+j).parent().removeClass('has-success');
                        $('#max_'+j).parent().removeClass('has-error');
                        $('#max_'+j).parent().removeClass('has-warning');
                        txt = $("#max_"+j).val();//on affecte à txt la valeur du champ max puis on la compare avec la correction
                        if(txt==valeur[k][(j*3)+2]){
                            if(txt!=''){
                                $('#max_'+j).parent().addClass('has-success');
                                compteur++;
                            }
                            else{
                                $('#max_'+j).parent().addClass('has-warning');
                                vide++;
                            }
                        }
                        else if(valeur[k][(j*3)+2]=="" && txt=='*'){
                            $('#max_'+j).parent().addClass('has-success');
                            compteur++;
                        }
                        else if(txt==""){
                            $('#max_'+j).parent().addClass('has-warning');
                            vide ++;
                        }
                        else if($.inArray(txt, valeur[k])!=-1){
                            if(txt!=''){
                                $('#max_'+j).parent().addClass('has-warning');
                                place++;
                            }
                            else{
                                $('#max_'+j).parent().addClass('has-warning');
                                vide++;
                            }
                        }
                        else{
                            $('#max_'+j).parent().addClass('has-error');
                            erreur++;
                        }
                    }
                    $('#commentaire').children().remove();
                    var prefix;
                    var message;
                    if(erreur>0){
                        prefix="<div class='alert alert-danger alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>";
                    }
                    else if(place>0){
                        prefix="<div class='alert alert-warning alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>";
                    }
                    else if(compteur==valeur[k].length){
                        prefix="<div class='alert alert-success alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>";
                    }
                    else{
                        prefix="<div class='alert alert-warning alert-dismissible text-center' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button><strong>";
                    }
                    if(erreur==0){//S'il n'y a pas d'erreur
                        if(place==0){//S'il n'y a pas de valeur de champ à la mauvaise place
                            if(compteur==valeur[k].length&&i-vide/3==valeur[k].length/3){//Si le nombre de champs valides est égal au nombre de champs de la trame et que le compteur soustrait du nombre de champs vides, le tout égal au nombre de champs de la trame alors on notifie l'utilisateur qu'il a réussi l'exercice
                                message="Brave ! L'exercice a bien été réussi !";
                                $('#commentaire').append(prefix+message+"</strong></div>");
                                document.getElementById('button').hidden = false;//on affiche le bouton recommencer
                                verif++;
                                //Ajout d'un exercice réussi dans la base de données
                                $.post("../ressources/function/fonction_exercice_fait_ajax.php",
                                    {
                                        UserID: <?php if(!empty($_SESSION['connect'])) echo $_SESSION['connect'];else echo -1; ?>,
                                        nom: "Structure de trame",
                                        isSuccess: "1"
                                    },
                                    function(data, status){
                                });
                            }
                            else if(compteur<valeur[k].length){//Si le nombre de champs valides est inférieur au nombre de champs de la trame on notifie l'utilisateur qu'il faut remplir les champs vides et qu'il lui manque peut être des champs
                                message="Il faut remplir les champs vides !";
                                $('#commentaire').append(prefix+message+"</strong></div>");
                                if(compteur+vide<valeur[k].length){
                                    message="Il manque peut être des champs !";
                                    $('#commentaire').append(prefix+message+"</strong></div>");
                                    //Ajout d'un exercice non réussi dans la base de données
                                    $.post("../ressources/function/fonction_exercice_fait_ajax.php",
                                        {
                                            UserID: <?php if(!empty($_SESSION['connect'])) echo $_SESSION['connect'];else echo -1; ?>,
                                            nom: "Structure de trame",
                                            isSuccess: "-1"
                                        },
                                        function(data, status){
                                    });
                                }
                            }
                        }
                        else{//Si un ou plusieurs éléments ne sont pas à la bonne place on notifie l'utilisateur
                            message="Un ou plusieurs éléments ne sont pas à la bonne place !";
                            $('#commentaire').append(prefix+message+"</strong></div>");
                            //Ajout d'un exercice non réussi dans la base de données
                            $.post("../ressources/function/fonction_exercice_fait_ajax.php",
                                {
                                    UserID: <?php if(!empty($_SESSION['connect'])) echo $_SESSION['connect'];else echo -1; ?>,
                                    nom: "Structure de trame",
                                    isSuccess: "-1"
                                },
                                function(data, status){
                            });
                        }
                    }
                    else{//S'il y a plus d'une erreur on notifie l'utilisateur
                        message="Il y a une ou plusieurs erreur(s) !";
                        $('#commentaire').append(prefix+message+"</strong></div>");
                        //Ajout d'un exercice non réussi dans la base de données
                        $.post("../ressources/function/fonction_exercice_fait_ajax.php",
                            {
                                UserID: <?php if(!empty($_SESSION['connect'])) echo $_SESSION['connect'];else echo -1; ?>,
                                nom: "Structure de trame",
                                isSuccess: "-1"
                            },
                            function(data, status){
                        });
                    }
                }
            }
        });
        });
        </script>
        <?php
    }
?>
