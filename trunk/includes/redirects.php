<?php

namespace Concordamos;

function restrict_voting_single_access() {
	if ( ! is_singular( 'voting' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$raw_post_meta = get_post_meta( $post_id );

	// Check if is private voting
	if ( $raw_post_meta['voting_type'][0] === 'private' ) {
		$unique_id = sanitize_title( get_query_var( 'unique_id' ) );
		$expired_unique_ids = array_filter( explode( ',', get_post_meta( $post_id, 'expired_unique_ids', true ) ) );

		if ( ! in_array( $unique_id, $expired_unique_ids ) ) {
			return;
		}

		wp_redirect( home_url( '/voting' ) );
		exit;
	}

	return;
}

add_action( 'template_redirect', 'Concordamos\restrict_voting_single_access' );
