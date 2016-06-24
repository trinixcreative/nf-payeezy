<?php

function ninja_forms_payeezy_activation(){
	global $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

	$sql = "CREATE TABLE IF NOT EXISTS ".NINJA_FORMS_UPLOADS_TABLE_NAME." (
	  `id` int(11) NOT NULL AUTO_INCREMENT,
	  `user_id` int(11) DEFAULT NULL,
	  `form_id` int(11) NOT NULL,
	  `field_id` int(11) NOT NULL,
	  `data` longtext CHARACTER SET utf8 NOT NULL,
	  `date_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	  PRIMARY KEY (`id`)
	) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ;";
	
	dbDelta($sql);

	$opt = get_option( 'ninja_forms_settings' );

	if( isset( $opt['version'] ) ){
		$current_version = $opt['version'];
	}else{
		$current_version = '';
	}

	$opt['payeezy_version'] = NINJA_FORMS_UPLOADS_VERSION;

	update_option( 'ninja_forms_settings', $opt );
}