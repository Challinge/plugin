<?php

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <script type="text/javascript" src="js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript" src="js/implementation.js"></script>
    </head>
    <body>
        <form method="post" action="envoie-mail.php" enctype="multipart/form-data">
            <label for="expediteur">Expéditeur :</label>
            <input name="expediteur" id="expediteur" style="width:100%" type="text"
            <label for="destinataire">Destinataire(s) :</label>
            <input name="destinataire" id="destinataire" style="width:100%" type="text">
            <label for="sujet">Sujet :</label>
            <input name="sujet" id="sujet" style="width:100%" type="text">
            <textarea name="content" style="width:100%"></textarea>
            <input type="hidden" name="MAX_FILE_SIZE" value="204800">
            <input type="file" name="fichier" size=40><br/>
            <button type="submit">Envoyer</button>
        </form>
    </body>
</html>
