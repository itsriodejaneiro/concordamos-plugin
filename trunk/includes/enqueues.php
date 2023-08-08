<?php

namespace Concordamos;

function enqueue_scripts_admin() {
	wp_enqueue_style( 'concordamos-admin', CONCORDAMOS_PLUGIN_URL . 'build/css/admin.css', [], CONCORDAMOS_PLUGIN_VERSION );
}

add_action( 'admin_enqueue_scripts', 'Concordamos\enqueue_scripts_admin' );

function enqueue_scripts_frontend() {
	wp_register_style('concordamos', CONCORDAMOS_PLUGIN_URL . 'build/css/plugin.css', [], CONCORDAMOS_PLUGIN_VERSION);

	if ( is_page() ) {
		$voting_page = get_voting_page();

		if ( get_the_ID() == $voting_page ) {
			wp_enqueue_script( 'concordamos-voting-form', CONCORDAMOS_PLUGIN_URL . 'build/js/voting-page/index.js', ['wp-element', 'wp-i18n'], CONCORDAMOS_PLUGIN_VERSION, true );
			wp_localize_script(
				'concordamos-voting-form',
				'concordamos',
				[
					'nonce'   => wp_create_nonce( 'wp_rest' ),
					'plugin_url' => CONCORDAMOS_PLUGIN_URL,
					'user_id' => get_current_user_id()
				]
			);

			wp_enqueue_style( 'concordamos-voting-form-style', CONCORDAMOS_PLUGIN_URL . 'build/css/voting-page.css', ['concordamos'], CONCORDAMOS_PLUGIN_VERSION );
		}
		$template_slug = get_page_template_slug();
		if ($template_slug === 'concordamos/template-login.php' || $template_slug = 'concordamos/template-create-user.php') {
			wp_enqueue_style( 'concordamos-template-login-style', CONCORDAMOS_PLUGIN_URL . 'build/css/template-login.css', ['concordamos'], CONCORDAMOS_PLUGIN_VERSION );
		}
	}

	if ( is_singular( 'voting' ) ) {
		wp_enqueue_script( 'concordamos-voting-single', CONCORDAMOS_PLUGIN_URL . 'build/js/voting-single/index.js', ['wp-element', 'wp-i18n'], CONCORDAMOS_PLUGIN_VERSION, true );
		wp_enqueue_style( 'concordamos-single-voting-style', CONCORDAMOS_PLUGIN_URL . 'build/css/single-voting.css', ['concordamos'], CONCORDAMOS_PLUGIN_VERSION );

		$unique_id = sanitize_title( get_query_var( 'unique_id' ) );

		wp_localize_script(
			'concordamos-voting-single',
			'concordamos',
			[
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'plugin_url' => CONCORDAMOS_PLUGIN_URL,
				'u_id'  => $unique_id,
				'v_id'  => get_the_ID(),
			]
		);
	}

	if ( is_post_type_archive( 'voting' ) ) {
		wp_enqueue_script( 'concordamos-voting-archive', CONCORDAMOS_PLUGIN_URL . 'build/js/voting-archive/index.js', ['wp-element', 'wp-i18n'], CONCORDAMOS_PLUGIN_VERSION, true );
		wp_enqueue_style( 'concordamos-voting-archive-style', CONCORDAMOS_PLUGIN_URL . 'build/css/archive-voting.css', ['concordamos'], CONCORDAMOS_PLUGIN_VERSION );

		wp_localize_script(
			'concordamos-voting-archive',
			'concordamos',
			[
				'is_admin' => current_user_can( 'manage_options' ),
				'nonce' => wp_create_nonce( 'wp-rest' ),
				'plugin_url' => CONCORDAMOS_PLUGIN_URL,
			]
		);
	}

	global $author;
	if ( is_author() && is_concordamos_user( $author ) ) {
		wp_enqueue_script( 'concordamos-my-account', CONCORDAMOS_PLUGIN_URL . 'build/js/my-account/index.js', [ 'wp-element', 'wp-i18n' ], CONCORDAMOS_PLUGIN_VERSION, true );
		wp_enqueue_style( 'concordamos-my-account-style', CONCORDAMOS_PLUGIN_URL . 'build/css/my-acount.css', [ 'concordamos' ], CONCORDAMOS_PLUGIN_VERSION );

		wp_localize_script( 'corcordamos-my-account', 'concordamos', [
			'nonce' => wp_create_nonce( 'wp-rest' ),
			'plugin_url' => CONCORDAMOS_PLUGIN_URL,
			'user_id' => $author,
		] );
	}
}


add_action( 'wp_enqueue_scripts', 'Concordamos\enqueue_scripts_frontend' );
