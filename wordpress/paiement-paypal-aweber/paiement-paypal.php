<?php
/**
 * Plugin Name: paiement-paypal
 * Plugin URI: -
 * Description: Avec ce plugin les utilisateurs pourront payer via paypal et seront enregistrés dans la base de données.
 * Version: 1.0.0
 * Author: ADOUKO Franck Venance
 * Author URI: http://www.challinge.net
 * Text Domain: Optional. Plugin's text domain for localization. Example: mytextdomain
 * Domain Path: Optional. Plugin's relative directory path to .mo files. Example: /locale/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: copyright-chall.i.nge
 */

require_once("fonction-api.php");
require_once("traitements.php");
require_once("execution-paiement.php");
require_once("aweber-crud.php");
require_once("recurring-payment-paypal.php");
require_once("cancel-payment-user.php");

global $dejaExecute;
$dejaExecute=1;

// Enregistrement de l'abonnement de 2 Euro
if (isset($_POST['vente-paypal-action'])) {
    add_action('init', 'vente_money_paypal');
}

function vente_money_paypal(){

    //$serveur_paypal = "https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=";
    $serveur_paypal = "https://www.paypal.com/webscr&cmd=_express-checkout&token=";
    $requete = construit_url_paypal();

    $requete = $requete."&METHOD=SetExpressCheckout".


        "&L_BILLINGTYPE0=RecurringPayments".   #The type of billing agreement

        "&L_BILLINGAGREEMENTDESCRIPTION0=".urlencode("Inscription au groupe privé : 2€ puis 37€/mois.").

        "&CANCELURL=".urlencode("http://sport-et-motivation.com/vente-annulee/").

        "&RETURNURL=".urlencode("http://sport-et-motivation.com/vente-faite/").

        "&AMT=2.0".

        "&CURRENCYCODE=EUR".

       // "&DESC=".urlencode("Accéder au groupe privé Sport et Motivations.").

        "&LOCALECODE=FR".

        "&HDRIMG=".urlencode("http://sport-et-motivation.com/wp-content/uploads/2013/11/Logo-Sport-et-motivation.png");

    $ch = curl_init($requete);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $resultat_paypal = curl_exec($ch);

    if (!$resultat_paypal)

    {echo "<p>Erreur</p><p>".curl_error($ch)."</p>";}

    else

    {

        $liste_param_paypal = recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array

        // Si la requête a été traitée avec succès

        if ($liste_param_paypal['ACK'] == 'Success')

        {

            // Redirige le visiteur sur le site de PayPal

            header("Location: ".$serveur_paypal.$liste_param_paypal['TOKEN']);

            exit();

        }

        else // En cas d'échec, affiche la première erreur trouvée.

        {echo "<p>Erreur de communication avec le serveur PayPal.<br />".$liste_param_paypal['L_SHORTMESSAGE0']."<br />".$liste_param_paypal['L_LONGMESSAGE0']."</p>";}

    }

    curl_close($ch);
}

add_filter( 'the_content', 'affichageResultatVente' );

function affichageResultatVente($content)
{
    $abonnement = '';
    global $dejaExecute;
    $dejaExecute=$dejaExecute+1;
    if ( is_page( 'vente-faite' )){
        $abonnement = 'vente';
        //echo $dejaExecute;
        if ($dejaExecute > 1) {

            $execution = execution_paiement($abonnement);
        }
        $content = $content.$execution;

    }

    if ( is_page( 'vente-annulee' )){
        $content = RecupDetailsCommande('');
    }

    if ( is_page( 'abonnement-fait' )){
        $abonnement = 'abonnement';
        if ($dejaExecute > 5) {
            $execution = execution_paiement($abonnement);
        }
        $content = $content.$execution;
    }

    if ( is_page( 'abonnement-annule' )){
        $content = $content.'[restricted]Abonnement annulé[/restricted]';
    }

    return $content;
}


// Ajout du fichier js
add_action( 'wp_footer', 'insert_jquery_foot' );

function insert_jquery_foot() {

    // Enlever le jquery de WP
    wp_deregister_script('jquery');
    // Enregistrer le jquery de la version 1.11.2
    wp_register_script('jquerys', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js', '', false, true);
    // Générer ce lien de jquery dans la page
    wp_enqueue_script('jquerys');

    // Voit si on est sur le front-end
    if ( !is_admin() ) {

        // Enregister notre fichier animation jquery dans le footer
        wp_register_script('wpclickbtn', plugins_url( 'js/click-bouton.js', __FILE__ ), '', false, true);
        // Générer ce de js dans la page
        wp_enqueue_script('wpclickbtn');

    }



}


// Enregistrement de l'abonnement de 37 Euro
if (isset($_POST['abonnement-paypal-actionn'])) {
    add_action('init', 'abonnement_money_paypal');
}

function abonnement_money_paypal(){


    if ($_REQUEST['lebouttonclique']=='reno' ) {
        $serveur_paypal = "https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=";
        $requete = construit_url_paypal();

        $requete = $requete . "&METHOD=SetExpressCheckout" .

            "&CANCELURL=" . urlencode("http://sport-et-motivation.com/abonnement-annulee/") .

            "&RETURNURL=" . urlencode("http://sport-et-motivation.com/abonnement-fait/") .

            "&AMT=37.0" .

            "&CURRENCYCODE=EUR" .

            "&DESC=" . urlencode("Accéder au groupe privé Sport et Motivations, pour ce nouvel abonnement.") .

            "&LOCALECODE=FR" .

            "&HDRIMG=" . urlencode("http://sport-et-motivation.com/wp-content/uploads/2013/11/Logo-Sport-et-motivation.png");

        $ch = curl_init($requete);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $resultat_paypal = curl_exec($ch);

        if (!$resultat_paypal) {
            echo "<p>Erreur</p><p>" . curl_error($ch) . "</p>";
        } else {

            $liste_param_paypal = recup_param_paypal($resultat_paypal); // Lance notre fonction qui dispatche le résultat obtenu en un array

            // Si la requête a été traitée avec succès

            if ($liste_param_paypal['ACK'] == 'Success') {

                // Redirige le visiteur sur le site de PayPal

                header("Location: " . $serveur_paypal . $liste_param_paypal['TOKEN']);

                exit();

            } else // En cas d'échec, affiche la première erreur trouvée.

            {
                echo "<p>Erreur de communication avec le serveur PayPal.<br />" . $liste_param_paypal['L_SHORTMESSAGE0'] . "<br />" . $liste_param_paypal['L_LONGMESSAGE0'] . "</p>";
            }

        }

        curl_close($ch);
    }


    if ($_REQUEST['lebouttonclique']=='annu' ){



        // Récupération de lassession dans le cookie
        $session = CheckLoginCookieForSession();

        global $wpdb;
        $listeDesFieldsUtilisateurs = '';
        // Récupér la liste des mail pour supprimer dans les table
        $listeDesUtilisateurs = $wpdb->get_results("SELECT User_Mail, Profile_ID FROM ". $wpdb->prefix . "EWD_FEUP_Users WHERE User_Sessioncheck='".$session."'", ARRAY_A);

        // Parcours de l'utilisateur trouvé pour le supprimer
        if ($listeDesUtilisateurs) {


            foreach ($listeDesUtilisateurs as $chaqueUtilisateur) {

                cancelPaymentPaypal($chaqueUtilisateur['Profile_ID']);

                $listeDesFieldsUtilisateurs = $wpdb->get_results("SELECT User_ID FROM " . $wpdb->prefix . "EWD_FEUP_User_Fields WHERE Field_Value='" . $chaqueUtilisateur['User_Mail'] . "'", ARRAY_A);
                // Au cas où l'utilisateur annule son abonnement, il est supprimé de la base EWD_FEUP_Users
                global $wpdb;
                $table_name = $wpdb->prefix . 'EWD_FEUP_Users';

                $wpdb->delete(
                    $table_name,
                    array('User_Mail' => $chaqueUtilisateur['User_Mail'])
                );


            }
        }

        // Parcours de l'utilisateur trouvé pour le supprimer
        if ($listeDesFieldsUtilisateurs) {

            // Suppression des champs donnat les informations sur l'utilisateur
            foreach ($listeDesFieldsUtilisateurs as $chaqueUtilisateur) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'EWD_FEUP_User_Fields';


                $wpdb->delete(
                    $table_name,
                    array('User_ID' => $chaqueUtilisateur['User_ID'])
                );
            }
        }


        $goback = add_query_arg( 'settings-updated', 'true',  wp_get_referer() );
        wp_redirect( $goback );
        exit;
    }
}


function CheckLoginCookieForSession() {
    global $wpdb, $ewd_feup_user_table_name;

    $LoginTime = get_option("EWD_FEUP_Login_Time");
    $Salt = get_option("EWD_FEUP_Hash_Salt");
    $CookieName = "EWD_FEUP_Login" . "%" . sha1(md5(get_site_url().$Salt));
    if (isset($_COOKIE[$CookieName])) {$Cookie = $_COOKIE[$CookieName];}
    else {$Cookie = null;}

    $Username = substr($Cookie, 0, strpos($Cookie, "%"));
    $TimeStamp = substr($Cookie, strpos($Cookie, "%")+1, strrpos($Cookie, "%")-strpos($Cookie, "%"));
    $SecCheck = substr($Cookie, strrpos($Cookie, "%")+1);
    $UserAgent = $_SERVER['HTTP_USER_AGENT'];

    $DBSeccheck='';

    if (isset($_COOKIE[$CookieName]) and $TimeStamp < (time() + $LoginTime*60)) {
        $UserDB = $wpdb->get_row($wpdb->prepare("SELECT User_Sessioncheck , User_Password FROM $ewd_feup_user_table_name WHERE Username ='%s'", $Username));
        $DBSeccheck = $UserDB->User_Sessioncheck;


        if (strcmp(sha1($SecCheck . $UserAgent), $DBSeccheck) === 0) {
            $User = array('Username' => $Username, 'User_Password' => $UserDB->User_Password);
            foreach($user as $users){
                $DBSeccheck=$users['User_Sessioncheck'];
            }
            return $DBSeccheck;
        }
        else {
            return false;
        }

        foreach($UserDB as $user){
            $DBSeccheck=$user['User_Sessioncheck'];
        }
    }

    return $Username;
}