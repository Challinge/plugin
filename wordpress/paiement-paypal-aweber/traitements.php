<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 25/02/2015
 * Time: 14:58
 */

function RecupDetailsCommande($leToken)
{
    $serveur_paypal = "https://www.sandbox.paypal.com/webscr&cmd=_express-checkout&token=";

    $requete = construit_url_paypal();

    $requete = $requete . "&METHOD=GetExpressCheckoutDetails" .

        "&TOKEN=" . htmlentities($leToken, ENT_QUOTES); // Ajoute le jeton

    $ch = curl_init($requete);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $resultat_paypal = curl_exec($ch);

    if (!$resultat_paypal) // S'il y a une erreur

    {
        $paiementEnregistre= "<p>Erreur</p><p>" . curl_error($ch) . "</p>";
    } // S'il s'est exécuté correctement

    else {

        $liste_param_paypal = recup_param_paypal($resultat_paypal);

    }

    curl_close($ch);
    return $liste_param_paypal;
}

// Envoie de mail à la personne qui a payé l'abonnement
function envoieMail($username, $password, $email, $nom, $prenom) {

    $mail = 'fvadouko@live.fr'; // Déclaration de l'adresse de destination. Ici on met $email
    if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui présentent des bogues.
    {
        $passage_ligne = "\r\n";
    } else {
        $passage_ligne = "\n";
    }

    $headers[] = 'From: sport-et-motivation '; // fvadouko-buyer et  DU4YNB9KM7KBA
    $headers[] = "MIME-Version: 1.0" . $passage_ligne;
    $headers[] = "Content-Type: text/html; charset=UTF-8" . $passage_ligne;

    $password = strtolower($password);

    $message_html = "<html><head></head><body>
        Bonjour ". $prenom. " ". $nom.",<br/><br/>
        Tout d'abord je tiens à te remercier pour la confiance que tu m'accordes.
        Je suis persuadé que le club privé Sport et Motivation t'aidera à rester motivé au quotidien,
        dans tous tes projets, tout en améliorant ta santé. Tu trouveras ci-dessous tes identifiants
        pour te connecter à ton espace membre.<br/>
        votre nom utilisateur : ".$username."<br/> votre mot de passe : ".$password."<br/><br/>
        à l'url : http://sport-et-motivation.com/connexion/
        <br/><br/> À tout de suite. Benjamin</body></html>";

    wp_mail( $email, 'Vos identifiants pour le groupe privé S.E.M. sont ici...', $message_html, $headers );
    //wp_mail( 'fvadouko@live.fr', 'Vos identifiants à sport-et-motivation.com', $message_html, $headers );
}

// Insertion des identifiants de la personne qui a payé l'abonnement de 2 Euro
function insertionBDD($username, $password, $mail, $nom, $prenom, $profileid) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'EWD_FEUP_Users';
    $password = trim(strtolower($password));
    $password = Generate_Password_For_Registration($password);

    // Inserer un nouvel utilisateur dans EWD_FEUP_Users
    $wpdb->insert(

        $table_name,

        array(

            'Username' => $username,

            'User_Password' => $password,

            'User_Mail'=> $mail,

            'Profile_ID'=> $profileid

        )

    );


    $listeDesUtilisateurs = $wpdb->get_results("SELECT User_ID FROM ". $wpdb->prefix . "EWD_FEUP_Users WHERE User_Mail='".$mail."'", ARRAY_A);

    global $wpdb;
    $table_name = $wpdb->prefix . 'EWD_FEUP_User_Fields';

    // Inserer un nouvel utilisateur dans EWD_FEUP_User_Fields
    if ($listeDesUtilisateurs) {

        foreach ($listeDesUtilisateurs as $chaqueUtilisateur) {
            // Inserer un nouveau champ EWD_FEUP_User_Fields
            $wpdb->insert(

                $table_name,

                array(

                    'Field_ID' => '1',

                    'User_ID' => $chaqueUtilisateur['User_ID'],

                    'Field_Name'=> 'First Name',

                    'Field_Value'=> $nom
                )

            );

            // Inserer un nouveau champ EWD_FEUP_User_Fields
            $wpdb->insert(

                $table_name,

                array(

                    'Field_ID' => '2',

                    'User_ID' => $chaqueUtilisateur['User_ID'],

                    'Field_Name'=> 'Last Name',

                    'Field_Value'=> $prenom
                )

            );

            // Inserer un nouveau champ EWD_FEUP_User_Fields
            $wpdb->insert(

                $table_name,

                array(

                    'Field_ID' => '3',

                    'User_ID' => $chaqueUtilisateur['User_ID'],

                    'Field_Name'=> 'Email',

                    'Field_Value'=> $mail
                )

            );
        }
    }
}

// Génération de mot de passe crypté
function Generate_Password_For_Registration($plainPassword = null) {
    if(!$plainPassword) {
        return false;
    }
    $intermediateSalt = bin2hex(openssl_random_pseudo_bytes(30));
    $intermediateSalt = substr($intermediateSalt,0,22);
    $finalSalt = '$2y$13$'.$intermediateSalt.'$';
    $hashedPassword = crypt($plainPassword,$finalSalt);
    return $hashedPassword;
}