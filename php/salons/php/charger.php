<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 11/03/2015
 * Time: 00:00
 */
require_once('fonctions.php');
$salonuser = htmlspecialchars($_POST['salonutilisateur']);
$bdd = bdd_connect();

$nombremsgss = 0;
$nombremsgscomptess = 0;

// Compte le nombre de messages
$reponse_countmsgss = $bdd->prepare('SELECT User_ID FROM wpvotrechat_chat_messages WHERE Salon = :salon');
$reponse_countmsgss->execute(array(
    'salon' => $salonuser
));

while ($donneess = $reponse_countmsgss->fetch()) {
    $nombremsgss++;
}

$nombremsgss = $nombremsgss - 10;
if ($nombremsgss <= 10) {
    $nombremsgss = 0;
}

// On récupère tous les messages qui se sont déroulé dans un salon
$reponse_useridd = $bdd->prepare('SELECT User_ID, message_text, timestamp FROM wpvotrechat_chat_messages WHERE Salon = :salon ORDER BY message_id ASC');
$reponse_useridd->execute(array("salon" => $salonuser));




while($donneess = $reponse_useridd->fetch()){

    $nombremsgscomptess++;
    if ($nombremsgscomptess >= $nombremsgss) {

        // On récupère le nom
        $reponse_usernamee = $bdd->query('SELECT account_login FROM wpvotrechat_chat_accounts WHERE User_ID = ' . $donneess['User_ID']);
        $usernamee = $reponse_usernamee->fetchColumn();

        // On récupère l'avatar du pseudo
        $reponse_images = $bdd->query('SELECT urlavatar FROM wpvotrechat_chat_accounts WHERE User_ID = ' . $donneess['User_ID']);
        $urlavatar = $reponse_images->fetchColumn();
        //$urlavatar = '../img/128.png';

        if ($odd) {

            // on affiche le message pour différencier le message avec odd
            echo '<li id="li-chat-content" class ="odd"><p><img src="'.$urlavatar .'" class="avt"><span class="user">' . $usernamee . ' :</span><span class="time">' . $donneess['timestamp'] . '</span></p>';
            echo '<p id="p-chat-content">' . $donneess['message_text'] . '</p></li>';
            $reponse_usernamee->closeCursor();
            $odd = false;
        }
        else {

            // on affiche le message
            echo '<li id="li-chat-content"><p><img src="'.$urlavatar .'" class="avt"><span class="user">' . $usernamee . ' :</span><span class="time">' . $donneess['timestamp'] . '</span></p>';
            echo '<p id="p-chat-content">' . $donneess['message_text'] . '</p></li>';
            $reponse_usernamee->closeCursor();
            $odd = true;
        }

        // Fermeture du curseur de lecture pour éviter d'aloudir la ram
        $reponse_images->closeCursor();

        // Fermeture du curseur de lecture pour éviter d'aloudir la ram
        $reponse_usernamee->closeCursor();
    }
}

// Fermeture du curseur de lecture pour éviter d'aloudir la ram
$reponse_userid->closeCursor();