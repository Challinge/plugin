<?php
/**
 * Plugin Name: pixlr-editor-website.
 * Plugin URI: -
 * Description: Edit an image.
 * Version: 1.0.0
 * Author: ADOUKO Franck Venance
 * Author URI: http://www.challinge.net
 * Text Domain: Optional. Plugin's text domain for localization. Example: mytextdomain
 * Domain Path: Optional. Plugin's relative directory path to .mo files. Example: /locale/
 * Network: Optional. Whether the plugin can only be activated network wide. Example: true
 * License: GPL2
 */

// add script to load the API : pixlr
add_action( 'wp_head', 'add_script_pixlr');
function add_script_pixlr () {
    ?>

    <script type="text/javascript" src="http://jokesandpictures.com/wp-content/plugins/pixlr-editors/pixlr.js"></script>
    <script type="text/javascript">
        pixlr.settings.target = 'http://jokesandpictures.com/';
        pixlr.settings.exit = 'http://jokesandpictures.com/';
        pixlr.settings.credentials = true;
        pixlr.settings.method = 'post';

    </script>

<?php
}

// load pixlr after the page is loaded
add_action( 'wp_footer', '_display_pixlr_to_a_page' );
function _display_pixlr_to_a_page() {
    global $post;
    $post_id = $post->ID;
    if ( is_user_logged_in() && $post_id == 1702 ){
        ?>
        <script type="text/javascript">
            pixlr.overlay.show({image:'', title:'', service:'editor'});

        </script>
    <?php
    }
}

// Diplay a picture in pixlr
add_filter( 'wp_nav_menu_items', '_custom_menu_item', 10, 2 );
function _custom_menu_item ( $items, $args ) {
    if ( is_user_logged_in() ) {
        if ( $args->theme_location == 'primary') {
            $items .= '<li>&nbsp;&nbsp;&nbsp;&nbsp;<button class="pixlrspecial"  ONCLICK="javascript:pixlr.overlay.show({image:\'\', title:\'\', service:\'editor\'});">dude your image</button>

</li>';
        }
    }
    return $items;
}
?>