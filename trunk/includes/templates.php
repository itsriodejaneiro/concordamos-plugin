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
add_filter( 'singular_template', 'Concordamos\load_single_voting_template' );

function load_voting_archive_template( $template ) {
	if ( is_post_type_archive( 'voting' ) && $template !== locate_template( [ 'archive-voting.php' ] ) ) {
		return CONCORDAMOS_PLUGIN_PATH . 'templates/archive-voting.php';
	}

	return $template;
}

add_filter( 'archive_template', 'Concordamos\load_voting_archive_template' );
