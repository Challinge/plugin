<?php
session_start();
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 12/03/2015
 * Time: 16:38
 */

require_once('fonctions.php');

$salonuser = htmlspecialchars($_POST['salonutilisateur']);

$nombreusers = 0;

$bdd = bdd_connect();

// Compte le nombre d'utilisateurs connectés à un salon
$reponse_countusers = $bdd->prepare('SELECT User_ID FROM wpvotrechat_participation_salon WHERE Salon = :salon');
$reponse_countusers->execute(array(
    'salon' => $salonuser
));

while ($donnees = $reponse_countusers->fetch()) {
    $nombreusers++;
}

if ($nombreusers != $_SESSION['nombreusers']) {
    $_SESSION['nombreusers'] = $nombreusers;

// On récupère tout ceux qui sont actuellement conecté  au salon que l'utilisation a cliqué
    $reponse_userid = $bdd->prepare('SELECT User_ID FROM wpvotrechat_participation_salon WHERE Salon = :salon');
    $reponse_userid->execute(array(
        'salon' => $salonuser
    ));


    while ($donnees = $reponse_userid->fetch()) {

        // On récupère le nom
        $reponse_username = $bdd->query('SELECT account_login FROM wpvotrechat_chat_accounts WHERE User_ID = ' . $donnees['User_ID']);
        $username = $reponse_username->fetchColumn();

        // on affiche le message (l'id servira plus tard)
        echo '<a id=' . $donnees['User_ID'] . '><p id="p-username">' . $username . '
    <span class="badge badge-info is-hidden">0</span></p></a>';

        $reponse_username->closeCursor();

    }

// Fermeture du curseur de lecture pour éviter d'aloudir la ram
    $reponse_userid->closeCursor();
}