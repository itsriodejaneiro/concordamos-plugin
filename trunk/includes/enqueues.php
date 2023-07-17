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
			wp_localize_script(
				'concordamos-voting-form',
				'concordamos',
				[
					'nonce'   => wp_create_nonce( 'wp_rest' ),
					'user_id' => get_current_user_id()
				]
			);
		}
	}
}

add_action( 'wp_enqueue_scripts', 'Concordamos\enqueue_scripts_frontend' );
