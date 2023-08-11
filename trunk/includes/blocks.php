<?php

namespace Concordamos;

function register_login_block () {
	wp_register_style( 'concordamos-login-block-style', CONCORDAMOS_PLUGIN_URL . 'build/css/login-block.css', [ 'concordamos-style' ] , CONCORDAMOS_PLUGIN_VERSION );
	wp_register_script( 'concordamos-login-block-admin', CONCORDAMOS_PLUGIN_URL . 'build/js/login-block/editor.js', [ 'wp-editor', 'wp-element', 'wp-i18n' ] , CONCORDAMOS_PLUGIN_VERSION );

	register_block_type( 'concordamos/login', [
		'api_version' => 2,
		'editor_script' => 'concordamos-login-block-admin',
		'styles' => [ 'concordamos-login-block-style' ],
		'render_callback' => 'Concordamos\render_login_block',
	] );
}

function register_votings_block () {
	wp_register_style( 'concordamos-votings-block-style', CONCORDAMOS_PLUGIN_URL . 'build/css/votings-block.css', [ 'concordamos-style' ] , CONCORDAMOS_PLUGIN_VERSION );
	wp_register_script( 'concordamos-votings-block', CONCORDAMOS_PLUGIN_URL . 'build/js/votings-block/index.js', [ 'wp-element', 'wp-i18n' ] , CONCORDAMOS_PLUGIN_VERSION );

	wp_register_script( 'concordamos-votings-block-admin', CONCORDAMOS_PLUGIN_URL . 'build/js/votings-block/editor.js', [ 'wp-editor', 'wp-element', 'wp-i18n' ] , CONCORDAMOS_PLUGIN_VERSION );

	wp_set_script_translations( 'concordamos-votings-block', 'concordamos' );
	wp_localize_script(
		'concordamos-votings-block',
		'concordamos',
		[
			'nonce'   => wp_create_nonce( 'wp_rest' ),
			'rest_url' => rest_url( 'concordamos/v1/' ),
		]
	);

	register_block_type( 'concordamos/votings', [
		'api_version' => 2,
		'script' => 'concordamos-votings-block',
		'editor_script' => 'concordamos-votings-block-admin',
	] );
}

function render_login_block ( $attributes ) {
	ob_start();
	include __DIR__ . '/blocks/login.php';
	$template = ob_get_contents();
	ob_end_clean();
	return $template;
}

add_action( 'init', 'Concordamos\register_login_block' );
add_action( 'init', 'Concordamos\register_votings_block' );