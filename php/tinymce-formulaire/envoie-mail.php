<?php
if (isset($_POST['destinataire'])) {
    $differentMails = explode(";", $_POST['destinataire']);

    $chemin_destination = 'fichiers/';

    move_uploaded_file($_FILES['fichier']['tmp_name'], $chemin_destination . $_FILES['fichier']['name']);

    foreach($differentMails as $differentMail) {

        $mail = $differentMail; // Déclaration de l'adresse de destination.
        if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $mail)) // On filtre les serveurs qui présentent des bogues.
        {
            $passage_ligne = "\r\n";
        } else {
            $passage_ligne = "\n";
        }
//=====Déclaration des messages au format texte et au format HTML.
        //$message_txt = "Salut à tous, voici un e-mail envoyé par un script PHP.";
        $message_html = "<html><head></head><body>".$_POST['content']."</body></html>";
//==========

//=====Lecture et mise en forme de la pièce jointe.
        $fichier = fopen($chemin_destination . $_FILES['fichier']['name'], "r");
        $attachement = fread($fichier, filesize($chemin_destination . $_FILES['fichier']['name']));
        $attachement = chunk_split(base64_encode($attachement));
        fclose($fichier);
//==========

//=====Création de la boundary.
        $boundary = "-----=" . md5(rand());
        $boundary_alt = "-----=" . md5(rand());
//==========

//=====Définition du sujet.
        $sujet = $_POST['sujet'];
//=========

//=====Création du header de l'e-mail.
        $header = "From: " . $_POST['expediteur'] . $passage_ligne;
        $header .= "Reply-to:". $_POST['expediteur'] . $passage_ligne;
        $header .= "MIME-Version: 1.0" . $passage_ligne;
        $header .= "Content-Type: multipart/mixed;" . $passage_ligne . " boundary=\"$boundary\"" . $passage_ligne;
//==========

//=====Création du message.
        $message = $passage_ligne . "--" . $boundary . $passage_ligne;
        $message .= "Content-Type: multipart/alternative;" . $passage_ligne . " boundary=\"$boundary_alt\"" . $passage_ligne;
        $message .= $passage_ligne . "--" . $boundary_alt . $passage_ligne;
//=====Ajout du message au format texte.
        $message .= "Content-Type: text/plain; charset=\"ISO-8859-1\"" . $passage_ligne;
        $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
        $message .= $passage_ligne . $message_txt . $passage_ligne;
//==========

        $message .= $passage_ligne . "--" . $boundary_alt . $passage_ligne;

//=====Ajout du message au format HTML.
        $message .= "Content-Type: text/html; charset=\"UTF-8\"" . $passage_ligne;
        $message .= "Content-Transfer-Encoding: 8bit" . $passage_ligne;
        $message .= $passage_ligne . $message_html . $passage_ligne;
//==========

//=====On ferme la boundary alternative.
        $message .= $passage_ligne . "--" . $boundary_alt . "--" . $passage_ligne;
//==========


        $message .= $passage_ligne . "--" . $boundary . $passage_ligne;

//=====Ajout de la pièce jointe.
        $message .= "Content-Type: " . $_FILES['fichier']['type'] . "; name=\"" . $chemin_destination . $_FILES['fichier']['name'] . "\"" . $passage_ligne;
        $message .= "Content-Transfer-Encoding: base64" . $passage_ligne;
        $message .= "Content-Disposition: attachment; filename=\"" . $chemin_destination . $_FILES['fichier']['name'] . "\"" . $passage_ligne;
        $message .= $passage_ligne . $attachement . $passage_ligne . $passage_ligne;
        $message .= $passage_ligne . "--" . $boundary . "--" . $passage_ligne;

//==========
    }
}
//=====Envoi de l'e-mail.
//=====Envoi de l'e-mail.
if (mail($mail, $sujet, $message, $header)) {
    echo 'E-mail envoyé avec succès';

} else {
    echo  'Erreur d\'envoi de l\'e-mail';
}

//==========

//==========
