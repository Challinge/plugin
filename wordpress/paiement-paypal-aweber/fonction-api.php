<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 25/02/2015
 * Time: 14:21
 */

$serveur_paypal = "https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token="; //serveur test
//$serveur_paypal = "https://www.paypal.com/webscr&cmd=_express-checkout&token=";


function construit_url_paypal()

{

    //$api_paypal = 'https://api-3t.sandbox.paypal.com/nvp?'; // Site de l'API PayPal. On ajoute déjà le ? afin de concaténer directement les paramètres.
    $api_paypal = 'https://api-3t.paypal.com/nvp?';
    $version = 86; // Version de l'API

    // Acomplèter entre griffes

    //$user = 'fvadouko-facilitator_api1.live.fr';
    //$pass = 'TXFH3NBFZT4JQLT7';
    //$signature = 'A6t6vOcdk87G.G4yQSzZpDeSSeDJAUgIQlegpkir0Vh39EkGyMdHrc-f';

    $api_paypal = $api_paypal.'VERSION='.$version.'&USER='.$user.'&PWD='.$pass.'&SIGNATURE='.$signature; // Ajoute tous les paramètres



    // A complèter entre griffes

    return  $api_paypal; // Renvoie la chaîne contenant tous nos paramètres.

}

function recup_param_paypal($resultat_paypal)

{

    $liste_parametres = explode("&",$resultat_paypal); // Crée un tableau de paramètres

    foreach($liste_parametres as $param_paypal) // Pour chaque paramètre

    {

        list($nom, $valeur) = explode("=", $param_paypal); // Sépare le nom et la valeur

        $liste_param_paypal[$nom]=urldecode($valeur); // Crée l'array final

    }

    return $liste_param_paypal; // Retourne l'array

}