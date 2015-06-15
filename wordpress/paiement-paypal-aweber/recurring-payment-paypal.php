<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 18/03/2015
 * Time: 11:11
 */

function recurringPaymentPaypal(){

    // Calcul du mois suivant
    $mois = date("m");
    $jour = date("d");
// Gestion du mois de février
    if ( ($mois == 2) AND ($jour >= 27) ){
        $jour=1;
    }

    if ( $mois == 12 ) {
        $mois = 1;
    }
    else{
        $mois++;
    }

// Calcul du jour

    if ( ($mois == 31) || ($jour == 30) ) {
        $mois = 1;
    }

    $requete = construit_url_paypal();

$requete = $requete . "&METHOD=CreateRecurringPaymentsProfile" .

    "&TOKEN=" . htmlentities($_GET['token'], ENT_QUOTES) .

    "&PAYERID=" . htmlentities($_GET['PayerID'], ENT_QUOTES) .

    "&PROFILESTARTDATE=2015-0".$mois."-".$jour."T00:00:00Z" .

    "&DESC=" . urlencode("Accéder au groupe privé Sport et Motivations, pour ce nouvel abonnement.") .

    "&BILLINGPERIOD=Month" .

    "&BILLINGFREQUENCY=1" .

    "&AMT=37" .

    "&CURRENCYCODE=EUR" .

    "&COUNTRYCODE=FR" .

    "&MAXFAILEDPAYMENTS=6";

$ch = curl_init($requete);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$resultat_paypal = curl_exec($ch);
    $profilid = '';

if (!$resultat_paypal) {
    echo "<p>Erreur</p><p>" . curl_error($ch) . "</p>";
} else {

    $liste_param_paypal = recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array

    // Si la requête a été traitée avec succès

    if ($liste_param_paypal['ACK'] == 'Success') {

        if ($liste_param_paypal['PROFILESTATUS'] == 'ActiveProfile'){
            $profilid = $liste_param_paypal['PROFILEID'];
        }


    } else // En cas d'échec, affiche la première erreur trouvée.

    {
        echo "<p>Erreur de communication avec le serveur PayPal.<br />" . $liste_param_paypal['L_SHORTMESSAGE0'] . "<br />" . $liste_param_paypal['L_LONGMESSAGE0'] . "</p>";
    }

}

curl_close($ch);
    return $profilid;
}