<?php

namespace Concordamos;

add_filter( 'the_content', 'Concordamos\add_create_voting_form' );

function add_create_voting_form( $content ) {
	if ( is_page() && is_main_query() ) {
		$voting_page = get_voting_page();

		if ( get_the_ID() == $voting_page ) {
			$content .= '<div id="concordamos-voting-form"></div>';
		}
	}

	return $content;
}

function load_single_voting_template( $template ) {
	if ( is_singular( 'voting' ) && $template !== locate_template( ['single-voting.php'] ) ) {
		return CONCORDAMOS_PLUGIN_PATH . 'templates/single-voting.php';
	}

	return $template;
}

add_filter( 'single_template', 'Concordamos\load_single_voting_template' );

function restrict_voting_single_access() {
	if ( ! is_singular( 'voting' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$raw_post_meta = get_post_meta( $post_id );

	// Check if is private voting
	if ( $raw_post_meta['voting_type'][0] === 'private' ) {
		$unique_id = sanitize_title( get_query_var( 'unique_id' ) );
		$unique_ids = array_filter( explode( ',', $raw_post_meta['unique_ids'][0] ) );

		if ( $unique_id && in_array( $unique_id, $unique_ids ) ) {
			return;
		}

		wp_redirect( home_url( '/voting' ) );
		exit;
	}

	return;
}

add_action( 'template_redirect', 'Concordamos\restrict_voting_single_access' );
