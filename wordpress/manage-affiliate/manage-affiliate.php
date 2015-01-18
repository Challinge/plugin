<?php
/**
 * Plugin Name: manage-affiliate
 * Plugin URI: -
 * Description: crud on affiliate and page.
 * Version: 1.0.0
 * Author: ADOUKO Franck Venance
 * Author URI: http://www.challinge.net
 * Text Domain: Optional. Plugin's text domain for localization. Example: mytextdomain
 * Domain Path: Optional. Plugin's relative directory path to .mo files. Example: /locale/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: GPL2
 */

add_action( 'admin_post_crud_affiliate', 'prefix_admin_crud_affiliate' );
function prefix_admin_crud_affiliate()
{

    status_header(200);
    //die("Server received '{$_REQUEST['idpage']}' from your browser.");

    global $wpdb;

    $table_name = $wpdb->prefix . 'handleaffiliates';

    $wpdb->insert(
        $table_name,
        array(
            'timeadded' => current_time('mysql'),
            'idpage' => $_REQUEST['idpage'],
            'idaffiliate' => $_REQUEST['idaffiliate'],
            'productid' => $_REQUEST['idproduct'],
            'amount' => $_REQUEST['amount'],
            'percentage' => $_REQUEST['percentage']
        )
    );

    $goback = add_query_arg( 'settings-updated', 'true',  wp_get_referer() );
    wp_redirect( $goback );
    exit;
}

// First of all Need to activate the plugins : infusionsoft sdk, infusionsoft developpers plugin
//add_action( 'admin_post_add_foobar', 'prefix_admin_add_foobar' );
/*add_action( 'admin_post_add_affiliate', 'prefix_admin_add_foobar' );
function prefix_admin_add_foobar()
{
    echo $_REQUEST['idpage'];
    global $wpdb;

    $table_name = $wpdb->prefix . 'handleaffiliates';

    $wpdb->insert(
        $table_name,
        array(
            'timeadded' => current_time('mysql'),
            'idpage' => '12',
            'idaffiliate' => 'ebete',
            'productid' => 0,
            'amount' => 0.0,
            'percentage' => 0
        )
    );
}
/*
// CONNEXION to the database
include_once('ConnBdd.class.php');
$bd = new ConnBdd('challing_coursera');
$bdd = $bd -> getBdd();

// INSERT in the dataBase
$reqinsert = $bdd->prepare('INSERT INTO wpifsnmmi_handleaffiliates(timeadded, idpage, idaffiliate, productid, amount, percentage) VALUES (:timeadded, :idpage, :idaffiliate, :productid, :amount, :percentage)');

$reqinsert->execute(array(
    'timeadded' => current_time('mysql'),
    'idpage' => '12',
    'idaffiliate' => 'ebete',
    'productid' => 0,
    'amount'=> 0.0,
    'percentage'=> 0
));


//    wp_redirect( home_url() );
//    exit;

//*/

