<?php
session_start();
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 10/03/2015
 * Time: 15:07
 */

session_destroy();
$query = $bdd->prepare("DELETE FROM chat_messages WHERE timestamp < :time");
$query->execute(array(
    'time' => $time_out
));