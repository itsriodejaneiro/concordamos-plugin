<?php

namespace Concordamos;

function register_login_block() {
	wp_register_style( 'concordamos-login-block-style', CONCORDAMOS_PLUGIN_URL . 'build/css/login-block.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );
	wp_register_script( 'concordamos-login-block-admin', CONCORDAMOS_PLUGIN_URL . 'build/js/login-block/editor.js', array( 'wp-editor', 'wp-element', 'wp-i18n' ), CONCORDAMOS_PLUGIN_VERSION, true );

	register_block_type(
		'concordamos/login',
		array(
			'api_version'     => 2,
			'editor_script'   => 'concordamos-login-block-admin',
			'style_handles'   => array( 'concordamos-login-block-style' ),
			'render_callback' => 'Concordamos\render_login_block',
		)
	);

	$redirect_to = filter_input( INPUT_GET, 'redirect_to', FILTER_VALIDATE_URL );

	wp_enqueue_script( 'concordamos-login', CONCORDAMOS_PLUGIN_URL . 'build/js/user/login.js', array( 'wp-element' ), CONCORDAMOS_PLUGIN_VERSION, true );

	wp_set_script_translations( 'concordamos-login', 'concordamos', CONCORDAMOS_PLUGIN_PATH . 'languages/' );
	wp_localize_script(
		'concordamos-login',
		'concordamos_login',
		array(
			'nonce'       => wp_create_nonce( 'wp_rest' ),
			'redirect_to' => empty( $redirect_to ) ? get_permalink( get_page_by_template( 'concordamos/template-my-account.php' ) ) : esc_url( get_home_url( null, $redirect_to ) ),
		)
	);
}

function register_votings_block() {
	wp_register_style( 'concordamos-votings-block-style', CONCORDAMOS_PLUGIN_URL . 'build/css/votings-block.css', array( 'concordamos-style' ), CONCORDAMOS_PLUGIN_VERSION );
	wp_register_script( 'concordamos-votings-block', CONCORDAMOS_PLUGIN_URL . 'build/js/votings-block/index.js', array( 'wp-element', 'wp-i18n' ), CONCORDAMOS_PLUGIN_VERSION, true );

	wp_register_script( 'concordamos-votings-block-admin', CONCORDAMOS_PLUGIN_URL . 'build/js/votings-block/editor.js', array( 'wp-editor', 'wp-element', 'wp-i18n' ), CONCORDAMOS_PLUGIN_VERSION, true );

	wp_set_script_translations( 'concordamos-votings-block', 'concordamos', CONCORDAMOS_PLUGIN_PATH . 'languages/' );
	wp_localize_script(
		'concordamos-votings-block',
		'concordamos',
		array(
			'locale'     => get_js_locale(),
			'nonce'      => wp_create_nonce( 'wp_rest' ),
			'plugin_url' => CONCORDAMOS_PLUGIN_URL,
			'rest_url'   => rest_url( 'concordamos/v1/' ),
		)
	);

	register_block_type(
		'concordamos/votings',
		array(
			'api_version'   => 2,
			'script'        => 'concordamos-votings-block',
			'editor_script' => 'concordamos-votings-block-admin',
			'style_handles' => array( 'concordamos-votings-block-style' ),
		)
	);
}

function render_login_block( $attributes ) {
	ob_start();
	include __DIR__ . '/blocks/login.php';
	$template = ob_get_contents();
	ob_end_clean();
	return $template;
}

add_action( 'init', 'Concordamos\register_login_block' );
add_action( 'init', 'Concordamos\register_votings_block' );
