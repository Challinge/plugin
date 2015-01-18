<?php
/**
 * Plugin Name: handle-nmi-ifs
 * Plugin URI: -
 * Description: handle nmi and ifs.
 * Version: 1.0.0
 * Author: ADOUKO Franck Venance
 * Author URI: http://www.challinge.net
 * Text Domain: Optional. Plugin's text domain for localization. Example: mytextdomain
 * Domain Path: Optional. Plugin's relative directory path to .mo files. Example: /locale/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: GPL2
 */

// First of all Need to activate the plugins : infusionsoft sdk, infusionsoft developpers plugin
add_action( 'admin_post_add_foobar', 'prefix_admin_add_foobar' );
//add_action( 'wp_footer', 'prefix_admin_add_foobar'); // for checking
function prefix_admin_add_foobar() {
    status_header(200);

    //******************** HANDLE NMI ********************//
    $gw = new gwapi;
    $gw->setLogin("demo", "password");
    $gw->setBilling($_REQUEST['firstname'], $_REQUEST['lastname'], $_REQUEST['firstname'] ."-".$_REQUEST['lastname'], $_REQUEST['addressone'], $_REQUEST['addresstwo'], $_REQUEST['city'],
        $_REQUEST['state'], $_REQUEST['zipcode'], "US", $_REQUEST['phone'], $_REQUEST['phone'], $_REQUEST['emailaddress'],
        $_REQUEST['emailaddress']);
    $gw->setShipping($_REQUEST['firstname'], $_REQUEST['lastname'], $_REQUEST['firstname'] ."-".$_REQUEST['lastname'], $_REQUEST['addressone'], $_REQUEST['addresstwo'], $_REQUEST['city'],
        $_REQUEST['zipcode'], $_REQUEST['emailaddress'], "US", $_REQUEST['emailaddress']);
    $gw->setOrder("1234", "Big Order", 1, 2, "PO1234", $_SERVER['REMOTE_ADDR']);

    $r = $gw->doSale("0.2", "4111111111111111", "1025", "999");
    print $gw->responses['responsetext'];

    //******************** REGISTER USER TO INFUSIONSOFT ********************//
    // FROM <form action="https://loweryourtuition.org/landing-page/wp-admin/admin-post.php" method="post">
    // Load the Infusionsoft API class
    require_once('duisdk/src/isdk.php');

    $ifs = new iSDK();

    $ifs->cfgCon("xp180", "219753c29b8ac0b7ed594fb97fa447fe");

    $conDat = array('FirstName' => $_REQUEST['firstname'],
        'LastName'  => $_REQUEST['lastname'],
        'Email'     => $_REQUEST['emailaddress']);

    $conID = $ifs->addCon($conDat);

    print 'Contact added : '.$conID. '<br />';

    // if you want to add another method like a campaign do it as above : $contact = $ifs->campaign(your mehod, $data );

    //******************** CREATE A COMMISSION FOR AN AFFILIATE TO INFUSIONSOFT ********************//
    // Create an invoice
    $currentDate = date("d-m-Y");
    $oDate = $ifs->infuDate($currentDate);
    $invoiceId = $ifs->blankOrder("219753c29b8ac0b7ed594fb97fa447fe","Payment of a student", $oDate, 0, 0);

    //echo 'The id invoice : '.$invoiceId . '<br />'; // for checking

    // Create a payment : this one exits because it's useful if you accept cash/check, or handle payments outside of Infusionsoft.
    $pDate = $ifs->infuDate($currentDate);
    $operation = $ifs->manualPmt($invoiceId,9.95,$pDate,$_REQUEST['cardtype'],'$'. $invoiceId .' paid by '. $_REQUEST['cardtype'],false);
    if ($operation) {
        print 'Manual Payment Successful'. '<br />';
    } else {
        print 'Manual Payment Failed'. '<br />';
    }

    // Creates a commission on an existing invoice
     $pageID = get_the_ID();
    global $wpdb;
    $affiliateId='';
    $anAffiliate = $wpdb->get_row("SELECT idaffiliate, productid, amount, percentage FROM wpifsnmmi_handleaffiliates WHERE idpage=".$pageID, ARRAY_A);

    if ( $anAffiliate ) {
        $affiliateId = $anAffiliate['idaffiliate'];
        $productId = $anAffiliate['productid'];
        $amount = $anAffiliate['amount'];
        $percentage = $anAffiliate['percentage'];

        /* For Checking
         * echo 'id affiliate :'.$affiliateId. '<br />';
        echo 'id product :'.$productId. '<br />';
        echo 'id amount :'.$amount. '<br />';
        echo 'percentage :'.$percentage. '<br />';
        */
    }

    $cDate = $ifs->infuDate($currentDate);
    // Change the id affiliate, the id product  the commission and the percentage from option page
    $operation = $ifs->commOverride($invoiceId,$affiliateId,$productId,$percentage,$amount,5,"No Description",$cDate);
    if ($operation) {
        print 'Commission being added Successful'. '<br />';
    } else {
        print 'Commission Failed'. '<br />';
    }

}
