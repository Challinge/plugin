<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 25/02/2015
 * Time: 14:54
 */



function execution_paiement($abonnement)
{

    $requete = construit_url_paypal(); // Construit les options de base

    // Le premier abonnement est de 2Euro
    if ($abonnement=='vente') {
        // On ajoute le reste des options
        // La fonction urlencode permet d'encoder au format URL les espaces, slash, deux points, etc.)
        $requete = $requete . "&METHOD=DoExpressCheckoutPayment" .
            "&TOKEN=" . htmlentities($_GET['token'], ENT_QUOTES) . // Ajoute le jeton qui nous a été renvoyé
            "&AMT=2.0" .
            "&CURRENCYCODE=EUR" .
            "&PayerID=" . htmlentities($_GET['PayerID'], ENT_QUOTES) . // Ajoute l'identifiant du paiement qui nous a également été renvoyé
            "&PAYMENTACTION=sale";
    }

    // Le deuxième abonnement est de 37 Euro
    if ($abonnement=='abonnement') {
        // On ajoute le reste des options
        // La fonction urlencode permet d'encoder au format URL les espaces, slash, deux points, etc.)
        $requete = $requete . "&METHOD=DoExpressCheckoutPayment" .
            "&TOKEN=" . htmlentities($_GET['token'], ENT_QUOTES) . // Ajoute le jeton qui nous a été renvoyé
            "&AMT=37.0" .
            "&CURRENCYCODE=EUR" .
            "&PayerID=" . htmlentities($_GET['PayerID'], ENT_QUOTES) . // Ajoute l'identifiant du paiement qui nous a également été renvoyé
            "&PAYMENTACTION=sale";
    }

// Initialise notre session cURL. On lui donne la requête à exécuter.
    $ch = curl_init($requete);

// Modifie l'option CURLOPT_SSL_VERIFYPEER afin d'ignorer la vérification du certificat SSL. Si cette option est à 1, une erreur affichera que la vérification du certificat SSL a échoué, et rien ne sera retourné.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
// Retourne directement le transfert sous forme de chaîne de la valeur retournée par curl_exec() au lieu de l'afficher directement.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// On lance l'exécution de la requête URL et on récupère le résultat dans une variable
    $resultat_paypal = curl_exec($ch);

    if (!$resultat_paypal) // S'il y a une erreur, on affiche "Erreur", suivi du détail de l'erreur.
    {
        $paiementFait = "<p>Erreur</p><p>" . curl_error($ch) . "</p>";
    } // S'il s'est exécuté correctement, on effectue les traitements...
    else {
        $liste_param_paypal = recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array

        // On affiche tous les paramètres afin d'avoir un aperçu global des valeurs exploitables (pour vos traitements). Une fois que votre page sera comme vous le voulez, supprimez ces 3 lignes. Les visiteurs n'auront aucune raison de voir ces valeurs s'afficher.
        $paiementFait = $liste_param_paypal;

        //echo $liste_param_paypal['ACK'];
        // Si la requête a été traitée avec succès
        if ($liste_param_paypal['ACK'] == 'Success') {

            // Le premier abonnement est de 2Euro
            if ($abonnement=='vente') {

                // Récupération des Détails de l'abonnement 2Euro ou 37 Euro
                $tabDetailsAbonnement = RecupDetailsCommande($_GET['token']);
                $email = $tabDetailsAbonnement['EMAIL'];
                $username = substr($tabDetailsAbonnement['EMAIL'], 0, strpos($tabDetailsAbonnement['EMAIL'],"@"));
                $password = $tabDetailsAbonnement['PAYERID'];
                $nom = $tabDetailsAbonnement['FIRSTNAME'];
                $prenom = $tabDetailsAbonnement['LASTNAME'];

                $profileid = recurringPaymentPaypal();

                // Envoie de mail
                envoieMail($username, $password, $email, $nom, $prenom);

                // Insertion des identifiants de la personne qui a payé l'abonnement de 2 Euro
                insertionBDD($username, $password, $email, $nom, $prenom, $profileid);

                // Ajout de l'abonné à aweber
                ajout_abonne($email, $nom, $prenom, $_GET['token']);

                $paiementFait = "<p>Nous vous avons envoyé votre login et mot de passe dans votre boite mail.</p>"; // On affiche la page avec les remerciements, et tout le tralala...


            }

            // Le deuxième abonnement est de 37 Euro
            if ($abonnement=='abonnement') {

                $paiementFait= "<p>Votre abonnement a été pris en compte avec succès.</p>"; // On affiche la page avec les remerciements, et tout le tralala...
            }


            // Mise à jour de la base de données & traitements divers...
            // mysql_query("UPDATE commandes SET etat='OK' WHERE id_commande='" . $liste_param_paypal['TRANSACTIONID'] . "'");
        } else // En cas d'échec, affiche la première erreur trouvée.
        {
            //$paiementFait = "<p>Erreur de communication avec le serveur PayPal.<br />" . $liste_param_paypal['L_SHORTMESSAGE0'] . "<br />" . $liste_param_paypal['L_LONGMESSAGE0'] . "</p>";
            $paiementFait = "<p>Nous vous avons envoyé votre login et mot de passe dans votre boite mail.</p>";
        }
    }
// On ferme notre session cURL.
    curl_close($ch);

    return $paiementFait;
}