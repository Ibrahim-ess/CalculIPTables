<?php

function aleaAdresseIP() {
// mt_rand est plus rapide que rand
// evitons le fait de toucher à la classe D ou E. 
	$input_adresse_ip=(string) mt_rand(1,223)
            .'.'. (string) mt_rand(0,255)
            .'.'. (string) mt_rand(0,255)
            .'.'. (string) mt_rand(1,254);
        $input_inetaddr= ip2long($input_adresse_ip);
	return($input_inetaddr);
}

// ********************************************
// Calcul du nombre d'hotes
// ********************************************
function nbreHotes($mask) {
    if ($mask==32)
            return(1);
    else
            return( (1<<(32-$mask)) -2);
}

// ******************************************
// Fonction de calcul du nbre de bits necessaire pour représenter n val. diff. au moins (plus rapide que ceil(log_2(n)) ?)
// ******************************************
function nbBitsNecessaires($n) {
    $i=0;$j=1;
    for (;$j<$n;$i++, $j <<= 1);
    return($i);
}

function afficheBinaire($n) {
    return chunk_split(chunk_split(base_convert($n,10,2),4," "),10,". ");
}

// ******************************************
// Fonction de calcul du masque inetaddr pour /$n
// ******************************************
function masque($n) {
	if ($n<=0) {
		echo "Erreur interne: n<=0 !\n";
		$n=1;
	} else if ($n>=32) {
		echo "Erreur interne: n>=32 !\n";
		$n=31;
	}
    return( ((1<<$n)-1) << (32 -$n));
}

// ********************************************
// Calcul du numéro de réseau
// ********************************************
function numeroReseau($inetaddr,$mask) {
    return($inetaddr & masque($mask));
}

// ********************************************
// Calcul de la premiere adresse
// ********************************************
function premAdresse($route,$mask) {
     if ($mask==32)
	$offset=0;
     else
	$offset=1;

     if ($mask==31)
	$calcul_premiere_ip="Non applicable";
     else {
	$calcul_premiere_ip=$route+$offset;
     }
     return($calcul_premiere_ip);
}

// ********************************************
// Calcul du broadcast
// ********************************************
function adresseDiffusion($route,$mask) {
     if ($mask==32)
	$offset=0;
     else
        $offset=1;
     return($route+nbreHotes($mask)+$offset);
}

// ********************************************
// Calcul de la dernière adresse
// ********************************************
function derAdresse($route,$mask) {
     if ($mask==32)
	$offset=0;
     else
	$offset=-1;
     if ($mask==31)
        $calcul_derniere_ip="Non applicable";
     else {
	$calcul_derniere_ip=adresseDiffusion($route,$mask)+$offset;
     }
     return($calcul_derniere_ip);
}

?>
