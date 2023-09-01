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

		// If the provided `unique_id` has already been used, redirect to the voting archive.
		if ( in_array( $unique_id, $expired_unique_ids ) ) {
			if ( is_user_logged_in() ) {
				wp_redirect( get_panel_url( get_permalink( $post_id ) ) );
				exit;
			} else {
				wp_redirect( get_post_type_archive_link( 'voting' ) );
				exit;
			}
		}

		// Check if the logged-in user has already voted.
		if ( is_user_logged_in() && get_vote_by_user( $post_id ) && get_query_var( 'panel' ) !== '1' ) {
			wp_redirect( get_panel_url( get_permalink( $post_id ) ) );
			exit;
		}
	}

	return;
}

add_action( 'template_redirect', 'Concordamos\restrict_voting_single_access' );


/**
 * @return void
 */
function required_login_to_create_voting() {
	if ( get_voting_page() != get_the_ID() ) {
		return;
	}
	if( is_user_logged_in() ) {
		return;
	}

	wp_redirect( get_permalink( get_page_by_template( 'concordamos/template-login.php' ) ) );
	exit;

}

add_action( 'template_redirect', 'Concordamos\required_login_to_create_voting' );

/**
 * @return void
 */
function required_login_to_my_account() {
	if ( ! strstr(get_page_template(), 'template-my-account.php')) {
		return;
	}
	if( is_user_logged_in() ) {
		return;
	}

	wp_redirect( get_permalink( get_page_by_template( 'concordamos/template-login.php' ) ) );
	exit;

}

add_action( 'template_redirect', 'Concordamos\required_login_to_my_account' );

/**
 * @return void
 */
function required_login_to_voting_panel() {
	if ( ! is_singular('voting') || get_query_var('panel') !== '1' ) {
		return;
	}
	if( is_user_logged_in() ) {
		return;
	}

	wp_redirect( get_permalink( get_page_by_template( 'concordamos/template-login.php' ) ) );
	exit;

}

add_action( 'template_redirect', 'Concordamos\required_login_to_voting_panel' );

function require_login_for_private_voting () {
	if ( is_singular( 'voting' ) && !is_user_logged_in() ) {
		$voting_access = get_post_meta( get_the_ID(), 'voting_access', true );

		if ( !empty( $voting_access ) && $voting_access === 'yes' ) {
			if ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) {
				wp_redirect( esc_url( get_permalink( get_page_by_template( 'concordamos/template-login.php' ) ) . '?redirect_to=' . $_SERVER["REQUEST_URI"] ) );
				exit;
			}

			wp_redirect( get_permalink( get_page_by_template( 'concordamos/template-login.php' ) ) );
			exit;
		}
	}
}

add_action( 'template_redirect', 'Concordamos\require_login_for_private_voting' );

/**
 * Prevent of the user with role `concordamos_network` access the WP admin panel
 */
function restrict_admin_access() {
	if ( is_admin() ) {
		$user = wp_get_current_user();

		if ( in_array( 'concordamos_network', (array) $user->roles ) ) {
			wp_redirect( home_url() );
			exit;
		}
	}
}
add_action( 'admin_init', 'Concordamos\restrict_admin_access' );
