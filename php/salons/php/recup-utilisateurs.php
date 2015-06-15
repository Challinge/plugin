<?php
session_start();
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 10/03/2015
 * Time: 17:37
 */

require_once('fonctions.php');

$iduser = htmlspecialchars($_POST['idutilisateur']);
$salonuser = htmlspecialchars($_POST['salonutilisateur']);

$bdd = bdd_connect();

// Au cas où l'internaute était dans un salon précédemment on le supprime
$query = $bdd->prepare('
      DELETE FROM wpvotrechat_participation_salon WHERE User_ID = :user_id
      ');
$query->execute(array(
    'user_id' => $iduser
));

// Fermeture du curseur de lecture pour éviter d'aloudir la ram
$query->closeCursor();

// l'internaute participe à un salon
$query = $bdd->prepare("
        INSERT INTO wpvotrechat_participation_salon (User_ID, Salon)
        VALUES(:user_id, :salon)
        ");
$query->execute(array(
    'user_id' => $iduser,
    'salon' => $salonuser
));

// Fermeture du curseur de lecture pour éviter d'aloudir la ram
$query->closeCursor();


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
