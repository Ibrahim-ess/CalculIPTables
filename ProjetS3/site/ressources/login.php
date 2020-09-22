<?php

    require_once('CAS.php');
    error_reporting(E_ALL & ~E_NOTICE);
    
	//phpCAS::setDebug('/var/log/phpCAS.log');
	//phpCAS::client(CAS_VERSION_2_0,'portail.cevif.univ-paris13.fr',443,'/cas/',true);
	phpCAS::client(CAS_VERSION_2_0,'cas.univ-paris13.fr',443,'/cas/',true);
	phpCAS::setNoCasServerValidation();

    //phpCAS::setCasServerCACert("/etc/pki/CA/chaine.pem");

    
    function authentication() {
        phpCAS::forceAuthentication();
        return phpCAS::getUser();
    }
    function deconnexion(){
        phpCAS::logoutWithRedirectService('http://www-info.iutv.univ-paris13.fr/CalculIP');
        //phpCAS::logoutWithRedirectService('http://calcul-ip-oursinator-1.c9.io/?logout=success');
    }
    
    function authrequired() {
        if (!(phpCAS::isAuthenticated())) {
            header("Location: http://perdu.com");
            die('Get away');
        }
    }
    function isLogin() {
        return phpCAS::checkAuthentication();
    }
    function getUser() {
        return phpCAS::getUser();
    }
?>
