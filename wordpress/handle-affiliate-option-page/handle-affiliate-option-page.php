<?php
/**
 * Plugin Name: handle-affiliate-option-page
 * Plugin URI: -
 * Description: handling the id affiliate and the id page in the option page of wordpress backend.
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

    $table_name = $wpdb->prefix . 'handleaffiliates';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		timeadded datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		idpage Text NOT NULL,
		idaffiliate Text NOT NULL,
		tagone Text,
		tagtwo Text,
		UNIQUE KEY id (id)
	) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql );

    add_option( 'jal_db_version', $jal_db_version );
}

// Adding the menu in the back-end down the dashboard
add_action( 'admin_menu', 'menu_option_page_handle_affiliate');

function menu_option_page_handle_affiliate(){
    jal_install();

    add_menu_page(
        'Handling affiliates',
        'Handling affiliates',
        'manage_options',
        'admin-handle-affiliate',
        'admin_handle_affiliate',
        plugins_url( 'handle-affiliate-option-page/img/partner.png' ),
        3
    );
}

function admin_handle_affiliate(){
    //to display picture, you need it

?>
    <div class="wrap">

        <h2>Handling affiliates</h2>

        <?php
            if ($_REQUEST ["settings-updated"]==true){

            ?>
                <div id="setting-error-settings_updated" class="updated settings-error">
                    <p>
                        <strong>Values are recorded.</strong>
                    </p>
                </div>
            <?php
            }
        ?>

        <form action="http://coursera.challinge.net/ifs/wp-admin/admin-post.php" method="post">
            <input type="hidden" name="action" value="crud_affiliate">
            <input type="hidden" name="data" value="foobarid">


            <table class="form-table">
                <tbody>
                    <tr>
                        <th scope="row">
                            <label for="idpage">The id page</label>
                        </th>
                        <td>
                            <input name="idpage" id="idpage" class="regular-text" type="text">
                            <p class="description">Type the id of a wordpress page.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="idaffiliate">The id affiliate</label>
                        </th>
                        <td>
                            <input name="idaffiliate" id="idaffiliate" class="regular-text" type="text">
                            <p class="description">Type the id of an affiliate.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="tagone">The tag one</label>
                        </th>
                        <td>
                            <input name="tagone" id="tagone" class="regular-text" type="text">
                            <p class="description">Type the tag one for one campaign.</p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="tagtwo">The tage two</label>
                        </th>
                        <td>
                            <input name="tagtwo" id="tagtwo" class="regular-text" type="text">
                            <p class="description">Type the tag two for one campaign.</p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php submit_button('Add the fields values')?>
        </form>
    </div>
<?php

    global $wpdb;

    $listofAffiliates = $wpdb->get_results("SELECT * FROM wpifsnmmi_handleaffiliates ORDER BY timeadded DESC", ARRAY_A);
    if ( $listofAffiliates ) {
        ?>
        <table border="1" bordercolor="lightgray" cellpadding="10" cellspacing="0">
                <tr>
                    <td align='center'><b>The ID PAGE</b></td>
					<td align='center'><b>THE ID AFFILIAITE</b></td>
                    <td align='center'><b>The TAG ONE</b></td>
                    <td align='center'><b>THE TAG TWO</b></td>
                    <td align='center'><b>UPDATE</b></td>

                </tr>
         <?php
        foreach ($listofAffiliates as $listofAffiliate) {
         ?>
            <tr>
                <td align='center'><?php print ($listofAffiliate['idpage']); ?></td>
                <td align='center'><?php print ($listofAffiliate['idaffiliate']); ?></td>
                <td align='center'><?php print ($listofAffiliate['tagone']); ?></td>
                <td align='center'><?php print ($listofAffiliate['tagtwo']); ?></td>
                <td align='center'><button>Set the update</button></td>

            </tr>
            <?php

        }
         ?>
        </table>
    <?php
    }
}