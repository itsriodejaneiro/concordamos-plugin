<?php

namespace Concordamos;

function enqueue_scripts_admin() {
	wp_enqueue_style( 'concordamos-admin', CONCORDAMOS_PLUGIN_URL . 'build/css/admin.css', [], CONCORDAMOS_PLUGIN_VERSION );
}

add_action( 'admin_enqueue_scripts', 'Concordamos\enqueue_scripts_admin' );

function enqueue_scripts_frontend() {
	wp_register_style('concordamos-style', CONCORDAMOS_PLUGIN_URL . 'build/css/plugin.css', [], CONCORDAMOS_PLUGIN_VERSION);

	if ( is_page() ) {
		$voting_page = get_voting_page();

		if ( get_the_ID() == $voting_page ) {
			wp_enqueue_style( 'concordamos-voting-form-style', CONCORDAMOS_PLUGIN_URL . 'build/css/voting-page.css', ['concordamos-style'], CONCORDAMOS_PLUGIN_VERSION );

			enqueue_localized_script( 'concordamos-voting-form', CONCORDAMOS_PLUGIN_URL . 'build/js/voting-page/index.js', ['wp-element', 'wp-i18n'], 'concordamos', [
				'nonce'   => wp_create_nonce( 'wp_rest' ),
				'plugin_url' => CONCORDAMOS_PLUGIN_URL,
				'rest_url' => rest_url( 'concordamos/v1/' ),
				'user_id' => get_current_user_id()
			] );
		}

		$template_slug = get_page_template_slug();

		if ( $template_slug === 'concordamos/template-login.php' ) {
			wp_enqueue_style( 'concordamos-template-login-style', CONCORDAMOS_PLUGIN_URL . 'build/css/template-login.css', ['concordamos-style'], CONCORDAMOS_PLUGIN_VERSION );

			enqueue_localized_script( 'concordamos-login', CONCORDAMOS_PLUGIN_URL . 'build/js/user/login.js', ['wp-element'], 'concordamos_login', [
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'my_account_url' => get_permalink( get_page_by_template('concordamos/template-my-account.php') )
			] );
		}

		if ( $template_slug === 'concordamos/template-create-user.php' ) {
			wp_enqueue_style( 'concordamos-template-login-style', CONCORDAMOS_PLUGIN_URL . 'build/css/template-login.css', ['concordamos-style'], CONCORDAMOS_PLUGIN_VERSION );

			enqueue_localized_script( 'concordamos-create-user', CONCORDAMOS_PLUGIN_URL . 'build/js/user/register.js', [], 'concordamos', [
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'plugin_url' => CONCORDAMOS_PLUGIN_URL,
				'rest_url' => rest_url( 'concordamos/user/v1' ),
				'my_account_url' => get_permalink( get_page_by_template('concordamos/template-my-account.php') ),
			] );
		}

		if ( $template_slug === 'concordamos/template-my-account.php' ) {
			wp_enqueue_style( 'concordamos-my-account-style', CONCORDAMOS_PLUGIN_URL . 'build/css/my-account.css', [ 'concordamos-style' ], CONCORDAMOS_PLUGIN_VERSION );

			enqueue_localized_script( 'concordamos-my-account', CONCORDAMOS_PLUGIN_URL . 'build/js/my-account/index.js', [ 'wp-element', 'wp-i18n' ], 'concordamos', [
				'nonce' => wp_create_nonce( 'wp_rest' ),
				'plugin_url' => CONCORDAMOS_PLUGIN_URL,
				'rest_url' => rest_url( 'concordamos/v1/' ),
				'user_id' => get_current_user_id(),
			] );
		}
	}

	if ( is_singular( 'voting' ) ) {
		wp_enqueue_style( 'concordamos-single-voting-style', CONCORDAMOS_PLUGIN_URL . 'build/css/single-voting.css', ['concordamos-style'], CONCORDAMOS_PLUGIN_VERSION );

		$unique_id = sanitize_title( get_query_var( 'unique_id' ) );

		enqueue_localized_script( 'concordamos-voting-single', CONCORDAMOS_PLUGIN_URL . 'build/js/voting-single/index.js', ['wp-element', 'wp-i18n'], 'concordamos', [
			'is_author' => get_post_field( 'post_author', get_the_ID() ) == get_current_user_id() ? true : false,
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'plugin_url' => CONCORDAMOS_PLUGIN_URL,
			'rest_url' => rest_url( 'concordamos/v1/' ),
			'u_id'  => $unique_id,
			'user_id' => get_current_user_id(),
			'v_id'  => get_the_ID(),
		] );
	}

	if ( is_post_type_archive( 'voting' ) ) {
		wp_enqueue_style( 'concordamos-voting-archive-style', CONCORDAMOS_PLUGIN_URL . 'build/css/archive-voting.css', ['concordamos-style'], CONCORDAMOS_PLUGIN_VERSION );

		enqueue_localized_script( 'concordamos-voting-archive', CONCORDAMOS_PLUGIN_URL . 'build/js/voting-archive/index.js', ['wp-element', 'wp-i18n'], 'concordamos', [
			'nonce' => wp_create_nonce( 'wp_rest' ),
			'plugin_url' => CONCORDAMOS_PLUGIN_URL,
			'rest_url' => rest_url( 'concordamos/v1/' ),
		] );
	}

	$id = get_the_ID();

	$block_list = [
	  'concordamos/login' => function() {
		  wp_enqueue_style(
			  'concordamos-login',
			  CONCORDAMOS_PLUGIN_URL . 'build/css/blocks/login.css',
			  [],
			  CONCORDAMOS_PLUGIN_VERSION,
			  'all'
		  );
	  },
	];

	// Enqueue only used blocks
	foreach( $block_list as $key => $block ) {
		if( has_block( $key, $id ) ){
			$block();
		}
	}

}

add_action( 'wp_enqueue_scripts', 'Concordamos\enqueue_scripts_frontend' );
