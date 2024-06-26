<?php

namespace Concordamos;

function restrict_voting_single_access() {
	if ( ! is_singular( 'voting' ) ) {
		return;
	}

	$post_id       = get_the_ID();
	$source_id     = get_source_post_id( 'voting', $post_id );
	$raw_post_meta = get_post_meta( $source_id );

	// Check if is private voting
	if ( $raw_post_meta['voting_type'][0] === 'private' && use_unique_links() ) {
		$unique_id          = sanitize_title( get_query_var( 'unique_id' ) );
		$expired_unique_ids = array_filter( explode( ',', get_post_meta( $source_id, 'expired_unique_ids', true ) ) );

		// If the provided `unique_id` has already been used, redirect to the voting archive.
		if ( in_array( $unique_id, $expired_unique_ids, true ) ) {
			if ( is_user_logged_in() ) {
				nocache_headers();
				wp_safe_redirect( get_panel_url( get_permalink( $post_id ) ) );
				exit;
			} else {
				nocache_headers();
				wp_safe_redirect( get_post_type_archive_link( 'voting' ) );
				exit;
			}
		}

		// Check if the logged-in user has already voted.
		if ( is_user_logged_in() && get_vote_by_user( $post_id ) && get_query_var( 'panel' ) !== '1' ) {
			nocache_headers();
			wp_safe_redirect( get_panel_url( get_permalink( $post_id ) ) );
			exit;
		}
	}
}

add_action( 'template_redirect', 'Concordamos\restrict_voting_single_access' );


/**
 * @return void
 */
function require_login_to_create_or_translate_voting() {
	$create_voting_page    = get_page_by_template( 'concordamos/template-create-voting.php' );
	$translate_voting_page = get_page_by_template( 'concordamos/template-translate-voting.php' );

	$is_voting_creation    = ! empty( $create_voting_page ) && $create_voting_page->ID === get_the_ID();
	$is_voting_translation = ! empty( $translate_voting_page ) && $translate_voting_page->ID === get_the_ID();

	if ( ! $is_voting_creation && ! $is_voting_translation ) {
		return;
	}
	if ( is_user_logged_in() ) {
		return;
	}

	nocache_headers();
	wp_safe_redirect( get_permalink( get_page_by_template( 'concordamos/template-login.php' ) ) );
	exit;
}

add_action( 'template_redirect', 'Concordamos\require_login_to_create_or_translate_voting' );

/**
 * @return void
 */
function authenticate_to_translate_voting() {
	$translate_voting_page = get_page_by_template( 'concordamos/template-translate-voting.php' );

	if ( empty( $translate_voting_page ) || $translate_voting_page->ID !== get_the_ID() ) {
		return;
	}

	$voting_slug = sanitize_key( filter_input( INPUT_GET, 'v_id' ) );

	if ( ! empty( $voting_slug ) ) {
		$voting = get_post_by_slug( 'voting', $voting_slug );

		if ( ! empty( $voting ) && $voting->post_author == get_current_user_id() ) {
			return;
		}
	}

	nocache_headers();
	wp_safe_redirect( get_permalink( get_page_by_template( 'concordamos/template-create-voting.php' ) ) );
	exit;
}

add_action( 'template_redirect', 'Concordamos\authenticate_to_translate_voting' );

/**
 * @return void
 */
function required_login_to_my_account() {
	if ( ! strstr( get_page_template(), 'template-my-account.php' ) ) {
		return;
	}
	if ( is_user_logged_in() ) {
		return;
	}

	nocache_headers();
	wp_safe_redirect( get_permalink( get_page_by_template( 'concordamos/template-login.php' ) ) );
	exit;
}

add_action( 'template_redirect', 'Concordamos\required_login_to_my_account' );

/**
 * @return void
 */
function required_login_to_voting_panel() {
	if ( ! is_singular( 'voting' ) || is_user_logged_in() ) {
		return;
	}

	if ( get_query_var( 'panel' ) !== '1' || is_voting_closed( get_the_ID() ) ) {
		return;
	}

	$login_url   = get_permalink( get_page_by_template( 'concordamos/template-login.php' ) );
	$redirect_to = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_VALIDATE_URL );

	if ( ! empty( $redirect_to ) ) {
		nocache_headers();
		wp_safe_redirect( esc_url( $login_url . '?redirect_to=' . $redirect_to ) );
		exit;
	}

	nocache_headers();
	wp_safe_redirect( $login_url );
	exit;
}

add_action( 'template_redirect', 'Concordamos\required_login_to_voting_panel' );

function require_login_for_private_voting() {
	if ( is_singular( 'voting' ) && ! is_user_logged_in() ) {
		$voting_access = get_post_meta( get_the_ID(), 'voting_access', true );

		if ( ! empty( $voting_access ) && $voting_access === 'yes' ) {
			$login_url   = get_permalink( get_page_by_template( 'concordamos/template-login.php' ) );
			$redirect_to = filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_VALIDATE_URL );

			if ( ! empty( $redirect_to ) ) {
				nocache_headers();
				wp_safe_redirect( esc_url( $login_url . '?redirect_to=' . $redirect_to ) );
				exit;
			}

			nocache_headers();
			wp_safe_redirect( $login_url );
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

		if ( in_array( 'concordamos_network', (array) $user->roles, true ) ) {
			nocache_headers();
			wp_safe_redirect( home_url() );
			exit;
		}
	}
}
add_action( 'admin_init', 'Concordamos\restrict_admin_access' );
