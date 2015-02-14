<?php
/**
 * Plugin Name: formulaire-simple
 * Plugin URI: -
 * Description: c'est un formulaire d'envoie de message au service client, à partir de wordpress.
 * Version: 1.0.0
 * Author: ADOUKO Franck Venance
 * Author URI: http://www.challinge.net
 * Text Domain: Optional. Plugin's text domain for localization. Example: mytextdomain
 * Domain Path: Optional. Plugin's relative directory path to .mo files. Example: /locale/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: GPL2
 */

add_action( 'wp_footer', 'ajout_de_boostrap' );

function ajout_de_boostrap(){
    $output = ' ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"><?php ';

    print $output;

}

add_filter( 'the_content', 'affichageFormulaire' );

function affichageFormulaire($content)
{

    // Elaboration du formulaire
    if ( is_page( 'contacts' )){


        // Code d'envoei bein réalisé à écrire

        $output = '

<form class="form-style-9" action="http://wp.challinge.net/new-site-cabira/wp-admin/admin-post.php" method="post">
                        <input type="hidden" name="action" value="send_mail">
                        <input type="hidden" name="data" value="foobarid">
<ul>
<li>
   <input type="text" class="field-style field-full align-none" name="nom" placeholder="Nom">

</li>
<li>
   <input type="email" name="email" class="field-style field-full align-none" id="exampleInputEmail1" placeholder="Email">
</li>
<li>
<input type="text"  class="field-style field-full align-none" placeholder="Sujet" name="sujet">
</li>
<li>
<textarea name="field5" class="field-style" placeholder="Message" rows="10"></textarea>
</li>
<li>
<input type="submit" value="Envoyer" />
</li>
</ul>
</form>

            <?php ';

        $content = $content.$output;
    }

    return $content;

}

// CRUD sur les valeurs des champs du formulaire servant à enregistrer l'image
add_action( 'admin_post_send_mail', 'prefix_admin_send_mail' );

function prefix_admin_send_mail(){
    $headers[] = 'From: '.$_REQUEST['email'];
    wp_mail( 'fvadouko@live.fr', $_REQUEST['sujet'], $_REQUEST['message'], $headers );
    $goback = add_query_arg( 'settings-updated', 'true',  wp_get_referer() );
    wp_redirect( $goback );
    exit;
}