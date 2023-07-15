<?php

namespace Concordamos;

function enqueue_scripts_admin() {
	wp_enqueue_style( 'concordamos-admin', CONCORDAMOS_PLUGIN_URL . 'build/css/admin.css', [], CONCORDAMOS_PLUGIN_VERSION );
}

add_action( 'admin_enqueue_scripts', 'Concordamos\enqueue_scripts_admin' );

function enqueue_scripts_frontend() {
	if ( is_page() ) {
		$voting_page = get_voting_page();

		if ( get_the_ID() == $voting_page ) {
			wp_enqueue_script( 'concordamos-voting-form', CONCORDAMOS_PLUGIN_URL . 'build/js/voting-page/index.js', ['wp-element'], CONCORDAMOS_PLUGIN_VERSION, true );
		}
	}
}

add_action( 'wp_enqueue_scripts', 'Concordamos\enqueue_scripts_frontend' );
