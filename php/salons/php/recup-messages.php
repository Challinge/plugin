<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 10/03/2015
 * Time: 21:19
 */

require_once('fonctions.php');

$iduser = htmlspecialchars($_POST['idutilisateur']);
$salonuser = htmlspecialchars($_POST['salonutilisateur']);
$messages = htmlspecialchars($_POST['messageutilisateur']);

$bdd = bdd_connect();

// l'internaute enregistre son message
$query = $bdd->prepare("
        INSERT INTO wpvotrechat_chat_messages(message_text, User_ID, Salon, timestamp)
        VALUES(:message_text, :user_id, :salon, :timestamp)
        ");
$query->execute(array(
    'message_text' => $messages,
    'user_id' => $iduser,
    'salon' => $salonuser,
    'timestamp' => date("d.m.Y H:i:s")
));

// Fermeture du curseur de lecture pour éviter d'aloudir la ram
$query->closeCursor();

$nombremsgs = 0;
$nombremsgscomptes = 0;

// Compte le nombre de messages
$reponse_countmsgs = $bdd->prepare('SELECT User_ID FROM wpvotrechat_chat_messages WHERE Salon = :salon');
$reponse_countmsgs->execute(array(
    'salon' => $salonuser
));

while ($donnees = $reponse_countmsgs->fetch()) {
    $nombremsgs++;
}

$nombremsgs = $nombremsgs - 5;
if ($nombremsgs <= 5) {
    $nombremsgs = 0;
}

// On récupère tous les messages qui se sont déroulé dans un salon
$reponse_useridd = $bdd->prepare('SELECT User_ID, message_text, timestamp FROM wpvotrechat_chat_messages WHERE Salon = :salon ORDER BY message_id ASC ');
$reponse_useridd->execute(array("salon" => $salonuser));

// Pour différencier les messages
$odd = true;

while($donneess = $reponse_useridd->fetch()){
    $nombremsgscomptes++;
    if ($nombremsgscomptes >= $nombremsgs) {
        // On récupère le nom
        $reponse_usernamee = $bdd->query('SELECT account_login FROM wpvotrechat_chat_accounts WHERE User_ID = ' . $donneess['User_ID']);
        $usernamee = $reponse_usernamee->fetchColumn();

        // On récupère l'avatar du pseudo
        $reponse_images = $bdd->query('SELECT urlavatar FROM wpvotrechat_chat_accounts WHERE User_ID = ' . $donneess['User_ID']);
        $urlavatar = $reponse_images->fetchColumn();

        if ($odd) {

            // on affiche le message pour différencier le message avec odd
            echo '<li id="li-chat-content" class ="odd"><p><img src="'.$urlavatar.'" class="avt"><span class="user">' . $usernamee . ' :</span><span class="time">' . $donneess['timestamp'] . '</span></p>';
            echo '<p id="p-chat-content">' . $donneess['message_text'] . '</p></li>';
            $reponse_usernamee->closeCursor();
            $odd = false;
        }
        else {

            // on affiche le message
            echo '<li id="li-chat-content"><p><img src="'.$urlavatar.'" class="avt"><span class="user">' . $usernamee . ' :</span><span class="time">' . $donneess['timestamp'] . '</span></p>';
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