<?php
/*
Plugin Name: Ninja Forms - Payeezy
Plugin URI: http://trinixcreative.com
Description: Payeezy add-on for Ninja Forms.
Version: 1.5
Author: TrinixCreative
Author URI: http://trinixcreative.com/
*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

global $wpdb;

define("NINJA_FORMS_UPLOADS_DIR", WP_PLUGIN_DIR."/".basename( dirname( __FILE__ ) ) );
define("NINJA_FORMS_UPLOADS_URL", plugins_url()."/".basename( dirname( __FILE__ ) ) );
define("NINJA_FORMS_UPLOADS_TABLE_NAME", $wpdb->prefix . "ninja_forms_payeezy");
define("NINJA_FORMS_UPLOADS_VERSION", ".1");


require_once(NINJA_FORMS_UPLOADS_DIR."/includes/admin/pages/settings.php");

require_once(NINJA_FORMS_UPLOADS_DIR."/includes/display/processing/process.php");

require_once(NINJA_FORMS_UPLOADS_DIR."/includes/fields/payment.php");

require_once(NINJA_FORMS_UPLOADS_DIR."/includes/activation.php");


//Add File Uploads to the admin menu
add_action('admin_menu', 'ninja_forms_add_payment_menu', 99);
function ninja_forms_add_payment_menu(){
	$capabilities = 'administrator';

	$uploads = add_submenu_page("ninja-forms", "Payeezy Settings", "Payeezy Settings", $capabilities, "ninja-forms-payments", "ninja_forms_admin");
}

register_activation_hook( __FILE__, 'ninja_forms_uploads_activation' );

$plugin_settings = get_option( 'ninja_forms_settings' );

if( isset( $plugin_settings['payeezy_version'] ) ){
	$current_version = $plugin_settings['payeezy_version'];
}else{
	$current_version = 0.4;
}

if( version_compare( $current_version, '0.5', '<' ) ){
	ninja_forms_payeezy_activation();
}