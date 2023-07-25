<?php

namespace Concordamos;

function get_voting_page() {
	$options = get_option( 'concordamos_options' );

	if ( isset( $options['votting_page'] ) && ! empty( $options['votting_page'] ) ) {
		return $options['votting_page'];
	}

	return '';
}

function get_options_by_voting( $voting_id ) {
	$args = [
		'post_type' => 'option',
		'meta_query' => [
			[
				'key' => 'voting_id',
				'value' => $voting_id,
				'compare' => '='
			]
		]
	];

	$options = \get_posts( $args );

	$return = [];

	if ( $options ) {
		foreach ( $options as $option ) {
			$raw_post_meta = \get_post_meta( $option->ID );

			$return[$option->ID] = [
				'option_name'        => $raw_post_meta['option_name'][0],
				'option_description' => $raw_post_meta['option_description'][0],
				'option_link'        => $raw_post_meta['option_link'][0]
			];
		}
	}

	return $return;
}

/**
 *
 * Create list of the terms by taxonomy
 *
 * @param int $post_id Post ID
 * @param string $tax Slug tax to get terms
 * @param bool $use_link Define if is use link to the terms
 *
 * @link https://developer.wordpress.org/reference/functions/get_the_terms/
 * @link https://developer.wordpress.org/reference/functions/sanitize_title/
 * @link https://developer.wordpress.org/reference/functions/esc_url/
 * @link https://developer.wordpress.org/reference/functions/get_term_link/
 *
 * @return string $html
 *
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

        $html .= "#";
        $html .= esc_attr( $term->name );

        if ( $use_link ) {
            $html .= '</a>';
        }

        $html .= '</li>';

    }

    $html .= '</ul>';

    return $html;

}
