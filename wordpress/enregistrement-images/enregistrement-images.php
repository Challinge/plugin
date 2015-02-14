<?php
/**
 * Plugin Name: enregistrement-images
 * Plugin URI: -
 * Description: crud sur les images dans un menu crée dans le back-end avec formulaire.
 * Version: 1.0.0
 * Author: ADOUKO Franck Venance
 * Author URI: http://www.challinge.net
 * Text Domain: Optional. Plugin's text domain for localization. Example: mytextdomain
 * Domain Path: Optional. Plugin's relative directory path to .mo files. Example: /locale/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: GPL2
 */


// Create the wordpress database table of handling the id affiliate and the id page
global $jal_db_version;
$jal_db_version = '1.0';
function jal_install() {

    global $wpdb;
    global $jal_db_version;

    $table_name = $wpdb->prefix . 'imagesdiv';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		idimage mediumint(9) NOT NULL AUTO_INCREMENT,
		nommenu Text NOT NULL,
		urlimage Text NOT NULL,
		positions INT,
		UNIQUE KEY idimage (idimage)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'jal_db_version', $jal_db_version );
}

// Adding the menu in the back-end down the dashboard
add_action( 'admin_menu', 'menu_enregistrement_images_div');

function menu_enregistrement_images_div(){
    jal_install();

    add_menu_page(
        'Enregistrement images',
        'Enregistrement images',
        'manage_options',
        'admin-enregistrement-images',
        'admin_enregistrement_images',
        plugins_url( '' ),
        3
    );
}

function admin_enregistrement_images(){
    //to display picture, you need it

    ?>
    <div class="wrap">

        <h2>Enregsitrement images</h2>

        <?php
        if ($_REQUEST ["settings-updated"]==true){

            ?>
            <div id="setting-error-settings_updated" class="updated settings-error">
                <p>
                    <strong>Les valeurs on été enregistrées.</strong>
                </p>
            </div>
        <?php
        }
        ?>

        <form action="http://wp.challinge.net/new-site-cabira/wp-admin/admin-post.php" method="post">
            <input type="hidden" name="action" value="crud_images">
            <input type="hidden" name="data" value="foobarid">
            <input type="hidden" name="lebouttonclique" id="lebouttonclique" value="">
            <input type="hidden" name="idimage" id="idimage" value="">

            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="nommenu">Nom du menu</label>
                    </th>
                    <td>
                        <input name="nommenu" id="nommenu" class="regular-text" type="text">
                        <p class="description">Saisisser le nom du menu.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="urlimage">L'url de l'image</label>
                    </th>
                    <td>
                        <input name="urlimage" id="urlimage" class="regular-text" type="text">
                        <p class="description">Saisisser l'url de l'image.</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="positions">Position</label>
                    </th>
                    <td>
                        <input name="positions" id="positions" class="regular-text" type="text">
                        <p class="description">Saisisser la position de l'image.</p>
                    </td>
                </tr>

                </tbody>
            </table>
            <table class="form-table">
                <tbody>
                <tr>
                    <td>
                        <button type="button" class="button button-primary" id='nouveau-img'>Nouvelle insertion</button>
                    </td>
                    <td>
                        <input name="submit" id="insertion-img" class="button button-primary" value="Insertion des valeurs" type="submit">
                    </td>
                    <td>
                        <input name="submit" id="modification-img" class="button button-primary" value="Modification des valeurs" type="submit">
                    </td>
                    <td>
                        <input name="submit" id="suppression-img" class="button button-primary" value="Suppression des valeurs" type="submit">
                    </td>
                </tr>
                </tbody>
            </table>

        </form>
    </div>
    <?php

    global $wpdb;
    $listeDimages = $wpdb->get_results("SELECT * FROM ". $wpdb->prefix . "imagesdiv" ." ORDER BY idimage DESC", ARRAY_A);
    if ( $listeDimages ) {
        ?>
        <table border="1" bordercolor="lightgray" cellpadding="10" cellspacing="0">
            <tr>
                <td align='center'><b>L'id de l'image</b></td>
                <td align='center'><b>Nom du menu</b></td>
                <td align='center'><b>Url de l'image</b></td>
                <td align='center'><b>Position</b></td>
                <td align='center'><b>Pour Modifier</b></td>
                <td align='center'><b>Pour Supprimer</b></td>

            </tr>
            <?php
            foreach ($listeDimages as $chaqueImage) {
                ?>
                <tr>
                    <td align='center' id='idimage-<?php print ($chaqueImage['idimage']); ?>'><?php print ($chaqueImage['idimage']); ?></td>
                    <td align='center' id='nommenu-<?php print ($chaqueImage['idimage']); ?>'><?php print ($chaqueImage['nommenu']); ?></td>
                    <td align='center' id='urlimage-<?php print ($chaqueImage['idimage']); ?>'><?php print ($chaqueImage['urlimage']); ?></td>
                    <td align='center' id='positions-<?php print ($chaqueImage['idimage']); ?>'><?php print ($chaqueImage['positions']); ?></td>
                    <td align='center' id='idimage-<?php print ($chaqueImage['idimage']); ?>'><button class="button button-primary" id='modifier-img-<?php print ($chaqueImage['idimage']); ?>'>Modifier</button></td>
                    <td align='center'><button class="button button-primary" id='supprimer-img-<?php print ($chaqueImage['idimage']); ?>'>Supprimer</button></td>
                </tr>
            <?php

            }
            ?>
        </table>
    <?php
        wp_reset_query();
    }
}

// CRUD sur les valeurs des champs du formulaire servant à enregistrer l'image
add_action( 'admin_post_crud_images', 'prefix_admin_crud_images' );

function prefix_admin_crud_images()
{

    status_header(200);
    //die("Server received '{$_REQUEST['idpage']}' from your browser.");

    global $wpdb;

    $table_name = $wpdb->prefix . 'imagesdiv';

    // Inserer si idimage n' a pas de valeur
    if ($_REQUEST['lebouttonclique']=='inse' ){
        $wpdb->insert(
            $table_name,
            array(
                'nommenu' => $_REQUEST['nommenu'],
                'urlimage' => $_REQUEST['urlimage'],
                'positions' => $_REQUEST['positions']
            )
        );
    }
    elseif ($_REQUEST['lebouttonclique']=='modi' ){
        $wpdb->update(
            $table_name,
            array(
                'nommenu' => $_REQUEST['nommenu'],
                'urlimage' => $_REQUEST['urlimage'],
                'positions' => $_REQUEST['positions']
            ),
            array( 'idimage' => $_REQUEST['idimage'] )
        );
    }
    elseif ($_REQUEST['lebouttonclique']=='supp' ){
        $wpdb->delete(
            $table_name,
            array( 'idimage' => $_REQUEST['idimage'] )
        );
    }


    $goback = add_query_arg( 'settings-updated', 'true',  wp_get_referer() );
    wp_redirect( $goback );
    exit;
}