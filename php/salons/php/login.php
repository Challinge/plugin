<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 09/03/2015
 * Time: 12:33
 */
?>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>

    <body>
        <form method="post" action="traitement-login.php">
            <center>
                <table>

                    <tr>
                        <td>Pseudo :<input type="text" name="pseudo" required /></td>
                    </tr>
                    <tr>
                        <td>Mot de passe :
                        <input type="password" name="password" placeholder="****" maxlength="255" size="35" required />
                        </td>
                    </tr>
                    <tr><td><input type="submit" value="Se Connecter !" /></td></tr>
                </table>
            </center>
            </form>
    </body>
</html>