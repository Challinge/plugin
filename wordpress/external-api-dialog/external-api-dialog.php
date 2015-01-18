<?php
/**
 * Plugin name: external-api-dialog
 * Description: dialog with external api, from our website
 * Version: 1.0
 * Author: ADOUKO Franck Venance
 * AUTHOR URI : http://www.challinge.net
 */

/*
   Links using for wp remote get whih is for the dialog :
   http://codex.wordpress.org/WordPress.org_API
   https://api.wordpress.org/plugins/info/1.0/{slug}.json
   http://codex.wordpress.org/HTTP_API
 */

// get data from the plugin hello-dolly and different plugins
function _wp_get_org_datas($slug){

    $args = array(
        'timeout'     => 120,
        'httpversion' => '1.1'
    );

    // third we use a cache system because, certain websites has a quota of requests for the same website.
    // so we use get_site_transients for the multi site in case of there multiple websites
    $cached     = get_site_transient( "https://api.wordpress.org/plugins/info/1.0/{$slug}.json" );

    // if my cache system is expired or if it's a first use then i will get the infos from the uri :
    if ( $cached === false ) {

        // test this uri in the browser https://api.wordpress.org/plugins/info/1.0/hello-dolly.json to show data from this plugin
        //  i launch my request
        $url = "https://api.wordpress.org/plugins/info/1.0/{$slug}.json";
        $cached = wp_remote_get( $url, $args );

        // To avoid $call = '', i put in cache
        set_site_transient( "https://api.wordpress.org/plugins/info/1.0/{$slug}.json", $cached, DAY_IN_SECONDS );
    }

    // third we sent a response to the JSON
    $response = json_decode( wp_remote_retrieve_body( $cached ) );

    return $response;

    // Second we get the body retrieves from all data
    /*
    $response  = json_decode( wp_remote_retrieve_body( $call ) );
    echo '<pre>';
    echo print_r( $response );
    echo '</pre>';
    */

    //first we get all data
    /*
    echo '<pre>';
    echo print_r( $call );
    echo '</pre>';
    */
}

// format the data
//add_action( 'wp_footer', '_output_wp_org_data' );
// add a shortcode instead of add action (it was for test)
add_shortcode( 'plugin_infos', '_output_wp_org_data' );
function _output_wp_org_data( $atts ) {

    $a  =  shortcode_atts(
        array(
            'slug' => 'hello-dolly',
        ),
        $atts,
        'plugin_info_shortcode'
    );

    // get response from _wp_get_org_datas
    $datas  =   _wp_get_org_datas($a['slug']);

    // display name, author
    $output   =  'Name of plugin : '. $datas->name;
    $output  .=  '<br />Author of this plugin : '. $datas->author;

    // first echo to the wp_footer
    //echo $output;

    // Since it's now an add_shortcode action which is behaved as an add_filter, so :
    return $output;
}