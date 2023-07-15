<?php

namespace Concordamos;

function get_voting_page() {
	$options = get_option( 'concordamos_options' );

	if ( isset( $options['votting_page'] ) && ! empty( $options['votting_page'] ) ) {
		return $options['votting_page'];
	}

	return '';
}
