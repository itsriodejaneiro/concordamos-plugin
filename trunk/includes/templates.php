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
