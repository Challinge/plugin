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
//add_action( 'admin_post_add_foobar', 'prefix_admin_add_foobar' );
add_action( 'wp_footer', 'prefix_admin_add_foobar'); // for checking
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

    print $gw->responses['responsetext']. '<br />';

    // if payment is not successful, there is no meaning of adding data in the IFS
    if ( $gw->responses['responsetext'] == 'DECLINE') {
        print 'Payment succeded in NMI' . '<br />';


        //******************** REGISTER USER TO INFUSIONSOFT ********************//
        // FROM <form action="https://loweryourtuition.org/landing-page/wp-admin/admin-post.php" method="post">
        // Load the Infusionsoft API class
        require_once('duisdk/src/isdk.php');

        $ifs = new iSDK();

        $ifs->cfgCon("xp180", "219753c29b8ac0b7ed594fb97fa447fe");

        $conDat = array('FirstName' => $_REQUEST['firstname'],
            'LastName' => $_REQUEST['lastname'],
            'Email' => $_REQUEST['emailaddress']);

       $conID = $ifs->addCon($conDat);

        print 'Contact added : ' . $conID . '<br />';

        // if you want to add another method like a campaign do it as above : $contact = $ifs->campaign(your mehod, $data );

        //******************** CREATE A COMMISSION FOR AN AFFILIATE TO INFUSIONSOFT ********************//
        /*
        $programs = $ifs->etResourcesForAffiliateProgram(24);
        foreach( $programs as $cle => $element)
        {
            foreach ( $element as $key => $value){
                echo '[' . $key . '] vaut ' . $value . '<br />';
            }

        }
        */
        /*
        echo '<pre>';
        print_r($programs);
        echo '</pre>';*/

        /*
        $result = $ifs->getResourcesForAffiliateProgram(6);

        echo '<pre>';
        print_r($result);
        echo '</pre>';
        */
        /*
        $returnFields = array('Id','NotifySale', 'NotifyLead','PayoutType');
        $query = array('NotifySale' => '1');
        $affiliate = $ifs->dsQuery("Affiliate",10,0,$query,$returnFields);
        */
        /*
        $returnFields = array('Id','PlanPrice', 'ProductId');
        $affiliate = $ifs->dsFind('SubscriptionPlan',5,0,'Id',2,$returnFields);
        */

        /* get id of the product
        $returnFields = array('Id','ProductPrice', 'ProductName');
        $query = array('ProductName' => 'Annual Membership LYT');
        $affiliate = $ifs->dsQuery("Product",10,0,$query,$returnFields);
        */

        /*
        $affiliateIds = 24;
        $start = date('Ymd\TH:i:s',mktime(00,00,00,9,01,2013));
        $finish = date('Ymd\TH:i:s',mktime(00,00,00,1,01,2015));
        $affiliate = $ifs->affPayouts($affiliateIds,$start,$finish);
        */

        /* get all commission for a specific affiliate
        $affiliateIds = 24;
        $start = date('Ymd\TH:i:s',mktime(00,00,00,9,01,2013));
        $finish = date('Ymd\TH:i:s',mktime(00,00,00,1,01,2015));
        $affiliate = $ifs->affCommissions($affiliateIds,$start,$finish);*/

        // to get all invoices for a specific affiliate
    /*

         $returnFields = array('Id','DateCreated', 'Description', 'Synced','ProductSold', 'TotalPaid', 'TotalDue', 'LeadAffiliateId');
        $query = array('AffiliateId' => '24');
        $affiliate = $ifs->dsQuery("Invoice",200,0,$query,$returnFields);

        echo '<pre>';
        print_r($affiliate);
        echo '</pre>';

        /*
        $affiliate = $ifs->getPayments(300);

        echo '<pre>';
        print_r($affiliate);
        echo '</pre>';
*/
        /*
        $returnFields = array('Id','Amt', 'InvoiceId', 'PayDate','PayStatus', 'PaymentId');
        $query = array('InvoiceId' => '300');
        $affiliate = $ifs->dsQuery("InvoicePayment",200,0,$query,$returnFields);
        echo '<pre>';
        print_r($affiliate);
        echo '</pre>';
        /*
         for($i=478; $i<=546; $i++){
             $ifs->deleteInvoice($i);
         }*/

        /*
        $affiliate = $ifs->getPayments(300);

        echo '<pre>';
        print_r($affiliate);
        echo '</pre>';

        $affiliate = $ifs->getPayments(556);

        echo '<pre>';
        print_r($affiliate);
        echo '</pre>';
*/
        // Search an affiliateId from pageId
        $pageID = get_the_ID();
        global $wpdb;
        $affiliateId = '';
        $anAffiliate = $wpdb->get_row("SELECT idaffiliate, tagone, tagtwo FROM wpifsnmmi_handleaffiliates WHERE idpage=" . $pageID, ARRAY_A);

        if ($anAffiliate) {
            $affiliateId = $anAffiliate['idaffiliate'];
            $tagone = $anAffiliate['tagone'];
            $tagtwo = $anAffiliate['tagtwo'];

            /* For Checking
             * echo 'id affiliate :'.$affiliateId. '<br />';
            echo 'id product :'.$productId. '<br />';
            echo 'id amount :'.$amount. '<br />';
            echo 'percentage :'.$percentage. '<br />';
            */
        }

        // Create a blank order :  a one-time order with no added line items
        $currentDate = date("d-m-Y");
        $oDate = $ifs->infuDate($currentDate);
        $invoiceId = $ifs->blankOrder($conID, "Payment of a student", $oDate, $anAffiliate, $anAffiliate);

        echo 'The id invoice : '.$invoiceId . '<br />'; // for checking

        // create a line in this blank order
        $result = $ifs->addOrderItem($invoiceId, 20, 6, 155.4, 1, "Turbo Booster", "Jay's Awesome Turbo Booster");

        echo 'The line order was created : '.$result . '<br />'; // for checking

        // Create a payment : this one exits because it's useful if you accept cash/check, or handle payments outside of Infusionsoft.
        $pDate = $ifs->infuDate($currentDate);
        $operation = $ifs->manualPmt($invoiceId, 155.4, $pDate, 'Credit Card','$12.95 paid by Credit Card', true);
        if ($operation) {
            print 'Manual Payment Successful' . '<br />';
        } else {
            print 'Manual Payment Failed' . '<br />';
        }

        // Creates a commission on an existing invoice


        $cDate = $ifs->infuDate($currentDate);
        // Change the id affiliate, the id product the percentage the commission and the percentage from option page
        $operation = $ifs->commOverride($invoiceId, $anAffiliate, 20, 22, 35.4, 5, "No Description", $cDate);
        if ($operation) {
            print 'Commission being added Successful' . '<br />';
        } else {
            print 'Commission Failed' . '<br />';
        }

        $affiliateIds = 24;
        $start = date('Ymd\TH:i:s',mktime(00,00,00,9,12,2014));
        $finish = date('Ymd\TH:i:s',mktime(14,00,00,6,01,2015));
        $affiliate = $ifs->affCommissions($affiliateIds,$start,$finish);

        echo '<pre>';
        print_r($affiliate);
        echo '</pre>';

            /*
        $carray = array(
            php_xmlrpc_encode($ifs->key),
            php_xmlrpc_encode(554),
            php_xmlrpc_encode(1),
            php_xmlrpc_encode(1),
            php_xmlrpc_encode(array(20, 4)),
            php_xmlrpc_encode(array(20, 4)),
            php_xmlrpc_encode(1),
            php_xmlrpc_encode(array(10, 1)) // array of strings
        );
        $operation = $ifs->methodCaller("OrderService.placeOrder", $carray);

        //$operation = $ifs->placeOrder(554,0,0,array(20,4),array(20,4),1,array("mmmm","mmmmmm"),24,24);

        echo '<pre>';
        print_r($operation);
        echo '</pre>';
            */
    }

}
