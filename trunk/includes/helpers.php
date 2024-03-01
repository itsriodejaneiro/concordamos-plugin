<?php

namespace Concordamos;

function get_faq_page() {
	return get_option( 'concordamos_faq_page', false );
}

function use_unique_links() {
	$option = get_option( 'concordamos_use_unique_links', '' );

	if ( ! empty( $option ) && 'yes' === $option ) {
		return true;
	}

	return false;
}

function get_options_by_voting( $voting_id ) {
	$args = array(
		'post_type'      => 'option',
		'posts_per_page' => -1,
		'meta_query'     => array(
			array(
				'key'     => 'voting_id',
				'value'   => $voting_id,
				'compare' => '=',
			),
		),
	);

	$options = \get_posts( $args );

	$return = array();

	if ( $options ) {
		foreach ( $options as $option ) {
			$raw_post_meta = \get_post_meta( $option->ID );

			$option_id = empty( $raw_post_meta['original_id'] ) ? $option->ID : $raw_post_meta['original_id'][0];

			$return[ $option_id ] = array(
				'option_name'        => $raw_post_meta['option_name'][0],
				'option_description' => $raw_post_meta['option_description'][0],
				'option_link'        => $raw_post_meta['option_link'][0],
			);
		}
	}

	return $return;
}

/**
 *
 * Create list of the terms by taxonomy
 *
 * @param int    $post_id Post ID
 * @param string $tax Slug tax to get terms
 * @param bool   $use_link Define if is use link to the terms
 *
 * @link https://developer.wordpress.org/reference/functions/get_the_terms/
 * @link https://developer.wordpress.org/reference/functions/sanitize_title/
 * @link https://developer.wordpress.org/reference/functions/esc_url/
 * @link https://developer.wordpress.org/reference/functions/get_term_link/
 *
 * @return string $html
 */
function get_html_terms( int $post_id, string $tax, bool $use_link = false ) {

	$terms = get_the_terms( $post_id, sanitize_title( $tax ) );

	if ( ! $terms || is_wp_error( $terms ) ) {
		return false;
	}

	$html = '<ul class="list-terms tax-' . sanitize_title( $tax ) . '">';

	foreach ( $terms as $term ) {

		$html .= '<li class="term-' . sanitize_title( $term->slug ) . '">';

		if ( $use_link ) {
			$html .= '<a href="' . esc_url( get_term_link( $term->term_id, $tax ) ) . '">';
		}

		$html .= '#';
		$html .= esc_attr( $term->name );

		if ( $use_link ) {
			$html .= '</a>';
		}

		$html .= '</li>';

	}

	$html .= '</ul>';

	return $html;
}

/**
 *
 * Formats a given timestamp into a specified date format.
 *
 * @param  mixed  $timestamp The timestamp to be formatted. It can be in seconds or milliseconds.
 * @param  string $format   The format in which the date should be output. Defaults to 'Y-m-d H:i:s'.
 * @return string           The formatted date string.
 */
function format_datetime( $timestamp, $format = 'Y-m-d H:i:s' ) {
	if ( strlen( $timestamp ) >= 13 ) {
		$timestamp_in_seconds = $timestamp / 1000;
		$date                 = new \DateTime();
		$date->setTimestamp( (string) $timestamp_in_seconds );
		return $date->format( $format );
	} else {
		$date = new \DateTime();
		$date->setTimestamp( (string) $timestamp );
		return $date->format( $format );
	}
}

/**
 * Check if the user is member of Concordamos network
 *
 * @param int The author ID
 * @param bool Whether to return true for admin users
 */
function is_concordamos_user( $author, $include_admin = false ) {
	$user = get_user_by( 'ID', $author );

	if ( in_array( 'concordamos_network', $user->roles, true ) ) {
		return true;
	} elseif ( $include_admin && in_array( 'administrator', $user->roles, true ) ) {
		return true;
	}

	return false;
}

/**
 *
 * Checks whether a given date is in the future or present.
 *
 * @param  string $date_string The input date as a string, expected to be in 'Y-m-d H:i:s' format.
 * @return bool   Returns true if the input date is in the future or present, false otherwise.
 */
function is_future_date( $date_string ) {
	$now = new \DateTime();

	$input_date = \DateTime::createFromFormat( 'Y-m-d H:i:s', $date_string );

	$interval = $now->diff( $input_date );

	return $interval->invert === 0;
}

/**
 *
 * Checks if the currently logged-in user is the author of a given post.
 *
 * @param int $voting_id The ID of the post to check.
 *
 * @return bool True if the currently logged-in user is the author of the post, false otherwise.
 */
function is_voting_owner( $voting_id ) {
	$current_user_id = get_current_user_id();

	$post_author_id = get_post_field( 'post_author', $voting_id );

	if ( $current_user_id == $post_author_id ) {
		return true;
	} else {
		return false;
	}
}

function get_translation_template( $voting_slug ) {
	$voting   = get_post_by_slug( 'voting', $voting_slug );
	$original = get_post( get_original_post( 'voting', $voting->ID ) );

	$options  = get_options_by_voting( $original->ID );

	$locale = get_post_meta( $original, 'locale', true );
	$locale = empty( $locale ) ? get_default_language() : $locale;

	$raw_tags = get_the_terms( $original, 'tag' );
	$tags     = array();
	if ( ! empty( $raw_tags ) ) {
		foreach ( $raw_tags as $tag ) {
			$tags[] = $tag->name;
		}
	}

	$translation_template = array(
		'tags'               => implode( ',', $tags ),
		'voting_description' => $original->post_content,
		'voting_name'        => $original->post_title,
		'voting_options'     => $options,
	);

	return $translation_template;
}

function get_voting_status( \WP_Post $post ) {
	$start = get_post_meta( $post->ID, 'date_start', true );
	$end   = get_post_meta( $post->ID, 'date_end', true );
	$now   = 1000 * time();

	if ( $now > $end ) {
		return 'past';
	} elseif ( $now < $start ) {
		return 'future';
	} else {
		return 'present';
	}
}

function prepare_voting_for_api( \WP_Post $post ) {
	$raw_post_meta = get_post_meta( $post->ID );
	$post_meta     = array();

	$skipped_meta = array( 'expired_unique_id', 'unique_ids' );

	foreach ( $raw_post_meta as $key => $value ) {
		if ( ! str_starts_with( $key, '_' ) && ! in_array( $key, $skipped_meta, true ) && ! empty( $value ) ) {
			if ( is_numeric( $value[0] ) ) {
				$post_meta[ $key ] = intval( $value[0] );
			} else {
				$post_meta[ $key ] = $value[0];
			}
		}
	}

	$raw_tags = get_the_terms( $post, 'tag' );
	$tags     = array();

	foreach ( $raw_tags as $tag ) {
		$tags[] = array(
			'ID'   => $tag->term_id,
			'name' => $tag->name,
			'slug' => $tag->slug,
		);
	}

	return array(
		'ID'        => $post->ID,
		'title'     => $post->post_title,
		'content'   => $post->post_content,
		'excerpt'   => $post->post_excerpt,
		'status'    => $post->post_status,
		'permalink' => get_permalink( $post ),
		'time'      => get_voting_status( $post ),
		'meta'      => $post_meta,
		'tags'      => $tags,
	);
}

function register_localized_script( $handle, $src, $deps, $object_name = 'concordamos', $l10n = array() ) {
	wp_register_script( $handle, $src, $deps, CONCORDAMOS_PLUGIN_VERSION, true );
	wp_set_script_translations( $handle, 'concordamos', CONCORDAMOS_PLUGIN_PATH . 'languages/' );
	wp_localize_script( $handle, $object_name, $l10n );
}

function enqueue_localized_script( $handle, $src, $deps, $object_name = 'concordamos', $l10n = array() ) {
	register_localized_script( $handle, $src, $deps, $object_name, $l10n );
	wp_enqueue_script( $handle );
}

function get_page_by_template( string $template ) {
	$pages = get_posts(
		array(
			'post_type'    => 'page',
			'meta_key'     => '_wp_page_template',
			'hierarchical' => 0,
			'meta_value'   => $template,
		)
	);

	foreach ( $pages as $page ) {
		return $page;
	}

	return false;
}

function get_post_by_slug( string $post_type, string $slug ) {
	$posts = get_posts(
		array(
			'post_type' => $post_type,
			'post_name' => $slug,
		)
	);

	foreach ( $posts as $post ) {
		return $post;
	}

	return false;
}

/**
 *
 * Count votes of the voting.
 *
 * @param int $voting_id The ID of the voting to count.
 *
 * @return int count votes.
 */
function get_vote_count( $voting_id ) {

	$args = array(
		'post_type'  => 'vote',
		'fields'     => 'ids',
		'meta_query' => array(
			array(
				'key'     => 'voting_id',
				'value'   => $voting_id,
				'compare' => '=',
				'type'    => 'NUMERIC',
			),
		),
	);

	$query = new \WP_Query( $args );

	$votes = count( $query->posts );

	return $votes;
}

function is_voting_closed( $voting_id ) {
	if ( get_vote_count( $voting_id ) >= intval( get_post_meta( $voting_id, 'number_voters', true ) ) ) {
		return true;
	}

	/**
	 * Checks if voting requires login and user has already voted
	 */
	if ( get_post_meta( $voting_id, 'voting_access', true ) === 'yes' && get_vote_by_user( $voting_id ) ) {
		return true;
	}

	/**
	 * Checks if user has already voted
	 */
	if ( get_vote_by_user( $voting_id ) ) {
		return true;
	}

	$now = 1000 * time();
	if ( intval( get_post_meta( $voting_id, 'date_end', true ) ) <= $now ) {
		return true;
	}

	return false;
}

function get_vote_by_user( $voting_id, $user_id = '' ) {

	if ( ! is_user_logged_in() ) {
		return false;
	}

	if ( empty( $user_id ) ) {
		$user_id = get_current_user_id();
	}

	$args = array(
		'post_type'  => 'vote',
		'fields'     => 'ids',
		'author'     => $user_id,
		'meta_query' => array(
			'compare' => 'AND',
			array(
				'key'     => 'logged_user',
				'value'   => 'yes',
				'compare' => '=',
			),
			array(
				'key'     => 'voting_id',
				'value'   => $voting_id,
				'compare' => '=',
			),
		),
	);

	$query = new \WP_Query( $args );

	$votes = count( $query->posts );

	return $votes;
}

function get_panel_url( $url ) {
	if ( strpos( $url, '?' ) !== false ) {
		return $url . '&panel=1';
	} else {
		return $url . '?panel=1';
	}
}

function get_change_password_token_status() {
	$token_key = sanitize_key( filter_input( INPUT_GET, 'concordamos_change_pass_tk' ) );
	if ( empty( $token_key ) ) {
		return false;
	}

	$token = User_Change_Pass::get_token( $token_key );
	if ( ! $token ) {
		return 'invalid';
	}

	return true;
}

function get_link_to_faq() {
	$faq_page = get_faq_page();

	if ( ! empty( $faq_page ) ) {
		printf( '<a href="%s" class="concordamos-button-faq">%s</a>', esc_url( get_permalink( $faq_page ) ), esc_html__( 'How to vote', 'concordamos' ) );
	}
}
add_action( 'wp_footer', 'Concordamos\get_link_to_faq' );
