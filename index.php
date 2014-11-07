<?php
/*
Plugin Name: WP Catalogue
Plugin URI: http://www.wordpress.org/extend/plugins/wp-catalogue/
Description: Display your products in an attractive and professional catalogue. It's easy to use, easy to customise, and lets you show off your products in style.
Author: Maeve Lander
Version: 1.7.2
Author URI: http://www.enigmaplugins.com
*/
//creating db tables

function customtaxorder_init() {
	global $wpdb;
	$init_query = $wpdb->query("SHOW COLUMNS FROM $wpdb->terms LIKE 'term_order'");
	if ($init_query == 0) {	$wpdb->query("ALTER TABLE $wpdb->terms ADD `term_order` INT( 4 ) NULL DEFAULT '0'"); }
}
register_activation_hook(__FILE__, 'customtaxorder_init');
register_uninstall_hook('uninstall.php', $callback);

require 'wpc-catalogue.php';
require 'products/wpc-product.php';

define( 'WP_CATALOGUE', plugin_dir_url( __FILE__ ) );
define( 'WP_CATALOGUE_PRODUCTS', WP_CATALOGUE.'products'  );
define( 'WP_CATALOGUE_INCLUDES', WP_CATALOGUE.'includes'  );
define( 'WP_CATALOGUE_CSS', WP_CATALOGUE_INCLUDES.'/css'  );
define( 'WP_CATALOGUE_JS', WP_CATALOGUE_INCLUDES.'/js'  );

// adding scripts and styles to amdin
add_action('admin_enqueue_scripts', 'wp_catalogue_scripts_method');
function wp_catalogue_scripts_method(){
	global $current_screen;
	wp_deregister_script('wpc-js');
	wp_register_script('wpc-js',WP_CATALOGUE_JS.'/wpc.js');
	if($current_screen->post_type=='wpcproduct'){
		wp_enqueue_script('wpc-js');
	}
	wp_register_style('admin-css', WP_CATALOGUE_CSS.'/admin-styles.css' );
	wp_enqueue_style( 'admin-css' );
}
function wpc_admin_init(){
    $style_url = WP_CATALOGUE_CSS.'/sorting.css';
    wp_register_style(WPC_STYLE, $style_url);
    $script_url = WP_CATALOGUE_JS.'/sorting.js';
    wp_register_script(WPC_SCRIPT, $script_url, array('jquery', 'jquery-ui-sortable'));	
}

add_action('admin_init', 'wpc_admin_init');
add_action('wp_enqueue_scripts', 'front_scripts');

function front_scripts(){
	 global $bg_color;
	 $bg_color = get_option('templateColorforProducts');
	wp_enqueue_script('jquery');
	wp_deregister_script('wpcf-js');
	wp_register_script('wpcf-js',WP_CATALOGUE_JS.'/wpc-front.js');
	wp_enqueue_script('wpcf-js');
	wp_register_style('catalogue-css', WP_CATALOGUE_CSS.'/catalogue-styles.css' );
	wp_enqueue_style( 'catalogue-css' );
	
	/* ========================  Take User Defined color =========================== 
	wp_register_style('dynamic-css', WP_CATALOGUE_CSS.'/dynamic-styles.css.php' );
	wp_enqueue_style( 'dynamic-css' );	*/	
}

// creating wp catalogue menus
//add_action( 'admin_menu', 'wpc_plugin_menu' );
function wpc_plugin_menu() {
	add_submenu_page( 'edit.php?post_type=wpcproduct', 'Order', 'Order', 'manage_options', 'customtaxorder', 'customtaxorder', 2 ); 
	add_submenu_page('edit.php?post_type=wpcproduct','Settings','Settings', 'manage_options','catalogue_settings', 'wp_catalogue_settings');
}
add_action('admin_print_styles', 'wpc_admin_styles');
add_action('admin_print_scripts', 'wpc_admin_scripts');
// add required styles
function wpc_admin_styles(){
    wp_enqueue_style(WPC_STYLE);
}
// add required scripts
function wpc_admin_scripts(){
    wp_enqueue_script(WPC_SCRIPT);
}

add_action( 'admin_init', 'register_catalogue_settings' );
$plugin_dir_path = dirname(__FILE__);

function register_catalogue_settings() {
	update_option('posts_per_page',get_option('pagination'));
	register_setting( 'baw-settings-group', 'grid_rows' );
	register_setting( 'baw-settings-group', 'templateColorforProducts' );  // new added color picker
	register_setting( 'baw-settings-group', 'pagination' );
	register_setting( 'baw-settings-group', 'image_height' );
	register_setting( 'baw-settings-group', 'image_width' );
	register_setting( 'baw-settings-group', 'thumb_height' );
	register_setting( 'baw-settings-group', 'thumb_width' );
	register_setting( 'baw-settings-group', 'image_scale_crop' );
	register_setting( 'baw-settings-group', 'thumb_scale_crop' );
	register_setting( 'baw-settings-group', 'croping' );
	register_setting( 'baw-settings-group', 'tcroping' );
	register_setting( 'baw-settings-group', 'next_prev' );
	register_setting( 'baw-settings-group', 'inn_temp_head' );
	register_setting( 'baw-settings-group', 'inn_temp_foot' );
}

function wp_catalogue_settings(){
	require 'settings.php';
}
require 'products/order.php';
add_action("template_redirect", 'my_theme_redirect');

function my_theme_redirect() {
   	
    global $wp;
   $plugindir = dirname( __FILE__ );	
    //A Specific Custom Post Type
    if ($wp->query_vars["post_type"] == 'wpcproduct') {
        $templatefilename = 'single-wpcproduct.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        do_theme_redirect($return_template);
    }
	if (is_tax()) {
        $templatefilename = 'taxonomy-wpccategories.php';
        if (file_exists(TEMPLATEPATH . '/' . $templatefilename)) {
            $return_template = TEMPLATEPATH . '/' . $templatefilename;
        } else {
            $return_template = $plugindir . '/themefiles/' . $templatefilename;
        }
        do_theme_redirect($return_template);
    }
}

function do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}
add_action( 'admin_notices', 'dev_check_current_screen' );

/* ========================  pick color through Iris =========================== */

add_action( 'admin_enqueue_scripts', 'mw_enqueue_color_picker' );
function mw_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('my-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
/* ========================  Multicolor =========================== */

load_plugin_textdomain( 'wpc', WPCACHEHOME . 'languages', basename( dirname( __FILE__ ) ) . '/languages' );

/* ========================  Take User Defined color =========================== */

add_action('wp_head', 'colorPalette');
function colorPalette() { ?>

<style type="text/css">
.wpc-img:hover {
 border: 5px solid <?php echo get_option('templateColorforProducts');
?> !important;
}
.wpc-title {
 color: <?php echo get_option('templateColorforProducts');
?> !important;
}
.wpc-title a:hover {
 color: <?php echo get_option('templateColorforProducts');
?> !important;
}
#wpc-col-1 ul li a:hover, #wpc-col-1 ul li.active-wpc-cat a {
	border-right: none;
 background:<?php echo get_option('templateColorforProducts');
?> no-repeat left top !important;
}
.wpc-paginations a:hover, .wpc-paginations .active-wpc-page {
	background: <?php echo get_option('templateColorforProducts');
?> !important;
}
</style>
<?php }
