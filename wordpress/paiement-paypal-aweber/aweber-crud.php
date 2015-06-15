<?php
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 25/02/2015
 * Time: 19:06
 */

require_once('aweber_api/aweber_api.php');

function ajout_abonne($mail, $nom, $prenom){
    $name = $nom.' '.$prenom;

    $consumerKey = ""; # Put your consumer key here

    $consumerSecret = ""; # Put your consumer secret key here

    $accessKey      = 'AgdBXUi1ushwfLCJgJ1MVhRi'; # put your credentials here
    $accessSecret   = 'bJm1j52ALvtpsn3OB0zRwhy6e5OUWvtllonn5a8q'; # put your credentials here
    $account_id     = 'df5c681d'; // put the Account ID here
    $list_id        = '3432906'; // put the List ID here

    $aweber = new AWeberAPI($consumerKey, $consumerSecret);

    function handle_errors(&$exc) {
        // a simple way to display an API exception
        print "<h3>AWeberAPIException:</h3>";
        print " <li> Type: $exc->type              <br>";
        print " <li> Msg : $exc->message           <br>";
        print " <li> Docs: $exc->documentation_url <br>";
        print "<hr>";
        print "<hr>";
    }

    try {
        $account = $aweber->getAccount($accessKey, $accessSecret);
        $account_id = $account->id;

        $list = $account->loadFromUrl("/accounts/{$account_id}/lists/{$list_id}");

        # lets create some custom fields on your list!
        $params = array(
            'email' => $mail,
            'name' => $nom.' '. $prenom
        );
        $subscribers = $list->subscribers;
        $new_subscriber = $subscribers->create($params);

    } catch(AWeberAPIException $exc) {
        handle_errors($exc);
        exit(1);
    }

}