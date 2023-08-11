<?php

namespace Concordamos;

function register_blocks () {
	wp_register_script( 'concordamos-votings-block', CONCORDAMOS_PLUGIN_URL . 'builds/js/votings-block/index.js', [ 'wp-element', 'wp-i18n' ] , CONCORDAMOS_PLUGIN_VERSION );

	wp_register_script( 'concordamos-votings-block-admin', CONCORDAMOS_PLUGIN_URL . 'builds/js/votings-block/editor.js', [ 'wp-element', 'wp-i18n' ] , CONCORDAMOS_PLUGIN_VERSION );

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

add_action( 'init', 'Concordamos\register_blocks' );
