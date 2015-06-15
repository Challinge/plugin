<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 18/03/2015
 * Time: 14:04
 */

function cancelPaymentPaypal($profileid){


    $requete = construit_url_paypal();

    $requete = $requete . "&METHOD=ManageRecurringPaymentsProfileStatus" .

        "&PROFILEID=" . $profileid .

        "&ACTION=Cancel";

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

        if ($liste_param_paypal['PROFILEID'] != '') {



        } else // En cas d'échec, affiche la première erreur trouvée.

        {
            echo "<p>Erreur de communication avec le serveur PayPal.<br />" . $liste_param_paypal['L_SHORTMESSAGE0'] . "<br />" . $liste_param_paypal['L_LONGMESSAGE0'] . "</p>";
        }

    }

    curl_close($ch);

}