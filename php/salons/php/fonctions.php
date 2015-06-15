<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 09/03/2015
 * Time: 12:48
 */

function bdd_connect() {
    $dsn = 'mysql:dbname=challing_coursera;host=localhost';
    $user = 'challing_adoukof';
    $password = 'kHv&P-pkCSr98vsf';
    try {
        $bdd = new PDO($dsn, $user, $password);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo 'Échec lors de la connexion : ' . $e->getMessage();
    }
    return $bdd;
}

function is_user_connect($pseudo) {
    $bdd = bdd_connect();

    $ip = $_SERVER["REMOTE_ADDR"];
    $query = $bdd->prepare('
      SELECT * FROM wpvotrechat_chat_online WHERE online_user = :pseudo
      ');
    $query->execute(array(
        'pseudo' => $pseudo,

    ));
    $count = $query->rowCount();
    if ($count == 0) {
        $is_user_connect = false;
    }
    else {
        $is_user_connect = true;
    }
    return $is_user_connect;
}

// Utilisateur connecté et insérer dans la table
function user_connect($ip, $pseudo) {
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $ip = $_SERVER["REMOTE_ADDR"];
    $bdd = bdd_connect($ip, $pseudo);
    $query = $bdd->prepare("
        INSERT INTO wpvotrechat_chat_online (online_ip, online_user, online_time)
        VALUES(:online_ip, :online_user, :online_time)
        ");
    $query->execute(array(
        'online_ip' => $ip,
        'online_user' => $pseudo,
        'online_time' => time(),
    ));
}