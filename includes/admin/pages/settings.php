<?php

add_action('admin_init', 'ninja_forms_register_tab_payment_settings');
function ninja_forms_register_tab_payment_settings(){
	$args = array(
		'name' => 'Settings',
		'page' => 'ninja-forms-payments',
		'display_function' => '',
		'save_function' => 'ninja_forms_save_payment_settings',
		'tab_reload' => true,
	);
	if( function_exists( 'ninja_forms_register_tab' ) ){
		ninja_forms_register_tab('payment_settings', $args);
	}
}

add_action( 'admin_init', 'ninja_forms_register_payment_settings_metabox');
function ninja_forms_register_payment_settings_metabox(){
	$args = array(
		'page' => 'ninja-forms-payments',
		'tab' => 'payment_settings',
		'slug' => 'payment_settings',
		'title' => __('Payment Settings', 'ninja-forms'),
		'settings' => array(
			array(
				'name' => 'API_key',
				'type' => 'text',
				'label' => __( 'API Key', 'ninja-forms' ),
				'desc' => '',
			),
			array(
				'name' => 'API_secret',
				'type' => 'text',
				'label' => __('API Secret Key', 'ninja-forms'),
				'desc' => '',
			),
			array(
				'name' => 'merchant_token',
				'type' => 'text',
				'label' => __('Merchant Token', 'ninja-forms'),
				'desc' => '',
			),
			// array(
			// 	'name' => 'merchant_ref',
			// 	'type' => 'text',
			// 	'label' => __( 'Merchant Reference', 'ninja-forms' ),
			// 	'desc' => 'ie: trinix-creative',
			// ),
		)
	);
	if( function_exists( 'ninja_forms_register_tab_metabox' ) ){
		ninja_forms_register_tab_metabox($args);
	}
}

function ninja_forms_save_payment_settings( $data ){
	$plugin_settings = get_option( 'ninja_forms_settings' );
	foreach( $data as $key => $val ){
		$plugin_settings[$key] = $val;
	}
	update_option( 'ninja_forms_settings', $plugin_settings );
	$update_msg = __( 'Settings Saved', 'ninja-forms' );
	return $update_msg;
}