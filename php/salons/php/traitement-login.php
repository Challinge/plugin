<?php
session_start();
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 09/03/2015
 * Time: 12:47
 */

require_once('fonctions.php');

$is_user_connect = false;
if ($is_user_connect == false) {

    $bdd = bdd_connect();

    $user_mdp = htmlspecialchars($_POST['password']);
    $user_pseudo = htmlspecialchars($_POST['pseudo']);

    $time = time();
    $reponse_mdp = $bdd->query('SELECT account_pass FROM wpvotrechat_chat_accounts WHERE account_login = ' . $bdd->quote($_POST['pseudo']));
    $mdp = $reponse_mdp->fetchColumn();
    if (isset($_POST['password']) && $user_mdp == $mdp) {
        user_connect($_SERVER['REMOTE_ADDR'], $_POST['pseudo']);
        $_SESSION['pseudo'] = htmlspecialchars($_POST['pseudo']);
        $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
        $_SESSION['nombreusers'] = 0;

        // Récupération de l'id
        $reponse_userid = $bdd->query('SELECT User_ID FROM wpvotrechat_chat_accounts WHERE account_login = ' . $bdd->quote($_POST['pseudo']));
        $userid = $reponse_userid->fetchColumn();
        $_SESSION['userid'] = $userid;

        // Fermeture du curseur de lecture pour éviter d'aloudir la ram
        $reponse_mdp->closeCursor();

        header('Location: chat.php');

    }

    else {
        echo ('Mot de passe incorrect !');
    }
}
else {
    echo 'Erreur : vous êtez déjà connectés !';
}


