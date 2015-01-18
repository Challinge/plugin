<?php
/**
 * Plugin name: external-api-twitter
 * Description: dialog with twitter.1.1, from our website, to get name and author of any api on wordpress.org
 * Version: 1.0
 * Author: ADOUKO Franck Venance
 * AUTHOR URI : http://www.challinge.net
 */

/*
   Links using for wp remote get whih is for the dialog :
   http://codex.wordpress.org/WordPress.org_API
   https://api.wordpress.org/plugins/info/1.0/{slug}.json
   http://codex.wordpress.org/HTTP_API
   https://dev.twitter.com/
 */

//parameters from twitter to have the user's credentials. We get them because i created a basic app on my twitter account, on https://dev.twitter.com/
define( 'PUBLIC_KEY', 'e2er7QHvnQKmRw6loNFWupdsE');
define( 'SECRET_KEY', 'JqJOCIRaXVnEA5QyVKKD5BC7MJZp2e52n96etvGNRUOTPcBp9N');

add_action( 'wp_footer', '_wp_dialog_twitter_token');
function _wp_dialog_twitter_token(){

    // Key encoding
    $auth           = base64_encode( PUBLIC_KEY.':'.SECRET_KEY );

    // What wp_remote_post needs
    $args  = array(
        'method'        => 'POST',
	    'httpversion'   => '1.0',

        // twitter needs to connect to its API:
        'headers'    => array(
            'Authorization' => 'Basic '. $auth,
            'Content-Type'  => 'application/x-www-form-urlencoded;charset=UTF-8'
        ),

        // Important parameter
        'body'     => array(
            'grant_type' => 'client_credentials'
        )
    );

    // The request to obtain the user's credentials to connect to twitter
    $url   = "https://api.twitter.com/oauth2/token";

    // Call twitter API
    $call  = wp_remote_post( $url, $args );
    $access_token = wp_remote_retrieve_body($call);
    //echo $access_token;

    return $save_token = ( is_object($access_token) && property_exists($access_token, 'access_token') ) ? $access_token->access_token : 'un problème est survenu avec Twitter, le token n\'a pas été généré!';

}