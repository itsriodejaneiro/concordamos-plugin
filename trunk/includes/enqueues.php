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

			wp_enqueue_style( 'concordamos-voting-form-style', CONCORDAMOS_PLUGIN_URL . 'build/css/voting-page.css', [], CONCORDAMOS_PLUGIN_VERSION );
		}
	}

	if ( is_singular( 'voting' ) ) {
		wp_enqueue_script( 'concordamos-voting-single', CONCORDAMOS_PLUGIN_URL . 'build/js/voting-single/index.js', ['wp-element'], CONCORDAMOS_PLUGIN_VERSION, true );
		wp_enqueue_style( 'concordamos-single-voting-style', CONCORDAMOS_PLUGIN_URL . 'build/css/single-voting.css', [], CONCORDAMOS_PLUGIN_VERSION );

		$unique_id = sanitize_title( get_query_var( 'unique_id' ) );

		wp_localize_script(
			'concordamos-voting-single',
			'concordamos',
			[
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'u_id'  => $unique_id,
				'v_id'  => get_the_ID()
			]
		);
	}

	if ( is_post_type_archive( 'voting' ) ) {
		wp_enqueue_script( 'concordamos-voting-archive', CONCORDAMOS_PLUGIN_URL . 'build/js/voting-archive/index.js', ['wp-element'], CONCORDAMOS_PLUGIN_VERSION, true );
		wp_enqueue_style( 'concordamos-voting-archive-style', CONCORDAMOS_PLUGIN_URL . 'build/css/archive-voting.css', [], CONCORDAMOS_PLUGIN_VERSION );

		wp_localize_script(
			'concordamos-voting-archive',
			'concordamos',
			[
				'is_admin' => current_user_can( 'manage_options' ),
				'nonce' => wp_create_nonce( 'wp-rest' ),
			]
		);
	}
}

add_action( 'wp_enqueue_scripts', 'Concordamos\enqueue_scripts_frontend' );
